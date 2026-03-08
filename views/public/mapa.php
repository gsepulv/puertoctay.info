<section class="section">
    <h2 class="section-title">Mapa de Puerto Octay</h2>
    <p class="mb-2">Todos los negocios y atractivos turísticos en un solo mapa.</p>

    <div id="mapa-completo" style="height:70vh; min-height:400px; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1);"></div>
</section>

<?php
$extraScripts = ($extraScripts ?? '') . '
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Centro: Puerto Octay
    var map = L.map("mapa-completo").setView([-40.9724, -72.8876], 13);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap"
    }).addTo(map);

    fetch("' . SITE_URL . '/api/negocios.json")
        .then(function(r) { return r.json(); })
        .then(function(negocios) {
            var bounds = [];
            negocios.forEach(function(n) {
                var lat = parseFloat(n.lat);
                var lng = parseFloat(n.lng);
                if (isNaN(lat) || isNaN(lng)) return;

                bounds.push([lat, lng]);
                var emoji = n.categoria_emoji || "📍";
                var popup = "<strong>" + n.nombre + "</strong>";
                if (n.categoria_nombre) popup += "<br><small>" + emoji + " " + n.categoria_nombre + "</small>";
                if (n.descripcion_corta) popup += "<br><small>" + n.descripcion_corta.substring(0, 80) + "</small>";
                popup += "<br><a href=\"' . SITE_URL . '/negocio/" + n.slug + "\">Ver ficha</a>";

                L.marker([lat, lng]).addTo(map).bindPopup(popup);
            });
            if (bounds.length > 0) {
                map.fitBounds(bounds, { padding: [30, 30] });
            }
        });
});
</script>';
?>
