<?php

class AdminAparienciaController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::checkAdmin();
    }

    public function index(): void
    {
        $configModel = new Configuracion($this->db);

        $colores = [
            'color_primary'   => $this->getConfig($configModel, 'apariencia', 'color_primary', COLOR_PRIMARY),
            'color_secondary' => $this->getConfig($configModel, 'apariencia', 'color_secondary', COLOR_SECONDARY),
            'color_accent'    => $this->getConfig($configModel, 'apariencia', 'color_accent', COLOR_ACCENT),
        ];

        $pageTitle = 'Apariencia';
        $viewName = 'admin/apariencia/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function guardar(): void
    {
        CsrfMiddleware::validate();

        $campos = [
            'color_primary'   => 'Color primario',
            'color_secondary' => 'Color secundario',
            'color_accent'    => 'Color de acento',
        ];

        foreach ($campos as $clave => $etiqueta) {
            $valor = trim($_POST[$clave] ?? '');
            if (!preg_match('/^#[0-9a-fA-F]{6}$/', $valor)) {
                continue;
            }
            $this->upsertConfig('apariencia', $clave, $valor, 'color', $etiqueta);
        }

        $_SESSION['flash_success'] = 'Colores guardados correctamente.';
        header('Location: ' . SITE_URL . '/admin/apariencia');
        exit;
    }

    private function getConfig(Configuracion $model, string $grupo, string $clave, string $default): string
    {
        $row = $model->findBy('clave', $clave);
        if ($row && $row['grupo'] === $grupo && !empty($row['valor'])) {
            return $row['valor'];
        }
        return $default;
    }

    private function upsertConfig(string $grupo, string $clave, string $valor, string $tipo, string $etiqueta): void
    {
        $stmt = $this->db->prepare("SELECT id FROM configuracion WHERE grupo = :g AND clave = :c");
        $stmt->execute(['g' => $grupo, 'c' => $clave]);
        $existing = $stmt->fetch();

        if ($existing) {
            $stmt = $this->db->prepare("UPDATE configuracion SET valor = :v, updated_at = NOW() WHERE id = :id");
            $stmt->execute(['v' => $valor, 'id' => $existing['id']]);
        } else {
            $stmt = $this->db->prepare(
                "INSERT INTO configuracion (grupo, clave, valor, tipo, etiqueta, orden) VALUES (:g, :c, :v, :t, :e, 0)"
            );
            $stmt->execute(['g' => $grupo, 'c' => $clave, 'v' => $valor, 't' => $tipo, 'e' => $etiqueta]);
        }
    }
}
