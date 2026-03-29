<div class="container">
    <nav class="breadcrumb">
        <a href="<?= SITE_URL ?>">Inicio</a>
        <span class="sep">/</span>
        <span>Mapa</span>
    </nav>
</div>

<section class="section-sm">
    <div class="container">
        <h1>📍 Mapa de Puerto Octay</h1>
        <p class="text-light mb-2">Explora todos los negocios y atractivos turisticos en el mapa interactivo</p>
        <div id="mapFull" style="height: calc(100vh - 220px); min-height: 400px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md);"></div>
    </div>
</section>

<?php $extraScripts = '<script>
document.addEventListener("DOMContentLoaded", function() {
    var map = L.map("mapFull").setView([-40.9724, -72.8876], 13);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors"
    }).addTo(map);

    fetch("' . SITE_URL . '/api/negocios.json")
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (!data || !data.length) return;
            var bounds = L.latLngBounds();
            data.forEach(function(n) {
                if (!n.lat || !n.lng) return;
                var marker = L.marker([parseFloat(n.lat), parseFloat(n.lng)]).addTo(map);
                marker.bindPopup(
                    "<strong>" + (n.nombre || "") + "</strong>" +
                    (n.categoria_nombre ? "<br><small>" + n.categoria_nombre + "</small>" : "") +
                    (n.direccion ? "<br><small>" + n.direccion + "</small>" : "") +
                    "<br><a href=\"' . SITE_URL . '/negocio/" + n.slug + "\">Ver detalle</a>"
                );
                bounds.extend([parseFloat(n.lat), parseFloat(n.lng)]);
            });
            if (bounds.isValid()) {
                map.fitBounds(bounds, { padding: [30, 30] });
            }
        });
});
</script>'; ?>
