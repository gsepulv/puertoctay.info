<?php

class SeoHelper
{
    /**
     * Meta tags para el <head>.
     */
    public static function metaTags(string $title, string $description, ?string $image = null, ?string $url = null): string
    {
        $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars(mb_substr(strip_tags($description), 0, 160), ENT_QUOTES, 'UTF-8');
        $url = htmlspecialchars($url ?? self::currentUrl(), ENT_QUOTES, 'UTF-8');

        $html = "<title>{$title}</title>\n";
        $html .= "    <meta name=\"description\" content=\"{$description}\">\n";

        // Open Graph
        $html .= "    <meta property=\"og:title\" content=\"{$title}\">\n";
        $html .= "    <meta property=\"og:description\" content=\"{$description}\">\n";
        $html .= "    <meta property=\"og:url\" content=\"{$url}\">\n";
        $html .= "    <meta property=\"og:type\" content=\"website\">\n";
        $html .= "    <meta property=\"og:site_name\" content=\"" . htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') . "\">\n";

        if ($image) {
            $image = htmlspecialchars($image, ENT_QUOTES, 'UTF-8');
            $html .= "    <meta property=\"og:image\" content=\"{$image}\">\n";
        }

        return $html;
    }

    /**
     * Schema.org LocalBusiness JSON-LD.
     */
    public static function schemaLocalBusiness(array $negocio): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $negocio['tipo'] === 'atractivo' ? 'TouristAttraction' : 'LocalBusiness',
            'name' => $negocio['nombre'],
            'description' => $negocio['descripcion_corta'] ?? '',
            'url' => SITE_URL . '/negocio/' . $negocio['slug'],
        ];

        if (!empty($negocio['direccion'])) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $negocio['direccion'],
                'addressLocality' => 'Puerto Octay',
                'addressRegion' => 'Los Lagos',
                'addressCountry' => 'CL',
            ];
        }

        if (!empty($negocio['lat']) && !empty($negocio['lng'])) {
            $schema['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => (float) $negocio['lat'],
                'longitude' => (float) $negocio['lng'],
            ];
        }

        if (!empty($negocio['telefono'])) {
            $schema['telephone'] = $negocio['telefono'];
        }

        if (!empty($negocio['foto_principal'])) {
            $schema['image'] = SITE_URL . '/uploads/' . $negocio['foto_principal'];
        }

        if (!empty($negocio['horario'])) {
            $schema['openingHours'] = $negocio['horario'];
        }

        // AggregateRating si hay reseñas
        if (!empty($negocio['_rating_avg']) && !empty($negocio['_rating_count'])) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => round((float) $negocio['_rating_avg'], 1),
                'reviewCount' => (int) $negocio['_rating_count'],
                'bestRating' => 5,
                'worstRating' => 1,
            ];
        }

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
    }

    /**
     * Schema.org NewsArticle JSON-LD.
     */
    public static function schemaNewsArticle(array $noticia): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $noticia['schema_type'] ?? 'NewsArticle',
            'headline' => $noticia['titulo'],
            'description' => $noticia['bajada'] ?? mb_substr(strip_tags($noticia['contenido'] ?? ''), 0, 160),
            'url' => SITE_URL . '/noticias/' . $noticia['slug'],
            'datePublished' => date('c', strtotime($noticia['publicado_en'] ?? $noticia['created_at'])),
            'dateModified' => date('c', strtotime($noticia['updated_at'] ?? $noticia['created_at'])),
            'publisher' => [
                '@type' => 'Organization',
                'name' => SITE_NAME,
                'url' => SITE_URL,
            ],
        ];

        if (!empty($noticia['autor'])) {
            $schema['author'] = [
                '@type' => 'Person',
                'name' => $noticia['autor'],
            ];
        }

        if (!empty($noticia['foto_destacada'])) {
            $schema['image'] = SITE_URL . '/uploads/' . $noticia['foto_destacada'];
        }

        if (!empty($noticia['tiempo_lectura'])) {
            $schema['timeRequired'] = 'PT' . (int) $noticia['tiempo_lectura'] . 'M';
        }

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
    }

    private static function currentUrl(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        return $protocol . '://' . ($_SERVER['HTTP_HOST'] ?? '') . ($_SERVER['REQUEST_URI'] ?? '');
    }
}
