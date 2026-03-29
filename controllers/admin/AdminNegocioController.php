<?php
/**
 * AdminNegocioController — visitapuertoctay.cl
 * CRUD completo de negocios
 */

class AdminNegocioController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // ── GET /admin/negocios ──────────────────────────────────

    public function index(): void
    {
        AuthMiddleware::check();

        $negocios = $this->db->query("
            SELECT n.*, c.nombre AS categoria_nombre, p.nombre AS plan_nombre
            FROM negocios n
            LEFT JOIN categorias_directorio c ON c.id = n.categoria_id
            LEFT JOIN planes p ON p.id = n.plan_id
            ORDER BY n.nombre ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/admin/negocios/index.php';
    }

    // ── GET /admin/negocios/crear ────────────────────────────

    public function crear(): void
    {
        AuthMiddleware::check();

        $negocio    = [];
        $categorias = $this->getCategorias();
        $planes     = $this->getPlanes();
        $errores    = [];

        require __DIR__ . '/../views/admin/negocios/form.php';
    }

    // ── POST /admin/negocios/guardar ─────────────────────────

    public function guardar(): void
    {
        AuthMiddleware::check();
        $this->verificarCsrf();

        $data    = $this->sanitize($_POST);
        $errores = $this->validar($data);

        if (!empty($errores)) {
            $negocio    = $data;
            $categorias = $this->getCategorias();
            $planes     = $this->getPlanes();
            require __DIR__ . '/../views/admin/negocios/form.php';
            return;
        }

        // Checkboxes
        $data['activo']     = isset($_POST['activo']) ? 1 : 0;
        $data['verificado'] = isset($_POST['verificado']) ? 1 : 0;
        $data['destacado']  = isset($_POST['destacado']) ? 1 : 0;

        // Nullable numeric
        $data['categoria_id']         = !empty($data['categoria_id']) ? (int)$data['categoria_id'] : null;
        $data['plan_id']              = !empty($data['plan_id']) ? (int)$data['plan_id'] : 1;
        $data['lat']                  = !empty($data['lat']) ? $data['lat'] : null;
        $data['lng']                  = !empty($data['lng']) ? $data['lng'] : null;
        $data['monto_mensual']        = (int)($data['monto_mensual'] ?? 0);
        $data['fecha_inicio_contrato'] = !empty($data['fecha_inicio_contrato']) ? $data['fecha_inicio_contrato'] : null;

        // Slug
        $data['slug'] = $this->generarSlug($data['nombre']);

        // Remove non-DB fields
        unset($data['csrf_token']);

        // Handle 3 file uploads
        foreach (['foto_principal' => 'negocios', 'portada' => 'portadas', 'logo' => 'logos'] as $field => $subdir) {
            if (!empty($_FILES[$field]['name'])) {
                $path = ImageHelper::upload($_FILES[$field], $subdir);
                if ($path) {
                    $data[$field] = $path;
                }
            }
        }

        // Build INSERT
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn($k) => ":$k", array_keys($data)));

        $stmt = $this->db->prepare("INSERT INTO negocios ($columns) VALUES ($placeholders)");
        $stmt->execute($data);

        header('Location: ' . SITE_URL . '/admin/negocios?success=' . urlencode('Negocio creado correctamente'));
        exit;
    }

    // ── GET /admin/negocios/{id}/editar ──────────────────────

    public function editar(int $id): void
    {
        AuthMiddleware::check();

        $negocio = $this->findOrFail($id);
        $categorias = $this->getCategorias();
        $planes     = $this->getPlanes();
        $errores    = [];

        require __DIR__ . '/../views/admin/negocios/form.php';
    }

    // ── POST /admin/negocios/{id}/actualizar ─────────────────

    public function actualizar(int $id): void
    {
        AuthMiddleware::check();
        $this->verificarCsrf();

        $negocio = $this->findOrFail($id);
        $data    = $this->sanitize($_POST);
        $errores = $this->validar($data);

        if (!empty($errores)) {
            $negocio    = array_merge($negocio, $data);
            $categorias = $this->getCategorias();
            $planes     = $this->getPlanes();
            require __DIR__ . '/../views/admin/negocios/form.php';
            return;
        }

        // Checkboxes
        $data['activo']     = isset($_POST['activo']) ? 1 : 0;
        $data['verificado'] = isset($_POST['verificado']) ? 1 : 0;
        $data['destacado']  = isset($_POST['destacado']) ? 1 : 0;

        // Nullable numeric
        $data['categoria_id']         = !empty($data['categoria_id']) ? (int)$data['categoria_id'] : null;
        $data['plan_id']              = !empty($data['plan_id']) ? (int)$data['plan_id'] : 1;
        $data['lat']                  = !empty($data['lat']) ? $data['lat'] : null;
        $data['lng']                  = !empty($data['lng']) ? $data['lng'] : null;
        $data['monto_mensual']        = (int)($data['monto_mensual'] ?? 0);
        $data['fecha_inicio_contrato'] = !empty($data['fecha_inicio_contrato']) ? $data['fecha_inicio_contrato'] : null;

        // Slug (regenerate if name changed)
        if ($data['nombre'] !== $negocio['nombre']) {
            $data['slug'] = $this->generarSlug($data['nombre'], $id);
        }

        // Remove non-DB fields
        unset($data['csrf_token']);

        // Handle 3 file uploads
        foreach (['foto_principal' => 'negocios', 'portada' => 'portadas', 'logo' => 'logos'] as $field => $subdir) {
            if (!empty($_FILES[$field]['name'])) {
                $path = ImageHelper::upload($_FILES[$field], $subdir);
                if ($path) {
                    // Delete old file
                    if (!empty($negocio[$field])) {
                        ImageHelper::delete($negocio[$field]);
                    }
                    $data[$field] = $path;
                }
            }
        }

        // Build UPDATE
        $sets = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
        $data['_id'] = $id;

        $stmt = $this->db->prepare("UPDATE negocios SET $sets WHERE id = :_id");
        $stmt->execute($data);

        header('Location: ' . SITE_URL . '/admin/negocios?success=' . urlencode('Negocio actualizado correctamente'));
        exit;
    }

    // ── POST /admin/negocios/{id}/eliminar ───────────────────

    public function eliminar(int $id): void
    {
        AuthMiddleware::check();
        $this->verificarCsrf();

        $negocio = $this->findOrFail($id);

        // Delete associated files
        foreach (['foto_principal', 'portada', 'logo'] as $field) {
            if (!empty($negocio[$field])) {
                ImageHelper::delete($negocio[$field]);
            }
        }

        $stmt = $this->db->prepare("DELETE FROM negocios WHERE id = :id");
        $stmt->execute(['id' => $id]);

        header('Location: ' . SITE_URL . '/admin/negocios?success=' . urlencode('Negocio eliminado correctamente'));
        exit;
    }

    // ── POST /admin/negocios/{id}/verificar ──────────────────

    public function verificar(int $id): void
    {
        AuthMiddleware::check();
        $this->verificarCsrf();

        $negocio = $this->findOrFail($id);

        $nuevoValor = $negocio['verificado'] ? 0 : 1;
        $stmt = $this->db->prepare("UPDATE negocios SET verificado = :v WHERE id = :id");
        $stmt->execute(['v' => $nuevoValor, 'id' => $id]);

        $msg = $nuevoValor ? 'Negocio verificado' : 'Verificación removida';
        header('Location: ' . SITE_URL . '/admin/negocios?success=' . urlencode($msg));
        exit;
    }

    // ══ Helpers privados ═════════════════════════════════════

    private function sanitize(array $post): array
    {
        $fields = [
            'nombre', 'tipo', 'descripcion_corta', 'descripcion_larga',
            'direccion', 'horario', 'telefono', 'whatsapp', 'email', 'sitio_web',
            'red_social_1', 'red_social_2',
            'facebook', 'instagram', 'tiktok', 'youtube', 'twitter', 'linkedin', 'telegram', 'pinterest',
            'como_llegar', 'precio_referencial',
            'meta_title', 'meta_description',
            'razon_social', 'rut_empresa', 'giro_comercial',
            'direccion_tributaria', 'comuna_tributaria',
            'nombre_propietario', 'rut_propietario', 'telefono_privado', 'email_facturacion',
            'metodo_pago',
        ];

        $data = [];
        foreach ($fields as $f) {
            $data[$f] = trim($post[$f] ?? '');
        }

        // Non-string fields kept as-is for later processing
        $data['categoria_id']         = $post['categoria_id'] ?? '';
        $data['plan_id']              = $post['plan_id'] ?? '';
        $data['lat']                  = $post['lat'] ?? '';
        $data['lng']                  = $post['lng'] ?? '';
        $data['monto_mensual']        = $post['monto_mensual'] ?? 0;
        $data['fecha_inicio_contrato'] = $post['fecha_inicio_contrato'] ?? '';
        $data['csrf_token']           = $post['_csrf'] ?? '';

        return $data;
    }

    private function validar(array $data): array
    {
        $errores = [];
        if (empty($data['nombre'])) {
            $errores[] = 'El nombre es obligatorio.';
        }
        if (empty($data['tipo'])) {
            $errores[] = 'El tipo es obligatorio.';
        }
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El email no es válido.';
        }
        if (!empty($data['sitio_web']) && !filter_var($data['sitio_web'], FILTER_VALIDATE_URL)) {
            $errores[] = 'La URL del sitio web no es válida.';
        }
        return $errores;
    }

    private function findOrFail(int $id): array
    {
        $stmt = $this->db->prepare("SELECT * FROM negocios WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $negocio = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$negocio) {
            header('Location: ' . SITE_URL . '/admin/negocios?error=' . urlencode('Negocio no encontrado'));
            exit;
        }

        return $negocio;
    }

    private function getCategorias(): array
    {
        return $this->db->query(
            "SELECT id, emoji, nombre FROM categorias_directorio ORDER BY nombre"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getPlanes(): array
    {
        return $this->db->query(
            "SELECT id, nombre, precio FROM planes WHERE activo = 1 ORDER BY precio ASC"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    private function generarSlug(string $nombre, ?int $excludeId = null): string
    {
        $slug = mb_strtolower(trim($nombre));
        $slug = preg_replace('/[áàäâ]/u', 'a', $slug);
        $slug = preg_replace('/[éèëê]/u', 'e', $slug);
        $slug = preg_replace('/[íìïî]/u', 'i', $slug);
        $slug = preg_replace('/[óòöô]/u', 'o', $slug);
        $slug = preg_replace('/[úùüû]/u', 'u', $slug);
        $slug = preg_replace('/ñ/u', 'n', $slug);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');

        // Ensure uniqueness
        $baseSlug = $slug;
        $suffix   = 2;

        while (true) {
            $sql = "SELECT COUNT(*) FROM negocios WHERE slug = :slug";
            $params = ['slug' => $slug];

            if ($excludeId) {
                $sql .= " AND id != :eid";
                $params['eid'] = $excludeId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            if ((int)$stmt->fetchColumn() === 0) {
                break;
            }

            $slug = $baseSlug . '-' . $suffix++;
        }

        return $slug;
    }

    private function verificarCsrf(): void
    {
        if (($_POST['_csrf'] ?? '') !== csrf_token()) {
            header('Location: ' . SITE_URL . '/admin/negocios?error=' . urlencode('Token CSRF inválido'));
            exit;
        }
    }
}
