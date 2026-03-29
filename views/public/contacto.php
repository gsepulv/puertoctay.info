<?php /** @var array $formData */ $formData = $_SESSION['form_data'] ?? []; ?>

<nav class="breadcrumb">
    <a href="/">Inicio</a>
    <span>/</span>
    <span>Contacto</span>
</nav>

<div class="container">
    <div class="section">
        <h1 style="margin-bottom:2rem;">Contacto</h1>

        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <div style="display:grid;grid-template-columns:1fr 380px;gap:2rem;align-items:start;">
            <!-- Contact Form -->
            <div class="card" style="padding:2rem;">
                <form action="/contacto" method="POST">
                    <?= csrf_field() ?>
                    <!-- Honeypot -->
                    <div style="position:absolute;left:-9999px;"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>

                    <div class="form-group">
                        <label for="nombre">Nombre *</label>
                        <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($formData['nombre'] ?? '') ?>" required class="form-control" placeholder="Tu nombre">
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($formData['email'] ?? '') ?>" required class="form-control" placeholder="tu@email.com">
                    </div>

                    <div class="form-group">
                        <label for="asunto">Asunto *</label>
                        <input type="text" id="asunto" name="asunto" value="<?= htmlspecialchars($formData['asunto'] ?? '') ?>" required class="form-control" placeholder="Asunto del mensaje">
                    </div>

                    <div class="form-group">
                        <label for="mensaje">Mensaje *</label>
                        <textarea id="mensaje" name="mensaje" rows="6" required class="form-control" placeholder="Escribe tu mensaje aquí..."><?= htmlspecialchars($formData['mensaje'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn">Enviar mensaje</button>
                </form>
            </div>

            <!-- Info Sidebar -->
            <div class="card" style="padding:2rem;">
                <h3 style="margin-bottom:1.5rem;">Información</h3>

                <div style="display:flex;gap:.75rem;margin-bottom:1.25rem;">
                    <span>✉</span>
                    <div>
                        <strong style="display:block;margin-bottom:.25rem;">Email</strong>
                        <a href="mailto:contacto@visitapuertoctay.cl">contacto@visitapuertoctay.cl</a>
                    </div>
                </div>

                <div style="display:flex;gap:.75rem;margin-bottom:1.25rem;">
                    <span>📍</span>
                    <div>
                        <strong style="display:block;margin-bottom:.25rem;">Ubicación</strong>
                        <span>Puerto Octay, Región de Los Lagos</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php unset($_SESSION['form_data']); ?>
