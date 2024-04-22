<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="icon/tayug_icon-removebg-preview.png">
    <title>Admin Add Spots</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDLP2wf4eWpmXcu86_ZrX3hQfyBtecgCjA&libraries=places"></script>
    <style>
        #map {
            height: 700px;
        }

        .yellow-marker {
            background-color: yellow;
            border: 2px solid #000;
            border-radius: 50%;
            display: block;
        }
    </style>
</head>

<body>
    <div id="map"> </div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <script src="https://unpkg.com/leaflet.gridlayer.googlemutant"></script>
    <script>
        var map = L.map('map').setView([16.0279, 120.7441], 16);

        L.gridLayer.googleMutant({
            type: 'hybrid'
        }).addTo(map);

        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'getMarkers.php', true);

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var markers = JSON.parse(xhr.responseText);

                markers.forEach(function(marker) {
                    var yellowMarker = L.marker([marker.lat, marker.lng], {
                        icon: L.divIcon({
                            className: 'yellow-marker'
                        })
                    }).addTo(map);
                    yellowMarker.bindPopup(marker.name);

                    yellowMarker.on('click', function() {

                        if (marker.image) {
                            var container = document.createElement('div');

                            var imageElement = document.createElement('img');
                            imageElement.src = marker.image;
                            imageElement.style.width = '200px';
                            imageElement.style.height = '150px';
                            container.appendChild(imageElement);

                            var textElement = document.createElement('p');
                            textElement.textContent = marker.name;
                            textElement.style.marginTop = '10px';
                            textElement.style.textAlign = 'center';
                            textElement.style.fontWeight = 'bold';
                            container.appendChild(textElement);

                            yellowMarker.bindPopup(container).openPopup();
                        }
                    });

                });

            }
        };
        xhr.open('GET', 'getMarkers.php', true);
        xhr.send();
    </script>
</body>

</html>