<?php

include(__DIR__ . '/include.php');
require_once ('Class/Weenect.php');

$weenect = new Weenect();
$weenect->login($config['usernameWeenect'], $config['passwordWeenect']);

//dd($weenect->getTracker());

?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" />
<style>
    html {
        background-color: #2d2e33;
    }

    .green {
        color: #1cc88a;
    }

    .red {
        color: #e74c3c;
    }

    .icon {
        position: absolute;
        top: 5px;
        font-size: 25px;
    }
</style>
<body>
    <div id="map" style="height: 600px; max-width: 750px; margin: auto;"></div>
    <div class="icon">
        <i class="fas fa-check-circle green"></i>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"></script>

<script>
    function isMarkerInsidePolygon(marker, poly) {
        var polyPoints = poly.getLatLngs();
        var x = marker.getLatLng().lat, y = marker.getLatLng().lng;
        polyPoints = polyPoints[0];

        var inside = false;
        for (var i = 0, j = polyPoints.length - 1; i < polyPoints.length; j = i++) {
            var xi = polyPoints[i].lat, yi = polyPoints[i].lng;
            var xj = polyPoints[j].lat, yj = polyPoints[j].lng;

            var intersect = ((yi > y) !== (yj > y))
                && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
            if (intersect) inside = !inside;
        }

        return inside;
    };

    $(document).ready(function () {

        let Lat = "Latitude";
        let Lng = "Lng";

        let map = L.map('map').setView([Lat, Lng], 20);
/*
        var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(map);
*/
        var tiles = L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19
        });

        tiles.addTo(map);

        let markerPos = JSON.parse('<?= $weenect->getPos() ?>');
        var marker = L.marker([markerPos.lat, markerPos.lng]).addTo(map);

        marker.bindPopup('Yumi')


        var polygon = L.polygon([
            [lat, lng],
            [lat, lng],
            [lat, lng],
        ])


        setInterval(function () {
            markerPos = JSON.parse('<?= $weenect->getPos() ?>');
            marker.setLatLng([markerPos.lat, markerPos.lng]);
            if(isMarkerInsidePolygon(marker, polygon) === true) {
                $('.icon').html('<i class="fas fa-check-circle green"></i>');
            }else{
                $('.icon').html('<i class="fas fa-times-circle red"></i>');
            }
        },40000);

    });
</script>
