<?php /** @var bool $usarLeaflet */ ?>

<nav class="breadcrumb">
    <a href="/">Inicio</a>
    <span>/</span>
    <span>Mapa</span>
</nav>

<div class="container">
    <div class="section" style="padding-top:1rem;">
        <h1 style="margin-bottom:1.5rem;">Mapa de Puerto Octay</h1>

        <div id="mapFull" style="height:calc(100vh - 200px);min-height:400px;border-radius:var(--radius-lg);"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('mapFull').setView([-40.9724, -72.8876], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    fetch(SITE_URL + '/api/negocios.json')
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (!data || !data.length) return;
            var bounds = L.latLngBounds();
            data.forEach(function(n) {
                if (!n.latitud || !n.longitud) return;
                var lat = parseFloat(n.latitud);
                var lng = parseFloat(n.longitud);
                if (isNaN(lat) || isNaN(lng)) return;
                var marker = L.marker([lat, lng]).addTo(map);
                marker.bindPopup(
                    '<strong>' + (n.nombre || '') + '</strong>' +
                    (n.direccion ? '<br>' + n.direccion : '') +
                    '<br><a href="/negocio/' + (n.slug || '') + '">Ver detalle</a>'
                );
                bounds.extend([lat, lng]);
            });
            if (bounds.isValid()) {
                map.fitBounds(bounds, { padding: [40, 40] });
            }
        })
        .catch(function(err) {
            console.error('Error cargando negocios:', err);
        });
});
</script>
