<div class="container" style="padding-top: 1rem;">
    <nav class="breadcrumb">
        <a href="<?= SITE_URL ?>">Inicio</a>
        <span class="sep">/</span>
        <span>Páginas Amigas y Bibliografía</span>
    </nav>
</div>

<section class="section">
    <div class="container" style="max-width: 900px;">
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <h1 style="margin-bottom: 0.5rem;">Páginas Amigas y Bibliografía</h1>
            <p style="color: var(--text-light); font-size: 1.05rem; max-width: 700px; margin: 0 auto; line-height: 1.7;">
                Sitios web que recomendamos y que han sido fuente de información para el contenido de Visita Puerto Octay. Agradecemos a cada una de estas fuentes por su valioso aporte.
            </p>
        </div>

        <?php
        $secciones = [
            [
                'titulo' => 'Fuentes Institucionales',
                'emoji' => '🏛',
                'enlaces' => [
                    ['nombre' => 'Ilustre Municipalidad de Puerto Octay', 'url' => 'https://munipuertoctay.cl', 'desc' => 'Sitio oficial de la municipalidad. Fuente de información sobre historia, servicios públicos, DAEM, salud y programas municipales.'],
                    ['nombre' => 'Chile es Tuyo (Sernatur)', 'url' => 'https://chileestuyo.cl', 'desc' => 'Servicio Nacional de Turismo. Información oficial sobre destinos turísticos de Chile.'],
                    ['nombre' => 'Sernatur', 'url' => 'https://www.sernatur.cl', 'desc' => 'Servicio Nacional de Turismo de Chile.'],
                    ['nombre' => 'Registro de Museos de Chile', 'url' => 'https://www.registromuseoschile.cl', 'desc' => 'Información sobre el Museo El Colono de Puerto Octay.'],
                    ['nombre' => 'Consejo de Monumentos Nacionales', 'url' => 'https://www.monumentos.gob.cl', 'desc' => 'Declaratoria de Zona Típica de Puerto Octay (2010).'],
                ],
            ],
            [
                'titulo' => 'Fuentes de Turismo y Viajes',
                'emoji' => '🌄',
                'enlaces' => [
                    ['nombre' => 'Turismo Puerto Octay', 'url' => 'https://turismopuertoctay.cl', 'desc' => 'Guía turística especializada en Puerto Octay. Tours personalizados y experiencias.'],
                    ['nombre' => 'Ladera Sur', 'url' => 'https://laderasur.com', 'desc' => 'Medio de comunicación dedicado a naturaleza y vida al aire libre. Artículo: 7 paseos para conocer los atractivos de Puerto Octay.'],
                    ['nombre' => 'Visit Chile', 'url' => 'https://www.visitchile.com', 'desc' => 'Portal turístico de Chile. Información sobre Lagos Llanquihue y Todos los Santos.'],
                    ['nombre' => 'Welcome Chile', 'url' => 'https://www.welcomechile.com', 'desc' => 'Portal de turismo. Historia de Puerto Octay y leyendas de la zona.'],
                ],
            ],
            [
                'titulo' => 'Fuentes Enciclopédicas',
                'emoji' => '📚',
                'enlaces' => [
                    ['nombre' => 'Wikipedia', 'url' => 'https://es.wikipedia.org/wiki/Puerto_Octay', 'desc' => 'Artículo enciclopédico sobre la comuna de Puerto Octay: historia, geografía, demografía, patrimonio.'],
                    ['nombre' => 'EcuRed', 'url' => 'https://www.ecured.cu/Puerto_Octay', 'desc' => 'Enciclopedia colaborativa. Datos generales de la comuna.'],
                ],
            ],
            [
                'titulo' => 'Fuentes de Arquitectura y Patrimonio',
                'emoji' => '🏗',
                'enlaces' => [
                    ['nombre' => 'Revista ED', 'url' => 'https://www.ed.cl', 'desc' => 'Artículo: Puerto Octay, un tesoro oculto en la ribera del Lago Llanquihue. Arquitectura, diseño y decoración.'],
                ],
            ],
            [
                'titulo' => 'Nuestros Proyectos',
                'emoji' => '⛵',
                'enlaces' => [
                    ['nombre' => 'PurranQUE.INFO', 'url' => 'https://www.purranque.info', 'desc' => 'Plataforma de servicios digitales para el sur de Chile. Empresa desarrolladora de Visita Puerto Octay.'],
                    ['nombre' => 'Regalos Purranque', 'url' => 'https://regalospurranque.cl', 'desc' => 'Directorio comercial de Purranque. Proyecto hermano de Visita Puerto Octay.'],
                ],
            ],
        ];
        ?>

        <?php foreach ($secciones as $seccion): ?>
        <div style="margin-bottom: 2.5rem;">
            <h2 style="font-size: 1.3rem; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid var(--border);">
                <?= $seccion['emoji'] ?> <?= $seccion['titulo'] ?>
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem;">
                <?php foreach ($seccion['enlaces'] as $enlace): ?>
                <a href="<?= htmlspecialchars($enlace['url']) ?>" target="_blank" rel="noopener noreferrer"
                   class="amiga-card">
                    <div style="font-weight: 600; color: var(--primary-dark); margin-bottom: 0.3rem; font-size: 0.95rem;">
                        <?= htmlspecialchars($enlace['nombre']) ?>
                        <span style="font-size: 0.75rem; opacity: 0.5; margin-left: 0.3rem;">&#8599;</span>
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-light); line-height: 1.5;">
                        <?= htmlspecialchars($enlace['desc']) ?>
                    </div>
                    <div style="font-size: 0.75rem; color: var(--text-lighter); margin-top: 0.4rem;">
                        <?= htmlspecialchars(parse_url($enlace['url'], PHP_URL_HOST)) ?>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <div style="text-align: center; padding: 2rem; background: var(--bg); border-radius: var(--radius-lg); margin-top: 1rem;">
            <p style="color: var(--text-light); font-size: 0.9rem; line-height: 1.8;">
                Todo el contenido publicado en Visita Puerto Octay ha sido elaborado a partir de información disponible públicamente en las fuentes aquí citadas.
                Si eres autor o representante de alguna de estas fuentes y deseas que modifiquemos o retiremos algún contenido, escríbenos a
                <a href="mailto:contacto@purranque.info" style="color: var(--primary); font-weight: 600;">contacto@purranque.info</a>.
                <br>Si deseas que tu sitio web aparezca en esta sección, también contáctanos.
            </p>
        </div>
    </div>
</section>

<style>
.amiga-card {
    display: block; padding: 1.2rem; background: var(--white);
    border: 1px solid var(--border); border-radius: var(--radius-md);
    text-decoration: none; color: inherit; transition: all var(--transition);
}
.amiga-card:hover {
    box-shadow: var(--shadow-hover); transform: translateY(-2px);
    border-color: var(--primary);
}
</style>
