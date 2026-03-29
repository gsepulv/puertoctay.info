<div class="container">
    <nav class="breadcrumb">
        <a href="<?= SITE_URL ?>">Inicio</a>
        <span class="sep">/</span>
        <span>Contacto</span>
    </nav>
</div>

<section class="section">
    <div class="container">
        <h1>Contacto</h1>

        <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="alert alert-success mb-2"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
        <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger mb-2"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
        <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <div class="grid-2" style="gap: 2rem; align-items: start;">
            <div>
                <div class="card" style="padding: 2rem;">
                    <h3 class="mb-2">Envianos un mensaje</h3>
                    <form action="<?= SITE_URL ?>/contacto" method="POST">
                        <?= csrf_field() ?>
                        <div style="display: none;">
                            <input type="text" name="website_url" value="" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="nombre">Nombre *</label>
                            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($_SESSION['form_data']['nombre'] ?? '') ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="asunto">Asunto *</label>
                            <input type="text" id="asunto" name="asunto" value="<?= htmlspecialchars($_SESSION['form_data']['asunto'] ?? '') ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="mensaje">Mensaje *</label>
                            <textarea id="mensaje" name="mensaje" rows="5" required><?= htmlspecialchars($_SESSION['form_data']['mensaje'] ?? '') ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Enviar mensaje</button>
                    </form>
                    <?php unset($_SESSION['form_data']); ?>
                </div>
            </div>

            <div>
                <div class="card" style="padding: 2rem;">
                    <h3 class="mb-2">Información</h3>

                    <div class="mb-1">
                        <p class="text-sm text-light">📍 Direccion</p>
                        <p>Puerto Octay, Region de Los Lagos, Chile</p>
                    </div>

                    <div class="mb-1">
                        <p class="text-sm text-light">✉ Email</p>
                        <p><a href="mailto:contacto@visitapuertoctay.cl">contacto@visitapuertoctay.cl</a></p>
                    </div>

                    <div class="mb-1">
                        <p class="text-sm text-light">🕐 Horario de atencion</p>
                        <p>Lunes a Viernes, 9:00 - 18:00</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
