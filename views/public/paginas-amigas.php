<div class="container" style="padding-top: 1rem;">
    <nav class="breadcrumb">
        <a href="<?= SITE_URL ?>">Inicio</a>
        <span class="sep">/</span>
        <span>Páginas Amigas</span>
    </nav>
</div>

<section class="section">
    <div class="container" style="max-width: 900px;">
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <h1 style="margin-bottom: 0.5rem;">Páginas Amigas</h1>
            <p style="color: var(--text-light); font-size: 1.05rem; max-width: 700px; margin: 0 auto; line-height: 1.7;">
                Sitios web que recomendamos y que nos han inspirado con información valiosa sobre Puerto Octay y la Región de Los Lagos.
            </p>
        </div>

        <?php
        $secciones = [
            [
                'titulo' => 'Institucionales',
                'emoji' => '🏛',
                'enlaces' => [
                    ['nombre' => 'Ilustre Municipalidad de Puerto Octay', 'url' => 'https://munipuertoctay.cl', 'desc' => 'Sitio oficial del municipio de Puerto Octay.'],
                    ['nombre' => 'Chile es Tuyo (Sernatur)', 'url' => 'https://chiletourism.travel', 'desc' => 'Portal oficial de turismo de Chile.'],
                    ['nombre' => 'Registro de Museos de Chile', 'url' => 'https://www.registromuseoschile.cl', 'desc' => 'Catálogo nacional de museos y espacios culturales.'],
                ],
            ],
            [
                'titulo' => 'Turismo y Viajes',
                'emoji' => '🌄',
                'enlaces' => [
                    ['nombre' => 'Turismo Puerto Octay', 'url' => 'https://turismopuertoctay.cl', 'desc' => 'Guía turística dedicada a Puerto Octay.'],
                    ['nombre' => 'Ladera Sur', 'url' => 'https://laderasur.com', 'desc' => 'Medio sobre naturaleza, viajes y vida al aire libre en Chile.'],
                    ['nombre' => 'RecorreChile', 'url' => 'https://recorrechile.com', 'desc' => 'Rutas, destinos y experiencias de viaje por Chile.'],
                    ['nombre' => 'Apuntes y Viajes', 'url' => 'https://apuntesyviajes.com', 'desc' => 'Blog de viajes con guías detalladas del sur de Chile.'],
                    ['nombre' => 'Visit Chile', 'url' => 'https://www.visitchile.com', 'desc' => 'Portal de turismo con información de destinos chilenos.'],
                    ['nombre' => 'Welcome Chile', 'url' => 'https://www.welcomechile.com', 'desc' => 'Guía de alojamientos y turismo en Chile.'],
                ],
            ],
            [
                'titulo' => 'Cultura y Patrimonio',
                'emoji' => '📚',
                'enlaces' => [
                    ['nombre' => 'Wikipedia: Puerto Octay', 'url' => 'https://es.wikipedia.org/wiki/Puerto_Octay', 'desc' => 'Historia, geografía y datos generales de Puerto Octay.'],
                    ['nombre' => 'Consejo de Monumentos Nacionales', 'url' => 'https://www.monumentos.gob.cl', 'desc' => 'Patrimonio cultural y monumentos protegidos de Chile.'],
                ],
            ],
            [
                'titulo' => 'Nuestros Proyectos',
                'emoji' => '⛵',
                'enlaces' => [
                    ['nombre' => 'PurranQUE.INFO', 'url' => 'https://www.purranque.info', 'desc' => 'Portal de información y servicios de Purranque.'],
                    ['nombre' => 'Regalos Purranque', 'url' => 'https://regalospurranque.cl', 'desc' => 'Directorio de comercios y regalos en Purranque.'],
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
                <a href="<?= htmlspecialchars($enlace['url']) ?>" target="_blank" rel="noopener"
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
            <p style="color: var(--text-light); font-size: 0.95rem;">
                Si deseas que tu sitio web aparezca en esta sección, escríbenos a
                <a href="mailto:contacto@purranque.info" style="color: var(--primary); font-weight: 600;">contacto@purranque.info</a>
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
