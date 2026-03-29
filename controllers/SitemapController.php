<?php

class SitemapController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function index(): void
    {
        header('Content-Type: application/xml; charset=utf-8');

        $urls = [];

        // Páginas estáticas
        $urls[] = ['loc' => SITE_URL . '/', 'priority' => '1.0', 'changefreq' => 'daily'];
        $urls[] = ['loc' => SITE_URL . '/directorio', 'priority' => '0.9', 'changefreq' => 'daily'];
        $urls[] = ['loc' => SITE_URL . '/categorias', 'priority' => '0.8', 'changefreq' => 'weekly'];
        $urls[] = ['loc' => SITE_URL . '/turismo', 'priority' => '0.8', 'changefreq' => 'weekly'];
        $urls[] = ['loc' => SITE_URL . '/patrimonio', 'priority' => '0.8', 'changefreq' => 'weekly'];
        $urls[] = ['loc' => SITE_URL . '/noticias', 'priority' => '0.8', 'changefreq' => 'daily'];
        $urls[] = ['loc' => SITE_URL . '/mapa', 'priority' => '0.7', 'changefreq' => 'weekly'];
        $urls[] = ['loc' => SITE_URL . '/buscar', 'priority' => '0.5', 'changefreq' => 'monthly'];

        // Categorías
        $stmt = $this->db->query("SELECT slug, updated_at FROM categorias WHERE activo = 1 ORDER BY nombre");
        while ($row = $stmt->fetch()) {
            $urls[] = [
                'loc' => SITE_URL . '/categoria/' . $row['slug'],
                'lastmod' => date('Y-m-d', strtotime($row['updated_at'])),
                'priority' => '0.7',
                'changefreq' => 'weekly',
            ];
        }

        // Negocios
        $stmt = $this->db->query("SELECT slug, updated_at FROM negocios WHERE activo = 1 ORDER BY nombre");
        while ($row = $stmt->fetch()) {
            $urls[] = [
                'loc' => SITE_URL . '/negocio/' . $row['slug'],
                'lastmod' => date('Y-m-d', strtotime($row['updated_at'])),
                'priority' => '0.8',
                'changefreq' => 'weekly',
            ];
        }

        // Noticias
        $stmt = $this->db->query("SELECT slug, updated_at FROM noticias WHERE estado = 'publicada' ORDER BY publicado_en DESC");
        while ($row = $stmt->fetch()) {
            $urls[] = [
                'loc' => SITE_URL . '/noticias/' . $row['slug'],
                'lastmod' => date('Y-m-d', strtotime($row['updated_at'])),
                'priority' => '0.6',
                'changefreq' => 'monthly',
            ];
        }

        // Páginas
        $stmt = $this->db->query("SELECT slug, updated_at FROM paginas WHERE activo = 1 ORDER BY titulo");
        while ($row = $stmt->fetch()) {
            $urls[] = [
                'loc' => SITE_URL . '/pagina/' . $row['slug'],
                'lastmod' => date('Y-m-d', strtotime($row['updated_at'])),
                'priority' => '0.5',
                'changefreq' => 'monthly',
            ];
        }

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $url) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($url['loc']) . "</loc>\n";
            if (!empty($url['lastmod'])) {
                echo "    <lastmod>" . $url['lastmod'] . "</lastmod>\n";
            }
            echo "    <changefreq>" . $url['changefreq'] . "</changefreq>\n";
            echo "    <priority>" . $url['priority'] . "</priority>\n";
            echo "  </url>\n";
        }
        echo '</urlset>';
    }
}
