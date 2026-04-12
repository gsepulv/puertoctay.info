<?php
$d = $_SESSION['form_data'] ?? [];
$errores = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_data'], $_SESSION['form_errors']);
?>

<section class="section-sm">
<div class="container" style="max-width: 720px;">

    <div style="text-align: center; margin-bottom: 2rem;">
        <span style="font-size: 3rem;">🏪</span>
        <h1 style="margin: 0.5rem 0;">Registra tu Negocio en Puerto Octay</h1>
        <p class="text-light">Completa el formulario y te contactaremos en máximo 48 horas.</p>
    </div>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
            <ul style="margin: 0; padding-left: 1.2rem;">
                <?php foreach ($errores as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= SITE_URL ?>/registrar-comercio" id="formSolicitud"
          style="background: var(--white); border-radius: var(--radius-lg); padding: 2rem; border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
        <?= csrf_field() ?>
        <div style="position: absolute; left: -9999px;">
            <input type="text" name="website" tabindex="-1" autocomplete="off">
        </div>

        <!-- SECCIÓN 1: TIPO DE NEGOCIO -->
        <h3 style="margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border);">
            🏷️ Tipo de Negocio
        </h3>

        <div id="tipos-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem; margin-bottom: 1rem;">
            <?php
            $tipos = [
                ['value' => 'alojamiento', 'icono' => '🏨', 'nombre' => 'Alojamiento'],
                ['value' => 'gastronomia', 'icono' => '🍽️', 'nombre' => 'Gastronomía'],
                ['value' => 'actividad',   'icono' => '🎯', 'nombre' => 'Actividad'],
                ['value' => 'arriendo',    'icono' => '🚤', 'nombre' => 'Arriendo'],
                ['value' => 'tour',        'icono' => '🗺️', 'nombre' => 'Tour'],
                ['value' => 'atractivo',   'icono' => '📍', 'nombre' => 'Atractivo'],
                ['value' => 'comercio',    'icono' => '🛒', 'nombre' => 'Comercio'],
                ['value' => 'servicio',    'icono' => '🔧', 'nombre' => 'Servicio'],
            ];
            foreach ($tipos as $t):
                $checked = ($d['tipo'] ?? '') === $t['value'] ? 'checked' : '';
            ?>
            <label class="tipo-card <?= $checked ? 'tipo-card--selected' : '' ?>" data-tipo="<?= $t['value'] ?>">
                <input type="radio" name="tipo" value="<?= $t['value'] ?>" <?= $checked ?> required style="display:none;">
                <span style="font-size: 1.8rem; display: block; margin-bottom: 0.25rem;"><?= $t['icono'] ?></span>
                <span style="font-size: 0.8rem; font-weight: 600;"><?= $t['nombre'] ?></span>
            </label>
            <?php endforeach; ?>
        </div>

        <div id="subtipos-container" style="display:<?= !empty($d['tipo']) ? 'block' : 'none' ?>; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label>Tipo específico <span style="color:#DC2626">*</span></label>
                <select name="subtipo" id="subtipo-select" required>
                    <option value="">Selecciona...</option>
                </select>
            </div>
        </div>

        <!-- SECCIÓN 2: INFORMACIÓN DEL NEGOCIO -->
        <h3 style="margin: 1.5rem 0 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">
            🏪 Tu Negocio
        </h3>

        <div class="form-group">
            <label>Nombre del negocio <span style="color:#DC2626">*</span></label>
            <input type="text" name="nombre_comercio" value="<?= htmlspecialchars($d['nombre_comercio'] ?? '') ?>"
                   minlength="3" maxlength="150" required placeholder="Ej: Cabañas Vista al Lago">
        </div>

        <div class="form-group">
            <label>Descripción breve <span style="color:#DC2626">*</span> <span id="desc-counter" style="color:var(--text-light); font-weight:normal; font-size:0.8rem;">0/300</span></label>
            <textarea name="descripcion" id="descripcion" minlength="20" maxlength="300" required rows="3"
                      placeholder="Describe tu negocio en pocas palabras..."><?= htmlspecialchars($d['descripcion'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Sector / Localidad <span style="color:#DC2626">*</span></label>
            <select name="sector_id" required>
                <option value="">Selecciona...</option>
                <?php foreach ($sectores as $sector): ?>
                    <option value="<?= $sector['id'] ?>" <?= ($d['sector_id'] ?? '') == $sector['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sector['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- SECCIÓN 3: TUS DATOS -->
        <h3 style="margin: 1.5rem 0 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">
            👤 Tus Datos
        </h3>

        <div class="form-group">
            <label>Nombre completo <span style="color:#DC2626">*</span></label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($d['nombre'] ?? '') ?>"
                   minlength="3" maxlength="150" required placeholder="Tu nombre completo">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Email <span style="color:#DC2626">*</span></label>
                <input type="email" name="email" value="<?= htmlspecialchars($d['email'] ?? '') ?>"
                       required placeholder="correo@ejemplo.com">
                <small style="color:var(--text-light);">Será tu usuario para acceder al panel</small>
            </div>
            <div class="form-group">
                <label>Teléfono / WhatsApp <span style="color:#DC2626">*</span></label>
                <input type="tel" name="telefono" value="<?= htmlspecialchars($d['telefono'] ?? '') ?>"
                       minlength="9" maxlength="20" required placeholder="+56 9 1234 5678">
            </div>
        </div>

        <!-- SECCIÓN 4: PLAN -->
        <h3 style="margin: 1.5rem 0 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">
            🆓 Tu Plan
        </h3>

        <div style="background: #F0FDF4; border: 2px solid #BBF7D0; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                <span style="font-size: 1.5rem;">🆓</span>
                <strong style="font-size: 1.1rem;">Plan Freemium</strong>
                <span style="background: #38A169; color: white; padding: 0.15rem 0.6rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">$0</span>
            </div>
            <p style="font-size: 0.9rem; color: #4A5568; margin: 0 0 0.5rem;">Incluido con tu registro:</p>
            <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.85rem; color: #2D3748;">
                <li style="padding: 0.15rem 0;">✓ Página de tu negocio en el directorio</li>
                <li style="padding: 0.15rem 0;">✓ 3 fotos de tu negocio</li>
                <li style="padding: 0.15rem 0;">✓ 1 red social</li>
                <li style="padding: 0.15rem 0;">✓ Botón de WhatsApp</li>
                <li style="padding: 0.15rem 0;">✓ Ubicación en mapa</li>
            </ul>
            <p style="font-size: 0.8rem; color: #718096; margin: 0.75rem 0 0; font-style: italic;">💡 Podrás mejorar tu plan después de la aprobación</p>
        </div>
        <input type="hidden" name="plan" value="freemium">

        <!-- SECCIÓN 5: TÉRMINOS Y POLÍTICAS -->
        <h3 style="margin: 1.5rem 0 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">
            📋 Términos y Políticas
        </h3>
        <p style="font-size: 0.85rem; color: var(--text-light); margin-bottom: 1rem;">Debes aceptar todas las políticas para continuar.</p>

        <?php
        $politicasData = [
            ['id' => 'terminos',   'titulo' => 'Términos y Condiciones',      'icono' => '📜'],
            ['id' => 'privacidad', 'titulo' => 'Política de Privacidad',      'icono' => '🔒'],
            ['id' => 'contenidos', 'titulo' => 'Política de Contenidos',      'icono' => '📝'],
            ['id' => 'cookies',    'titulo' => 'Política de Cookies',          'icono' => '🍪'],
            ['id' => 'derechos',   'titulo' => 'Ejercicio de Derechos ARCO',  'icono' => '⚖️'],
        ];
        foreach ($politicasData as $pol):
            $accepted = ($d['politica_' . $pol['id']] ?? '') === 'acepto';
        ?>
        <div style="border: 1px solid var(--border); border-radius: 8px; padding: 0.75rem 1rem; margin-bottom: 0.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                <span style="font-size: 0.9rem;"><?= $pol['icono'] ?> <?= $pol['titulo'] ?></span>
                <div style="display: flex; gap: 0.75rem;">
                    <label style="font-size: 0.8rem; cursor: pointer; display: flex; align-items: center; gap: 0.25rem;">
                        <input type="radio" name="politica_<?= $pol['id'] ?>" value="acepto" class="politica-radio" <?= $accepted ? 'checked' : '' ?> required>
                        Acepto
                    </label>
                    <label style="font-size: 0.8rem; cursor: pointer; display: flex; align-items: center; gap: 0.25rem;">
                        <input type="radio" name="politica_<?= $pol['id'] ?>" value="rechazo" class="politica-radio" <?= (!$accepted && !empty($d['politica_' . $pol['id']])) ? 'checked' : '' ?>>
                        Rechazo
                    </label>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <p style="font-size: 0.85rem; color: var(--text-light); margin-top: 0.75rem; text-align: center;">
            <span id="politicas-counter">0</span>/5 políticas aceptadas
        </p>

        <!-- SECCIÓN 6: ENVÍO -->
        <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border);">
            <div style="background: #EDF2F7; border-radius: 8px; padding: 1rem; margin-bottom: 1.25rem; display: flex; align-items: flex-start; gap: 0.75rem;">
                <span style="font-size: 1.2rem;">🔒</span>
                <p style="margin: 0; font-size: 0.85rem; color: #4A5568;">Tu información está segura y no será compartida. Te enviaremos un email con tus credenciales cuando tu solicitud sea aprobada.</p>
            </div>

            <button type="submit" id="btnEnviar" class="btn btn-primary" style="width: 100%; padding: 0.85rem; font-size: 1.05rem; font-weight: 600;" disabled>
                Enviar Solicitud
            </button>
        </div>
    </form>
</div>
</section>

<style>
.tipo-card {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    padding: 1rem 0.5rem; border: 2px solid var(--border); border-radius: 10px;
    cursor: pointer; transition: all 0.2s; text-align: center; background: var(--white);
}
.tipo-card:hover { border-color: var(--primary); background: #F7FAFC; }
.tipo-card--selected { border-color: var(--primary); background: #EBF8FF; box-shadow: 0 0 0 3px rgba(43,108,176,0.15); }
@media (max-width: 600px) {
    #tipos-grid { grid-template-columns: repeat(2, 1fr) !important; }
}
</style>

<script>
(function() {
    var subtiposPorTipo = {
        alojamiento: [{s:'hotel',n:'Hotel'},{s:'hostal',n:'Hostal'},{s:'cabana',n:'Cabaña'},{s:'camping',n:'Camping'},{s:'glamping',n:'Glamping'},{s:'lodge',n:'Lodge'},{s:'bnb',n:'B&B'},{s:'departamento',n:'Departamento'},{s:'casa-veraneo',n:'Casa de veraneo'},{s:'refugio',n:'Refugio'},{s:'apart-hotel',n:'Apart Hotel'},{s:'hospedaje-rural',n:'Hospedaje rural'},{s:'domos',n:'Domos'}],
        gastronomia: [{s:'restaurante',n:'Restaurante'},{s:'cafeteria',n:'Cafetería'},{s:'casa-de-te',n:'Casa de té'},{s:'panaderia',n:'Panadería'},{s:'cerveceria',n:'Cervecería artesanal'},{s:'food-truck',n:'Food truck'},{s:'pasteleria',n:'Pastelería'},{s:'heladeria',n:'Heladería'},{s:'picada',n:'Picada / Fonda'},{s:'cocina-autor',n:'Cocina de autor'},{s:'asador',n:'Asador / Parrilla'},{s:'chocolateria',n:'Chocolatería'}],
        actividad: [{s:'kayak',n:'Kayak'},{s:'pesca',n:'Pesca deportiva'},{s:'canopy',n:'Canopy'},{s:'trekking',n:'Trekking'},{s:'cabalgata',n:'Cabalgata'},{s:'rafting',n:'Rafting'},{s:'navegacion',n:'Navegación'},{s:'avistamiento-aves',n:'Avistamiento de aves'},{s:'buceo',n:'Buceo / Snorkel'},{s:'sup',n:'Stand Up Paddle'},{s:'ski',n:'Ski / Snowboard'},{s:'escalada',n:'Escalada'},{s:'mtb',n:'Mountain bike'},{s:'canyoning',n:'Canyoning'},{s:'tour-foto',n:'Tour fotográfico'},{s:'taller',n:'Taller artesanal'},{s:'termas',n:'Termas / Spa'},{s:'yoga',n:'Yoga / Retiro'}],
        arriendo: [{s:'botes-remo',n:'Botes a remo'},{s:'lanchas',n:'Lanchas a motor'},{s:'motos-agua',n:'Motos de agua'},{s:'kayaks',n:'Kayaks'},{s:'bicicletas',n:'Bicicletas'},{s:'equipos-pesca',n:'Equipos de pesca'},{s:'equipos-ski',n:'Equipos de ski'},{s:'vehiculos',n:'Vehículos'},{s:'equipos-camping',n:'Equipos camping'},{s:'drones',n:'Drones'}],
        tour: [{s:'city-tour',n:'City tour'},{s:'patrimonial',n:'Tour patrimonial'},{s:'gastronomico',n:'Tour gastronómico'},{s:'naturaleza',n:'Excursión naturaleza'},{s:'fotografico',n:'Tour fotográfico'},{s:'cruce-lagos',n:'Cruce de lagos'},{s:'nocturno',n:'Tour nocturno'},{s:'privado',n:'Tour privado'}],
        atractivo: [{s:'museo',n:'Museo'},{s:'iglesia',n:'Iglesia / Patrimonio'},{s:'cascada',n:'Cascada / Salto'},{s:'mirador',n:'Mirador'},{s:'parque',n:'Parque Nacional'},{s:'playa',n:'Playa'},{s:'reserva',n:'Reserva Natural'},{s:'volcan',n:'Volcán'},{s:'termas-naturales',n:'Termas naturales'},{s:'cementerio',n:'Cementerio histórico'}],
        comercio: [{s:'tienda-outdoor',n:'Tienda outdoor'},{s:'artesania',n:'Artesanía'},{s:'feria',n:'Feria artesanal'},{s:'dulceria',n:'Chocolatería / Dulcería'},{s:'queseria',n:'Quesería'},{s:'vivero',n:'Vivero'},{s:'minimarket',n:'Minimarket'},{s:'emporio',n:'Emporio gourmet'}],
        servicio: [{s:'transfer',n:'Transfer / Transporte'},{s:'guia',n:'Guía turístico'},{s:'fotografia',n:'Fotografía profesional'},{s:'eventos',n:'Eventos / Banquetería'},{s:'agencia',n:'Agencia de viajes'},{s:'salon-eventos',n:'Arriendo salón eventos'},{s:'mecanico-nautico',n:'Mecánico náutico'},{s:'veterinario',n:'Veterinario'},{s:'lavanderia',n:'Lavandería'},{s:'cajero',n:'Cajero / Banco'}]
    };

    var cards = document.querySelectorAll('.tipo-card');
    var subCont = document.getElementById('subtipos-container');
    var subSel = document.getElementById('subtipo-select');
    var savedSubtipo = <?= json_encode($d['subtipo'] ?? '') ?>;

    cards.forEach(function(card) {
        card.addEventListener('click', function() {
            cards.forEach(function(c) { c.classList.remove('tipo-card--selected'); });
            card.classList.add('tipo-card--selected');
            var tipo = card.getAttribute('data-tipo');
            var items = subtiposPorTipo[tipo] || [];
            subSel.innerHTML = '<option value="">Selecciona...</option>';
            items.forEach(function(item) {
                var opt = document.createElement('option');
                opt.value = item.s;
                opt.textContent = item.n;
                if (item.s === savedSubtipo) opt.selected = true;
                subSel.appendChild(opt);
            });
            subCont.style.display = 'block';
            checkForm();
        });
    });

    var savedTipo = <?= json_encode($d['tipo'] ?? '') ?>;
    if (savedTipo) {
        var activeCard = document.querySelector('.tipo-card[data-tipo="' + savedTipo + '"]');
        if (activeCard) activeCard.click();
    }

    var descEl = document.getElementById('descripcion');
    var counterEl = document.getElementById('desc-counter');
    if (descEl && counterEl) {
        function updateCounter() { counterEl.textContent = descEl.value.length + '/300'; }
        descEl.addEventListener('input', updateCounter);
        updateCounter();
    }

    var politicaRadios = document.querySelectorAll('.politica-radio');
    var politicasCounterEl = document.getElementById('politicas-counter');
    var btnEnviar = document.getElementById('btnEnviar');

    function checkForm() {
        var accepted = document.querySelectorAll('.politica-radio[value="acepto"]:checked').length;
        if (politicasCounterEl) politicasCounterEl.textContent = accepted;
        btnEnviar.disabled = accepted < 5;
    }
    politicaRadios.forEach(function(r) { r.addEventListener('change', checkForm); });
    checkForm();
})();
</script>
