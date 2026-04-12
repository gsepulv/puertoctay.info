<?php
/**
 * Configuración de campos específicos por tipo/subtipo.
 * Cada campo: type, label, required, placeholder, options (si aplica).
 * Se guardan en negocios.campos_especificos como JSON.
 */

return [
    'alojamiento' => [
        '_comunes' => [
            'capacidad_personas' => ['type' => 'number', 'label' => 'Capacidad máxima (personas)', 'required' => true, 'min' => 1],
            'habitaciones' => ['type' => 'number', 'label' => 'Habitaciones'],
            'banos' => ['type' => 'number', 'label' => 'Baños'],
            'check_in' => ['type' => 'time', 'label' => 'Hora check-in', 'placeholder' => '15:00'],
            'check_out' => ['type' => 'time', 'label' => 'Hora check-out', 'placeholder' => '11:00'],
            'precio_desde' => ['type' => 'number', 'label' => 'Precio desde (CLP/noche)', 'min' => 0],
            'precio_hasta' => ['type' => 'number', 'label' => 'Precio hasta (temporada alta)', 'min' => 0],
            'amenities' => ['type' => 'checkbox_group', 'label' => 'Amenities', 'options' => [
                'wifi' => 'WiFi', 'calefaccion' => 'Calefacción', 'tv' => 'TV', 'agua_caliente' => 'Agua caliente',
                'cocina_equipada' => 'Cocina equipada', 'refrigerador' => 'Refrigerador', 'parrilla' => 'Parrilla / Quincho',
                'estacionamiento' => 'Estacionamiento', 'vista_lago' => 'Vista al lago', 'vista_volcan' => 'Vista al volcán',
                'terraza' => 'Terraza', 'jardin' => 'Jardín', 'tinaja' => 'Tinaja / Hot tub', 'sauna' => 'Sauna',
            ]],
            'acepta_mascotas' => ['type' => 'boolean', 'label' => '¿Acepta mascotas?'],
        ],
        'camping' => [
            'sitios_total' => ['type' => 'number', 'label' => 'Sitios totales', 'required' => true],
            'precio_sitio' => ['type' => 'number', 'label' => 'Precio por sitio (CLP/noche)'],
            'tiene_electricidad' => ['type' => 'boolean', 'label' => 'Electricidad disponible'],
            'duchas_calientes' => ['type' => 'boolean', 'label' => 'Duchas calientes'],
            'acepta_motorhome' => ['type' => 'boolean', 'label' => 'Acepta motorhome/camper'],
        ],
        'glamping' => [
            'tipo_estructura' => ['type' => 'select', 'label' => 'Tipo de estructura', 'options' => [
                'domo' => 'Domo', 'tipi' => 'Tipi', 'burbuja' => 'Burbuja', 'carpa_safari' => 'Carpa safari', 'otro' => 'Otro',
            ]],
            'incluye_desayuno' => ['type' => 'boolean', 'label' => 'Incluye desayuno'],
            'bano_privado' => ['type' => 'boolean', 'label' => 'Baño privado'],
        ],
    ],

    'gastronomia' => [
        '_comunes' => [
            'tipo_cocina' => ['type' => 'checkbox_group', 'label' => 'Tipo de cocina', 'options' => [
                'chilena' => 'Chilena tradicional', 'alemana' => 'Alemana', 'mariscos' => 'Mariscos',
                'carnes' => 'Carnes / Parrilla', 'vegetariana' => 'Vegetariana', 'internacional' => 'Internacional',
            ]],
            'especialidades' => ['type' => 'textarea', 'label' => 'Especialidades de la casa', 'placeholder' => 'Ej: Cordero al palo, Kuchen de frambuesa...'],
            'capacidad_personas' => ['type' => 'number', 'label' => 'Capacidad (personas)'],
            'rango_precios' => ['type' => 'select', 'label' => 'Rango de precios', 'options' => [
                '$' => '$ Económico (hasta $8.000)', '$$' => '$$ Moderado ($8.000-$15.000)',
                '$$$' => '$$$ Alto ($15.000-$25.000)', '$$$$' => '$$$$ Premium (sobre $25.000)',
            ]],
            'acepta_reservas' => ['type' => 'boolean', 'label' => 'Acepta reservas'],
            'tiene_delivery' => ['type' => 'boolean', 'label' => 'Tiene delivery'],
            'tiene_terraza' => ['type' => 'boolean', 'label' => 'Tiene terraza/exterior'],
        ],
        'cerveceria' => [
            'cervezas_propias' => ['type' => 'textarea', 'label' => 'Cervezas propias', 'placeholder' => 'Nombres y estilos'],
            'tours_planta' => ['type' => 'boolean', 'label' => 'Ofrece tours a la planta'],
        ],
        'casa-de-te' => [
            'tradicion_alemana' => ['type' => 'boolean', 'label' => 'Tradición alemana'],
            'kuchen_casero' => ['type' => 'boolean', 'label' => 'Kuchen casero'],
        ],
    ],

    'actividad' => [
        '_comunes' => [
            'duracion_minutos' => ['type' => 'number', 'label' => 'Duración (minutos)', 'placeholder' => 'Ej: 120'],
            'nivel_dificultad' => ['type' => 'select', 'label' => 'Nivel de dificultad', 'options' => [
                'facil' => 'Fácil', 'moderado' => 'Moderado', 'dificil' => 'Difícil', 'experto' => 'Experto',
            ]],
            'edad_minima' => ['type' => 'number', 'label' => 'Edad mínima', 'min' => 0],
            'precio_persona' => ['type' => 'number', 'label' => 'Precio por persona (CLP)'],
            'incluye_equipo' => ['type' => 'boolean', 'label' => 'Incluye equipo'],
            'incluye_guia' => ['type' => 'boolean', 'label' => 'Incluye guía'],
            'requiere_reserva' => ['type' => 'boolean', 'label' => 'Requiere reserva previa'],
        ],
        'kayak' => [
            'tipo_kayak' => ['type' => 'checkbox_group', 'label' => 'Tipos disponibles', 'options' => [
                'simple' => 'Simple', 'doble' => 'Doble', 'travesia' => 'Travesía', 'sit_on_top' => 'Sit-on-top',
            ]],
            'precio_hora' => ['type' => 'number', 'label' => 'Precio arriendo (CLP/hora)'],
        ],
        'trekking' => [
            'distancia_km' => ['type' => 'number', 'label' => 'Distancia (km)', 'step' => '0.1'],
            'desnivel_metros' => ['type' => 'number', 'label' => 'Desnivel (metros)'],
        ],
    ],

    'arriendo' => [
        '_comunes' => [
            'precio_hora' => ['type' => 'number', 'label' => 'Precio por hora (CLP)'],
            'precio_dia' => ['type' => 'number', 'label' => 'Precio por día (CLP)'],
            'deposito_garantia' => ['type' => 'number', 'label' => 'Depósito de garantía (CLP)'],
            'seguro_incluido' => ['type' => 'boolean', 'label' => 'Seguro incluido'],
        ],
        'lanchas' => [
            'capacidad_personas' => ['type' => 'number', 'label' => 'Capacidad (personas)', 'required' => true],
            'requiere_licencia' => ['type' => 'boolean', 'label' => 'Requiere licencia náutica'],
        ],
        'bicicletas' => [
            'tipo_bicicleta' => ['type' => 'checkbox_group', 'label' => 'Tipos disponibles', 'options' => [
                'mtb' => 'Mountain bike', 'ruta' => 'Ruta', 'electrica' => 'Eléctrica', 'infantil' => 'Infantil',
            ]],
            'incluye_casco' => ['type' => 'boolean', 'label' => 'Incluye casco'],
        ],
    ],

    'tour' => [
        '_comunes' => [
            'duracion_horas' => ['type' => 'number', 'label' => 'Duración (horas)', 'step' => '0.5'],
            'precio_persona' => ['type' => 'number', 'label' => 'Precio por persona (CLP)'],
            'min_personas' => ['type' => 'number', 'label' => 'Mínimo de personas'],
            'max_personas' => ['type' => 'number', 'label' => 'Máximo de personas'],
            'incluye_transporte' => ['type' => 'boolean', 'label' => 'Incluye transporte'],
            'incluye_almuerzo' => ['type' => 'boolean', 'label' => 'Incluye almuerzo'],
            'idiomas_guia' => ['type' => 'checkbox_group', 'label' => 'Idiomas del guía', 'options' => [
                'espanol' => 'Español', 'ingles' => 'Inglés', 'aleman' => 'Alemán', 'frances' => 'Francés',
            ]],
            'itinerario' => ['type' => 'textarea', 'label' => 'Itinerario', 'placeholder' => 'Paradas y actividades del tour'],
        ],
    ],

    'atractivo' => [
        '_comunes' => [
            'entrada_pagada' => ['type' => 'boolean', 'label' => '¿Entrada pagada?'],
            'precio_entrada' => ['type' => 'number', 'label' => 'Precio entrada (CLP)'],
            'horario_visita' => ['type' => 'text', 'label' => 'Horario de visita', 'placeholder' => 'Ej: 9:00 - 18:00'],
            'tiempo_recorrido' => ['type' => 'text', 'label' => 'Tiempo de recorrido', 'placeholder' => 'Ej: 1-2 horas'],
            'guia_disponible' => ['type' => 'boolean', 'label' => 'Guía disponible'],
            'acceso_movilidad_reducida' => ['type' => 'boolean', 'label' => 'Acceso movilidad reducida'],
        ],
        'museo' => [
            'tipo_coleccion' => ['type' => 'text', 'label' => 'Tipo de colección', 'placeholder' => 'Ej: Historia local, colonización alemana'],
        ],
        'iglesia' => [
            'ano_construccion' => ['type' => 'number', 'label' => 'Año de construcción', 'min' => 1500, 'max' => 2030],
            'estilo_arquitectonico' => ['type' => 'text', 'label' => 'Estilo arquitectónico'],
        ],
    ],

    'comercio' => [
        '_comunes' => [
            'acepta_tarjeta' => ['type' => 'boolean', 'label' => 'Acepta tarjeta'],
            'acepta_transferencia' => ['type' => 'boolean', 'label' => 'Acepta transferencia'],
            'tiene_despacho' => ['type' => 'boolean', 'label' => 'Tiene despacho/envío'],
        ],
        'artesania' => [
            'tipo_artesania' => ['type' => 'checkbox_group', 'label' => 'Tipo de artesanía', 'options' => [
                'madera' => 'Madera', 'lana' => 'Lana/Tejidos', 'ceramica' => 'Cerámica', 'cuero' => 'Cuero',
            ]],
            'ofrece_talleres' => ['type' => 'boolean', 'label' => 'Ofrece talleres'],
        ],
        'queseria' => [
            'tipos_queso' => ['type' => 'textarea', 'label' => 'Tipos de queso', 'placeholder' => 'Ej: Gouda, mantecoso, ahumado...'],
            'ofrece_degustacion' => ['type' => 'boolean', 'label' => 'Ofrece degustación'],
        ],
    ],

    'servicio' => [
        '_comunes' => [
            'requiere_cita' => ['type' => 'boolean', 'label' => 'Requiere cita previa'],
            'disponible_24h' => ['type' => 'boolean', 'label' => 'Disponible 24 horas'],
        ],
        'transfer' => [
            'destinos' => ['type' => 'checkbox_group', 'label' => 'Destinos', 'options' => [
                'aeropuerto_tepual' => 'Aeropuerto El Tepual', 'puerto_varas' => 'Puerto Varas',
                'puerto_montt' => 'Puerto Montt', 'frutillar' => 'Frutillar', 'osorno' => 'Osorno',
            ]],
        ],
        'guia' => [
            'especialidades' => ['type' => 'textarea', 'label' => 'Especialidades', 'placeholder' => 'Ej: Historia local, naturaleza...'],
            'idiomas' => ['type' => 'checkbox_group', 'label' => 'Idiomas', 'options' => [
                'espanol' => 'Español', 'ingles' => 'Inglés', 'aleman' => 'Alemán',
            ]],
        ],
    ],
];
