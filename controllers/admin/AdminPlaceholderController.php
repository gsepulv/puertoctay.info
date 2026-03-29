<?php

class AdminPlaceholderController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::check();
    }

    public function banners(): void { $this->render('Banners', 'Gestiona los banners del sitio.'); }
    public function estadisticas(): void { $this->render('Estadísticas', 'Visualiza métricas de tráfico y uso del sitio.'); }
    public function mensajes(): void { $this->render('Mensajes', 'Mensajes recibidos desde el formulario de contacto.'); }
    public function nurturing(): void { $this->render('Nurturing', 'Campañas de seguimiento y fidelización.'); }
    public function correo(): void { $this->render('Enviar Correo', 'Envía correos masivos o individuales.'); }
    public function reportes(): void { $this->render('Reportes', 'Reportes de actividad y rendimiento.'); }

    public function redesSociales(): void { $this->render('Redes Sociales', 'Vincula y gestiona las redes sociales del sitio.'); }
    public function apariencia(): void { $this->render('Apariencia', 'Colores, fuentes, logo y personalización visual.'); }
    public function textosLegales(): void { $this->render('Textos Legales', 'Términos de uso, privacidad, cookies.'); }

    public function menu(): void { $this->render('Menú', 'Configura los enlaces del menú de navegación.'); }

    public function mantenimiento(): void { $this->render('Mantenimiento', 'Limpieza de cache, backups, logs.'); }
    public function blog(): void { header('Location: ' . SITE_URL . '/admin/noticias'); exit; }

    private function render(string $titulo, string $descripcion): void
    {
        $pageTitle = $titulo . ' — Admin';
        $placeholderTitulo = $titulo;
        $placeholderDescripcion = $descripcion;
        $viewName = 'admin/placeholder';
        require ROOT_PATH . '/views/layouts/admin.php';
    }
}
