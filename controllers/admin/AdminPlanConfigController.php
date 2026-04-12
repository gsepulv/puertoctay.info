<?php
/**
 * AdminPlanConfigController — Gestión de planes comerciales.
 * Solo index (listar) y actualizar. NO crear ni eliminar.
 */

class AdminPlanConfigController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::checkAdmin();
    }

    public function index(): void
    {
        $model = new PlanConfig($this->db);
        $planes = $model->findOrdenados();

        $pageTitle = 'Gestión de Planes — Admin';
        $viewName = 'admin/planes-config/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function editar(string $id): void
    {
        $model = new PlanConfig($this->db);
        $plan = $model->find((int) $id);

        if (!$plan) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $errores = [];
        $pageTitle = 'Editar: ' . htmlspecialchars($plan['nombre']) . ' — Admin';
        $viewName = 'admin/planes-config/form';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function actualizar(string $id): void
    {
        CsrfMiddleware::validate();
        $model = new PlanConfig($this->db);
        $plan = $model->find((int) $id);

        if (!$plan) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $data = Sanitizer::cleanArray($_POST);
        $errores = $this->validar($data);

        if (!empty($errores)) {
            $plan = array_merge($plan, $data);
            $pageTitle = 'Editar: ' . htmlspecialchars($plan['nombre']) . ' — Admin';
            $viewName = 'admin/planes-config/form';
            require ROOT_PATH . '/views/layouts/admin.php';
            return;
        }

        $update = $this->preparar($data);
        $model->update((int) $id, $update);

        AuditLog::log('editar', 'planes_config', (int) $id, "Plan: {$plan['nombre']}");

        $_SESSION['flash_success'] = 'Plan «' . htmlspecialchars($data['nombre']) . '» actualizado correctamente.';
        header('Location: ' . SITE_URL . '/admin/planes-config');
        exit;
    }

    private function validar(array $data): array
    {
        $errores = [];

        if (empty($data['nombre']) || mb_strlen($data['nombre']) < 3) {
            $errores[] = 'El nombre es obligatorio (mínimo 3 caracteres).';
        }
        if (mb_strlen($data['nombre'] ?? '') > 50) {
            $errores[] = 'El nombre no puede superar los 50 caracteres.';
        }
        if (empty($data['color']) || !preg_match('/^#[0-9a-fA-F]{6}$/', $data['color'])) {
            $errores[] = 'El color debe ser un código hexadecimal válido (#RRGGBB).';
        }
        if (!isset($data['orden']) || (int) $data['orden'] < 1 || (int) $data['orden'] > 10) {
            $errores[] = 'El orden debe ser un número entre 1 y 10.';
        }
        if (empty($data['descripcion']) || mb_strlen($data['descripcion']) < 10) {
            $errores[] = 'La descripción es obligatoria (mínimo 10 caracteres).';
        }
        if (mb_strlen($data['descripcion'] ?? '') > 500) {
            $errores[] = 'La descripción no puede superar los 500 caracteres.';
        }
        if (!isset($data['precio_intro']) || (int) $data['precio_intro'] < 0) {
            $errores[] = 'El precio introductorio debe ser 0 o mayor.';
        }
        if (!isset($data['precio_regular']) || (int) $data['precio_regular'] < 0) {
            $errores[] = 'El precio regular debe ser 0 o mayor.';
        }
        if (!isset($data['duracion_dias']) || (int) $data['duracion_dias'] < 1 || (int) $data['duracion_dias'] > 365) {
            $errores[] = 'La duración debe ser entre 1 y 365 días.';
        }
        if (!empty($data['max_fotos']) && (int) $data['max_fotos'] < 1) {
            $errores[] = 'El máximo de fotos debe ser al menos 1.';
        }
        if (!empty($data['max_redes']) && ((int) $data['max_redes'] < 1 || (int) $data['max_redes'] > 99)) {
            $errores[] = 'El máximo de redes debe ser entre 1 y 99.';
        }
        $posiciones = ['normal', 'prioritaria', 'siempre_primero'];
        if (empty($data['posicion_listado']) || !in_array($data['posicion_listado'], $posiciones)) {
            $errores[] = 'La posición en listado no es válida.';
        }

        return $errores;
    }

    private function preparar(array $data): array
    {
        return [
            'nombre'             => $data['nombre'],
            'icono'              => !empty($data['icono']) ? $data['icono'] : null,
            'color'              => $data['color'],
            'orden'              => (int) $data['orden'],
            'descripcion'        => $_POST['descripcion'] ?? '',
            'precio_intro'       => (int) $data['precio_intro'],
            'precio_regular'     => (int) $data['precio_regular'],
            'duracion_dias'      => (int) $data['duracion_dias'],
            'max_fotos'          => !empty($data['max_fotos']) ? (int) $data['max_fotos'] : null,
            'max_redes'          => !empty($data['max_redes']) ? (int) $data['max_redes'] : null,
            'cupos_globales'     => !empty($data['cupos_globales']) ? (int) $data['cupos_globales'] : null,
            'max_cupos_categoria'=> !empty($data['max_cupos_categoria']) ? (int) $data['max_cupos_categoria'] : null,
            'posicion_listado'   => $data['posicion_listado'],
            'tiene_mapa'         => isset($data['tiene_mapa']) ? 1 : 0,
            'tiene_horarios'     => isset($data['tiene_horarios']) ? 1 : 0,
            'tiene_sello'        => isset($data['tiene_sello']) ? 1 : 0,
            'tiene_reporte'      => isset($data['tiene_reporte']) ? 1 : 0,
            'activo'             => isset($data['activo']) ? 1 : 0,
        ];
    }
}
