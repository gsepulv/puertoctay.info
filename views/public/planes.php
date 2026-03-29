<div class="section">
    <div style="text-align:center; margin-bottom:2rem;">
        <h1 class="section-title">Planes para tu Negocio</h1>
        <p style="color:#666; max-width:600px; margin:0 auto;">Elige el plan que mejor se adapte a las necesidades de tu negocio en Puerto Octay. Desde presencia básica hasta máxima visibilidad.</p>
    </div>

    <?php if (empty($planes)): ?>
        <div class="empty-state">
            <p>Los planes estarán disponibles próximamente.</p>
        </div>
    <?php else: ?>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:1.5rem; max-width:1100px; margin:0 auto;">
            <?php foreach ($planes as $i => $plan): ?>
                <?php
                $isPopular = $plan['prioridad'] == 2;
                $borderColor = $isPopular ? COLOR_ACCENT : '#e0e0e0';
                ?>
                <div style="background:#fff; border-radius:12px; border:2px solid <?= $borderColor ?>; box-shadow:0 4px 16px rgba(0,0,0,0.08); overflow:hidden; display:flex; flex-direction:column; <?= $isPopular ? 'transform:scale(1.03);' : '' ?>">
                    <?php if ($isPopular): ?>
                        <div style="background:<?= COLOR_ACCENT ?>; color:#fff; text-align:center; padding:0.4rem; font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em;">
                            Más Popular
                        </div>
                    <?php endif; ?>

                    <div style="padding:1.8rem 1.5rem; flex:1; display:flex; flex-direction:column;">
                        <h3 style="font-size:1.3rem; margin-bottom:0.3rem; color:<?= COLOR_PRIMARY ?>;"><?= htmlspecialchars($plan['nombre']) ?></h3>

                        <div style="margin:1rem 0;">
                            <?php if ((int)$plan['precio'] === 0): ?>
                                <span style="font-size:2.2rem; font-weight:700; color:<?= COLOR_PRIMARY ?>;">Gratis</span>
                            <?php else: ?>
                                <span style="font-size:1rem; color:#888;">$</span>
                                <span style="font-size:2.2rem; font-weight:700; color:<?= COLOR_PRIMARY ?>;"><?= number_format($plan['precio'], 0, ',', '.') ?></span>
                                <span style="font-size:0.9rem; color:#888;">/mes</span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($plan['descripcion'])): ?>
                            <p style="color:#666; font-size:0.9rem; margin-bottom:1rem; line-height:1.5;"><?= nl2br(htmlspecialchars($plan['descripcion'])) ?></p>
                        <?php endif; ?>

                        <ul style="list-style:none; padding:0; margin:0 0 1.5rem; flex:1;">
                            <li style="padding:0.4rem 0; font-size:0.9rem; border-bottom:1px solid #f0f0f0;">
                                <span style="color:<?= COLOR_PRIMARY ?>; margin-right:0.4rem;">&#10003;</span>
                                <?= (int)$plan['max_fotos'] ?> foto<?= (int)$plan['max_fotos'] !== 1 ? 's' : '' ?> del negocio
                            </li>
                            <?php if ($plan['badge']): ?>
                            <li style="padding:0.4rem 0; font-size:0.9rem; border-bottom:1px solid #f0f0f0;">
                                <span style="color:<?= COLOR_ACCENT ?>; margin-right:0.4rem;">&#9733;</span>
                                Sello de negocio destacado
                            </li>
                            <?php endif; ?>
                            <?php if ($plan['estadisticas']): ?>
                            <li style="padding:0.4rem 0; font-size:0.9rem; border-bottom:1px solid #f0f0f0;">
                                <span style="color:<?= COLOR_PRIMARY ?>; margin-right:0.4rem;">&#10003;</span>
                                Estadísticas de visitas
                            </li>
                            <?php endif; ?>
                            <?php if ($plan['noticia_mensual']): ?>
                            <li style="padding:0.4rem 0; font-size:0.9rem; border-bottom:1px solid #f0f0f0;">
                                <span style="color:<?= COLOR_PRIMARY ?>; margin-right:0.4rem;">&#10003;</span>
                                Noticia mensual incluida
                            </li>
                            <?php endif; ?>
                            <?php if ($plan['banner_portada']): ?>
                            <li style="padding:0.4rem 0; font-size:0.9rem; border-bottom:1px solid #f0f0f0;">
                                <span style="color:<?= COLOR_ACCENT ?>; margin-right:0.4rem;">&#9733;</span>
                                Banner en portada del sitio
                            </li>
                            <?php endif; ?>
                            <?php if ($plan['max_cupos']): ?>
                            <li style="padding:0.4rem 0; font-size:0.9rem; color:#888;">
                                Cupos limitados: <?= (int) $plan['max_cupos'] ?>
                            </li>
                            <?php endif; ?>
                        </ul>

                        <a href="<?= SITE_URL ?>/contacto" class="btn <?= $isPopular ? 'btn-accent' : 'btn-primary' ?>" style="text-align:center; display:block;">
                            <?= (int)$plan['precio'] === 0 ? 'Registrar Gratis' : 'Contratar Plan' ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align:center; margin-top:2.5rem; color:#888; font-size:0.9rem;">
            <p>¿Tienes dudas sobre los planes? <a href="<?= SITE_URL ?>/contacto">Contáctanos</a> y te ayudaremos a elegir el mejor para tu negocio.</p>
            <p style="margin-top:0.5rem; font-size:0.8rem;">Todos los precios son en pesos chilenos (CLP) e incluyen IVA.</p>
        </div>
    <?php endif; ?>
</div>
