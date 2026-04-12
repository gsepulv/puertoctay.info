<?php
/**
 * Helper para mostrar campos específicos en la ficha pública.
 * Formatea valores del JSON campos_especificos para presentación al visitante.
 */

class FichaCamposHelper
{
    private static array $etiquetas = [
        'capacidad_personas' => ['icono' => '👥', 'label' => 'Capacidad', 'sufijo' => ' personas'],
        'habitaciones' => ['icono' => '🛏️', 'label' => 'Habitaciones'],
        'banos' => ['icono' => '🚿', 'label' => 'Baños'],
        'check_in' => ['icono' => '🔑', 'label' => 'Check-in'],
        'check_out' => ['icono' => '🚪', 'label' => 'Check-out'],
        'precio_desde' => ['icono' => '💰', 'label' => 'Desde', 'formato' => 'precio'],
        'precio_hasta' => ['icono' => '💰', 'label' => 'Hasta', 'formato' => 'precio'],
        'sitios_total' => ['icono' => '⛺', 'label' => 'Sitios disponibles'],
        'precio_sitio' => ['icono' => '💰', 'label' => 'Precio por sitio', 'formato' => 'precio'],
        'duracion_minutos' => ['icono' => '⏱️', 'label' => 'Duración', 'formato' => 'duracion'],
        'duracion_horas' => ['icono' => '⏱️', 'label' => 'Duración', 'sufijo' => ' horas'],
        'nivel_dificultad' => ['icono' => '📊', 'label' => 'Dificultad'],
        'edad_minima' => ['icono' => '👶', 'label' => 'Edad mínima', 'sufijo' => ' años'],
        'precio_persona' => ['icono' => '💰', 'label' => 'Precio por persona', 'formato' => 'precio'],
        'precio_hora' => ['icono' => '💰', 'label' => 'Precio por hora', 'formato' => 'precio'],
        'precio_dia' => ['icono' => '💰', 'label' => 'Precio por día', 'formato' => 'precio'],
        'distancia_km' => ['icono' => '📏', 'label' => 'Distancia', 'sufijo' => ' km'],
        'desnivel_metros' => ['icono' => '⛰️', 'label' => 'Desnivel', 'sufijo' => ' m'],
        'rango_precios' => ['icono' => '💵', 'label' => 'Rango de precios'],
        'precio_entrada' => ['icono' => '🎟️', 'label' => 'Entrada', 'formato' => 'precio'],
        'horario_visita' => ['icono' => '🕐', 'label' => 'Horario de visita'],
        'tiempo_recorrido' => ['icono' => '⏱️', 'label' => 'Tiempo de recorrido'],
        'min_personas' => ['icono' => '👥', 'label' => 'Mínimo', 'sufijo' => ' personas'],
        'max_personas' => ['icono' => '👥', 'label' => 'Máximo', 'sufijo' => ' personas'],
        'deposito_garantia' => ['icono' => '💳', 'label' => 'Depósito', 'formato' => 'precio'],
        'ano_construccion' => ['icono' => '📅', 'label' => 'Año de construcción'],
    ];

    private static array $booleanLabels = [
        'acepta_mascotas' => '🐕 Acepta mascotas',
        'tiene_electricidad' => '⚡ Electricidad',
        'duchas_calientes' => '🚿 Duchas calientes',
        'acepta_motorhome' => '🚐 Acepta motorhome',
        'incluye_desayuno' => '🍳 Incluye desayuno',
        'bano_privado' => '🚽 Baño privado',
        'acepta_reservas' => '📅 Acepta reservas',
        'tiene_delivery' => '🛵 Delivery',
        'tiene_terraza' => '🏡 Terraza',
        'incluye_equipo' => '🎒 Equipo incluido',
        'incluye_guia' => '🧑‍🏫 Guía incluido',
        'incluye_transporte' => '🚐 Transporte incluido',
        'requiere_reserva' => '📞 Requiere reserva',
        'entrada_pagada' => '🎟️ Entrada pagada',
        'guia_disponible' => '🧑‍🏫 Guía disponible',
        'acceso_movilidad_reducida' => '♿ Acceso movilidad reducida',
        'acepta_tarjeta' => '💳 Acepta tarjeta',
        'acepta_transferencia' => '🏦 Acepta transferencia',
        'tiene_despacho' => '📦 Despacho/Envío',
        'seguro_incluido' => '🛡️ Seguro incluido',
        'incluye_casco' => '⛑️ Casco incluido',
        'disponible_24h' => '🕐 24 horas',
        'ofrece_talleres' => '🎨 Ofrece talleres',
        'ofrece_degustacion' => '🧀 Ofrece degustación',
        'tours_planta' => '🏭 Tours a la planta',
        'tradicion_alemana' => '🇩🇪 Tradición alemana',
        'kuchen_casero' => '🍰 Kuchen casero',
    ];

    private static array $amenitiesLabels = [
        'wifi' => '📶 WiFi', 'calefaccion' => '🔥 Calefacción', 'tv' => '📺 TV',
        'agua_caliente' => '🚿 Agua caliente', 'cocina_equipada' => '🍳 Cocina equipada',
        'refrigerador' => '🧊 Refrigerador', 'parrilla' => '🍖 Parrilla/Quincho',
        'estacionamiento' => '🅿️ Estacionamiento', 'vista_lago' => '🏞️ Vista al lago',
        'vista_volcan' => '🌋 Vista al volcán', 'terraza' => '🏡 Terraza',
        'jardin' => '🌳 Jardín', 'tinaja' => '🛁 Tinaja/Hot tub', 'sauna' => '🧖 Sauna',
    ];

    private static array $dificultadLabels = [
        'facil' => '🟢 Fácil', 'moderado' => '🟡 Moderado', 'dificil' => '🟠 Difícil', 'experto' => '🔴 Experto',
    ];

    public static function formatearValor(string $campo, $valor): ?string
    {
        if ($valor === null || $valor === '') return null;
        $config = self::$etiquetas[$campo] ?? null;
        if (isset($config['formato'])) {
            if ($config['formato'] === 'precio') return '$' . number_format((int)$valor, 0, ',', '.');
            if ($config['formato'] === 'duracion') {
                $v = (int)$valor;
                if ($v >= 60) { $h = floor($v/60); $m = $v%60; return $m > 0 ? "{$h}h {$m}min" : "{$h} horas"; }
                return "{$v} minutos";
            }
        }
        if (isset($config['sufijo'])) return $valor . $config['sufijo'];
        if ($campo === 'nivel_dificultad') return self::$dificultadLabels[$valor] ?? $valor;
        return (string)$valor;
    }

    public static function renderDatosBasicos(array $campos): string
    {
        $datosKeys = array_keys(self::$etiquetas);
        $html = '<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:0.75rem;">';
        $found = false;
        foreach ($datosKeys as $campo) {
            if (!empty($campos[$campo]) && !is_array($campos[$campo])) {
                $config = self::$etiquetas[$campo];
                $valor = self::formatearValor($campo, $campos[$campo]);
                if ($valor === null) continue;
                $found = true;
                $html .= '<div style="display:flex;align-items:center;gap:0.5rem;padding:0.65rem;background:#F7FAFC;border-radius:8px;">';
                $html .= '<span style="font-size:1.1rem;">' . $config['icono'] . '</span>';
                $html .= '<span style="color:#718096;font-size:0.85rem;">' . htmlspecialchars($config['label']) . ':</span>';
                $html .= '<span style="font-weight:600;color:#2D3748;">' . htmlspecialchars($valor) . '</span>';
                $html .= '</div>';
            }
        }
        $html .= '</div>';
        return $found ? $html : '';
    }

    public static function renderAmenities(array $campos): string
    {
        $amenities = $campos['amenities'] ?? [];
        if (empty($amenities) || !is_array($amenities)) return '';
        $html = '<div style="margin-top:1rem;"><h4 style="margin:0 0 0.5rem;">🏠 Comodidades</h4>';
        $html .= '<div style="display:flex;flex-wrap:wrap;gap:0.5rem;">';
        foreach ($amenities as $a) {
            if (isset(self::$amenitiesLabels[$a])) {
                $html .= '<span style="padding:0.4rem 0.75rem;background:#EDF2F7;border-radius:20px;font-size:0.85rem;">' . self::$amenitiesLabels[$a] . '</span>';
            }
        }
        $html .= '</div></div>';
        return $html;
    }

    public static function renderServicios(array $campos): string
    {
        $items = [];
        foreach (self::$booleanLabels as $key => $label) {
            if (!empty($campos[$key])) $items[] = $label;
        }
        if (empty($items)) return '';
        $html = '<div style="margin-top:1rem;"><h4 style="margin:0 0 0.5rem;">✨ Servicios</h4>';
        $html .= '<div style="display:flex;flex-wrap:wrap;gap:0.5rem;">';
        foreach ($items as $item) {
            $html .= '<span style="padding:0.4rem 0.75rem;background:#F0FFF4;border-radius:20px;font-size:0.85rem;border:1px solid #C6F6D5;">' . $item . '</span>';
        }
        $html .= '</div></div>';
        return $html;
    }

    public static function renderCheckboxGroup(array $campos, string $key, string $titulo, array $labels): string
    {
        $valores = $campos[$key] ?? [];
        if (empty($valores) || !is_array($valores)) return '';
        $html = '<div style="margin-top:1rem;"><h4 style="margin:0 0 0.5rem;">' . htmlspecialchars($titulo) . '</h4>';
        $html .= '<div style="display:flex;flex-wrap:wrap;gap:0.5rem;">';
        foreach ($valores as $v) {
            if (isset($labels[$v])) {
                $html .= '<span style="padding:0.4rem 0.75rem;background:#EDF2F7;border-radius:20px;font-size:0.85rem;">' . $labels[$v] . '</span>';
            }
        }
        $html .= '</div></div>';
        return $html;
    }

    public static function renderTextos(array $campos): string
    {
        $textKeys = ['especialidades', 'itinerario', 'cervezas_propias', 'tipos_queso', 'tipo_coleccion',
                     'estilo_arquitectonico', 'senderos', 'rutas', 'rutas_sugeridas'];
        $html = '';
        foreach ($textKeys as $key) {
            if (!empty($campos[$key]) && is_string($campos[$key])) {
                $label = ucfirst(str_replace('_', ' ', $key));
                $html .= '<div style="margin-top:1rem;">';
                $html .= '<h4 style="margin:0 0 0.25rem;">📝 ' . htmlspecialchars($label) . '</h4>';
                $html .= '<p style="color:#4A5568;line-height:1.6;">' . nl2br(htmlspecialchars($campos[$key])) . '</p>';
                $html .= '</div>';
            }
        }
        return $html;
    }
}
