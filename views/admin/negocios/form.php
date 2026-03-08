<?php $esEdicion = !empty($negocio['id']); ?>

<?php if (!empty($errores)): ?>
    <div class="alert alert-danger">
        <ul style="margin:0; padding-left:1.2rem;">
            <?php foreach ($errores as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= SITE_URL ?>/admin/negocios/<?= $esEdicion ? $negocio['id'] . '/actualizar' : 'guardar' ?>"
      method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="form-card" style="margin-bottom:1.5rem;">
        <h3 style="margin-bottom:1rem;">Información básica</h3>

        <div class="form-row">
            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($negocio['nombre'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo *</label>
                <select id="tipo" name="tipo" required>
                    <option value="">Seleccionar...</option>
                    <?php foreach (['comercio' => 'Comercio', 'atractivo' => 'Atractivo turístico', 'servicio' => 'Servicio', 'gastronomia' => 'Gastronomía'] as $val => $label): ?>
                        <option value="<?= $val ?>" <?= ($negocio['tipo'] ?? '') === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="categoria_id">Categoría</label>
                <select id="categoria_id" name="categoria_id">
                    <option value="">Sin categoría</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($negocio['categoria_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>><?= $cat['emoji'] ?> <?= htmlspecialchars($cat['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="plan_id">Plan</label>
                <select id="plan_id" name="plan_id">
                    <?php foreach ($planes as $plan): ?>
                        <option value="<?= $plan['id'] ?>" <?= ($negocio['plan_id'] ?? 1) == $plan['id'] ? 'selected' : '' ?>><?= htmlspecialchars($plan['nombre']) ?> ($<?= number_format($plan['precio']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="descripcion_corta">Descripción corta</label>
            <input type="text" id="descripcion_corta" name="descripcion_corta" value="<?= htmlspecialchars($negocio['descripcion_corta'] ?? '') ?>" maxlength="300">
        </div>

        <div class="form-group">
            <label for="descripcion_larga">Descripción completa</label>
            <textarea id="descripcion_larga" name="descripcion_larga" rows="5"><?= htmlspecialchars($negocio['descripcion_larga'] ?? '') ?></textarea>
        </div>
    </div>

    <div class="form-card" style="margin-bottom:1.5rem;">
        <h3 style="margin-bottom:1rem;">Contacto</h3>

        <div class="form-row">
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($negocio['direccion'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="horario">Horario</label>
                <input type="text" id="horario" name="horario" value="<?= htmlspecialchars($negocio['horario'] ?? '') ?>" placeholder="Lun-Vie 9:00-18:00">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($negocio['telefono'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="whatsapp">WhatsApp</label>
                <input type="text" id="whatsapp" name="whatsapp" value="<?= htmlspecialchars($negocio['whatsapp'] ?? '') ?>" placeholder="+56912345678">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($negocio['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="sitio_web">Sitio web</label>
                <input type="url" id="sitio_web" name="sitio_web" value="<?= htmlspecialchars($negocio['sitio_web'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="red_social_1">Red social 1</label>
                <input type="url" id="red_social_1" name="red_social_1" value="<?= htmlspecialchars($negocio['red_social_1'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="red_social_2">Red social 2</label>
                <input type="url" id="red_social_2" name="red_social_2" value="<?= htmlspecialchars($negocio['red_social_2'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="precio_referencial">Precio referencial</label>
            <input type="text" id="precio_referencial" name="precio_referencial" value="<?= htmlspecialchars($negocio['precio_referencial'] ?? '') ?>" placeholder="$5.000 - $15.000">
        </div>
    </div>

    <div class="form-card" style="margin-bottom:1.5rem;">
        <h3 style="margin-bottom:1rem;">Ubicación</h3>

        <div class="form-row">
            <div class="form-group">
                <label for="lat">Latitud</label>
                <input type="text" id="lat" name="lat" value="<?= htmlspecialchars($negocio['lat'] ?? '') ?>" placeholder="-40.9724">
            </div>
            <div class="form-group">
                <label for="lng">Longitud</label>
                <input type="text" id="lng" name="lng" value="<?= htmlspecialchars($negocio['lng'] ?? '') ?>" placeholder="-72.8876">
            </div>
        </div>

        <div class="form-group">
            <label for="como_llegar">Cómo llegar</label>
            <textarea id="como_llegar" name="como_llegar" rows="3"><?= htmlspecialchars($negocio['como_llegar'] ?? '') ?></textarea>
        </div>
    </div>

    <div class="form-card" style="margin-bottom:1.5rem;">
        <h3 style="margin-bottom:1rem;">Imágenes</h3>

        <div class="form-row">
            <div class="form-group">
                <label for="foto_principal">Foto principal</label>
                <input type="file" id="foto_principal" name="foto_principal" accept="image/jpeg,image/png,image/webp">
                <?php if (!empty($negocio['foto_principal'])): ?>
                    <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($negocio['foto_principal']) ?>" alt="Foto actual" style="max-width:200px; margin-top:0.5rem; border-radius:6px;">
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="logo">Logo</label>
                <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/webp">
                <?php if (!empty($negocio['logo'])): ?>
                    <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($negocio['logo']) ?>" alt="Logo actual" style="max-width:120px; margin-top:0.5rem; border-radius:6px;">
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="form-card" style="margin-bottom:1.5rem;">
        <h3 style="margin-bottom:1rem;">Estado</h3>
        <div class="form-row">
            <div class="form-group">
                <label>
                    <input type="checkbox" name="activo" value="1" <?= ($negocio['activo'] ?? 1) ? 'checked' : '' ?>>
                    Activo (visible en el sitio)
                </label>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="verificado" value="1" <?= ($negocio['verificado'] ?? 0) ? 'checked' : '' ?>>
                    Verificado
                </label>
            </div>
        </div>
    </div>

    <div style="display:flex; gap:1rem; align-items:center;">
        <button type="submit" class="btn btn-primary"><?= $esEdicion ? 'Guardar cambios' : 'Crear negocio' ?></button>
        <a href="<?= SITE_URL ?>/admin/negocios" style="color:#888;">Cancelar</a>
    </div>
</form>
