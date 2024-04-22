<?php
// Start the session
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: landingpage.php #login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="icon/tayug_icon-removebg-preview.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    c
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap">
    <script src="https://unpkg.com/leaflet.gridlayer.googlemutant"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDLP2wf4eWpmXcu86_ZrX3hQfyBtecgCjA&libraries=places"></script>
    <title>Home Page</title>

    <style>
        h1 {
            font-family: 'YourFirstFont', 'YourSecondFont', serif;
            font-size: 2em;
        }

        #container {
            display: flex;
        }

        #map {
            flex: 1;
            height: 500px;
        }

        #locate-control {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            margin-right: 10px;
        }

        #locate-control button,
        #locate-control #logoutButton {
            font-family: 'Helvetica', 'Arial', sans-serif;
            width: 100%;
            padding: 10px;
            font-size: 14px;
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            color: #333333;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s;
            margin-bottom: 10px;
        }

        #locate-control button:hover,
        #locate-control #logoutButton:hover {
            background-color: #f5f5f5;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        #locate-control #logoutButton {
            margin-top: auto;
            /* Push the logout button to the bottom */
        }

        #locate-control .searchCont {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }

        #locate-control .searchCont input {
            padding: 10px;
            font-size: 16px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #locate-control .rearch-result {
            border-top: 1px solid #ccc;
        }

        #locate-control .rearch-result span {
            height: 100px;
            display: block;
        }

        .weather {
            font-family: 'Helvetica', 'Arial', sans-serif;
            display: flex;
            flex-direction: column;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .wedTxt {
            font-size: 22px;
        }

        .wedCont {
            border: 1px solid #cccccc;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 10px;
        }

        .wedCont:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .blue-marker,
        .red-marker,
        .yellow-marker {
            border: 2px solid #000;
            border-radius: 50%;
            display: block;
        }

        .blue-marker {
            background-color: blue;
            height: 12px;
            width: 12px;
        }

        .red-marker {
            background-color: red;
            height: 12px;
            width: 12px;
        }

        .yellow-marker {
            background-color: yellow;
            height: 20px;
            width: 20px;
        }

        #searchResult {
            height: 50px;
            cursor: pointer;
        }

        .search-result {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .leaflet-routing-container .leaflet-routing-alternatives-container {
            background-color: rgba(255, 255, 255, 0.8);
            color: black;
            border: 1px solid black;
            border-radius: 5px;
        }

        .leaflet-routing-container .leaflet-routing-alt {
            color: black;
        }

        .leaflet-routing-container .leaflet-routing-icon {
            filter: invert(100%);
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 1.5em;
                /* Adjust the font size for smaller screens */
            }

            #map {
                height: 500px;
            }

            #locate-control {
                align-items: stretch;
            }

            #locate-control button {
                margin-bottom: 5px;
            }
        }
    </style>
</head>

<body>
    <div>
        <h1 style="display: inline-block;"> Discovering Tayug & Nearby Attractions </h1>
    </div>

    <div id="container">
        <div id="map"></div>
        <div id="locate-control" class="leaflet-bar leaflet-control leaflet-control-custom">
            <button id="locateButton" onclick="locateUser()">Get Current Location</button>
            <button id="getDirectionsButton" onclick="getDirections()">Get Directions</button>
            <button id="deleteMarkerButton" onclick="deleteMarkers()">Clear Markers</button>
            <button id="openGoogleMapsButton" onclick="openGoogleMaps()" disabled>Open in Google Maps</button>
            <button id="deleteMarkerButton" onclick="listSpot()">List of Spots</button>
            <script>
                function listSpot() {
                    const delayTime = 1000;
                    setTimeout(function() {
                        window.location.href = 'spotList.php';
                    }, delayTime);
                }
            </script>
            <div class="wedCont">
                <div class="weather">
                    <div id="weather-container">
                        <p class="wedTxt">Weather Information</p>
                        <p id="location"></p>
                        <p id="temperature"></p>
                        <p id="description" style="text-transform: capitalize;"></p>
                    </div>
                </div>
            </div>
            <form method="post">
                <div class="searchCont">
                    <input type="text" name="" id="live_search" autocomplete="off" placeholder="Search ...">
                    <div id="searchResult" class="rearch-result">
                        <span style="height: 200px;"></span>
                    </div>
                </div>
            </form>
            <button id="logoutButton" onclick="logoutUser()">Logout</button>
            <script>
                function logoutUser() {
                    setTimeout(function() {
                        window.location.href = 'logout.php';
                    }, 2000);
                }
            </script>
        </div>
    </div>

    <script>
        const apiKey = "fdf299377c73ef0a57ddfbbb3e5e845c";
        const weatherContainer = document.getElementById('weather-container');
        const locationElement = document.getElementById('location');
        const temperatureElement = document.getElementById('temperature');
        const descriptionElement = document.getElementById('description');

        navigator.geolocation.getCurrentPosition(position => {
            const {
                latitude,
                longitude
            } = position.coords;
            fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&appid=${apiKey}&units=metric`)
                .then(response => response.json())
                .then(data => {
                    const location = data.name + ', ' + data.sys.country;
                    const temperature = data.main.temp + '°C';
                    const description = data.weather[0].description;

                    locationElement.textContent = location;
                    temperatureElement.textContent = temperature;
                    descriptionElement.textContent = description;
                })
                .catch(error => {
                    console.error('Error fetching weather data:', error);
                    weatherContainer.innerHTML = '<p>Unable to fetch weather data</p>';
                });
        }, error => {
            console.error('Error getting user location:', error);
            weatherContainer.innerHTML = '<p>Unable to get user location</p>';
        });
    </script>
    <script>
        var mapContainer = document.getElementById('map');
        var map = L.map('map').setView([16.0279, 120.7441], 16);

        L.gridLayer.googleMutant({
            type: 'hybrid'
        }).addTo(map);

        function updateMapSize() {
            var mapContainer = document.getElementById('map');
            mapContainer.style.height = window.innerHeight + 'px';
            map.invalidateSize();
        }

        window.addEventListener('resize', updateMapSize);
        updateMapSize();

        var locateControl = L.control.locate({
            position: 'topleft',
            drawCircle: false,
            drawMarker: false,
            flyTo: true,
            keepCurrentZoomLevel: true,
            setView: 'untilPan',
            cacheLocation: true
        }).addTo(map);

        map.on('locationerror', function() {
            document.getElementById('locateButton') = false;
        });
        var destinationCoordinates;
        var blueMarker;
        var redMarker;
        var routingControl;
        var openGoogleMapsButton = document.getElementById('openGoogleMapsButton');


        function locateUser() {
            if (locateControl._active) {
                locateControl.stop();
            } else {
                locateControl.start();
            }
            map.once('locationfound', function(e) {
                destinationCoordinates = [e.latlng.lat, e.latlng.lng];

                if (redMarker) {
                    map.removeLayer(redMarker);
                }

                redMarker = L.marker(e.latlng, {
                    icon: L.divIcon({
                        className: 'red-marker'
                    })
                }).addTo(map);
                openGoogleMapsButton.disabled = false;
            });
        }

        map.on('click', function(e) {
            if (blueMarker) {
                map.removeLayer(blueMarker);
            }

            blueMarker = L.marker(e.latlng, {
                icon: L.divIcon({
                    className: 'blue-marker'
                })
            }).addTo(map);
            destinationCoordinates = [e.latlng.lat, e.latlng.lng];

            if (routingControl) {
                map.removeControl(routingControl);
            }

            openGoogleMapsButton.disabled = true;
        });
        map.on('popupopen', function(e) {
            openGoogleMapsButton.disabled = false;

            destinationCoordinates = e.popup._latlng;
        });

        function getDirections() {
            if (destinationCoordinates && redMarker) {
                routingControl = L.Routing.control({
                    waypoints: [
                        L.latLng(redMarker.getLatLng()),
                        L.latLng(destinationCoordinates)
                    ],
                    routeWhileDragging: true,
                    lineOptions: {
                        styles: [{
                            color: 'purple',
                            opacity: 1,
                            weight: 5,
                        }]
                    },
                    serviceUrl: 'https://router.project-osrm.org/route/v1'
                }).addTo(map);

                map.setView(redMarker.getLatLng(), 10);
            } else {
                alert("Please get your current location or select a destination first.");
            }
        }

        function openGoogleMaps() {
            if (!destinationCoordinates) {
                console.error('Destination coordinates not set.');
                return;
            }

            var googleMapsUrl = 'https://www.google.com/maps/place/' + destinationCoordinates.lat + ',' + destinationCoordinates.lng;

            window.open(googleMapsUrl, '_blank');
        }
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
                    yellowMarker.id = marker.id;


                    yellowMarker.on('click', function() {
                        console.log('Clicked! Marker ID:', yellowMarker.id);

                        var container = document.createElement('div');
                        container.style.display = 'flex';

                        var imageElement = document.createElement('img');
                        imageElement.src = marker.image;
                        imageElement.style.width = '200px';
                        imageElement.style.height = '150px';

                        imageElement.style.cursor = 'pointer';
                        imageElement.addEventListener('click', function() {
                            console.log('Clicked! Marker ID:', yellowMarker.id);
                            window.location.href = 'spotListView.php?id=' + yellowMarker.id;
                        });

                        container.appendChild(imageElement);

                        var textContainer = document.createElement('div');
                        textContainer.style.marginLeft = '30px';

                        var textElement = document.createElement('p');
                        textElement.textContent = marker.name;
                        textElement.style.marginTop = '10px';
                        textElement.style.fontWeight = 'bold';
                        textElement.style.fontSize = '13px';
                        textContainer.appendChild(textElement);

                        var descElement = document.createElement('p');
                        descElement.textContent = marker.desc;
                        descElement.style.fontSize = '11px';
                        textContainer.appendChild(descElement);

                        if (marker.avg_rating !== null) {
                            var ratingElement = document.createElement('p');
                            var overallRatingPercentage = Math.round(marker.avg_rating * 20);
                            ratingElement.textContent = 'Overall Rating: ' + overallRatingPercentage + '%';
                            ratingElement.style.fontSize = '12px';
                            textContainer.appendChild(ratingElement);
                        } else {
                            var noRatingElement = document.createElement('p');
                            noRatingElement.textContent = 'No ratings available';
                            noRatingElement.style.fontSize = '12px';
                            textContainer.appendChild(noRatingElement);
                        }

                        container.appendChild(textContainer);

                        yellowMarker.bindPopup(container).openPopup();
                    });

                });
            }
        };
        xhr.open('GET', 'getMarkers.php', true);
        xhr.send();

        function deleteMarkers() {
            if (blueMarker) {
                map.removeLayer(blueMarker);
            }

            if (redMarker) {
                map.removeLayer(redMarker);
            }

            if (routingControl) {
                map.removeControl(routingControl);
            }

            destinationCoordinates = null;
        }
        $(document).ready(function() {
            function handleResultClick(latitude, longitude) {
                map.setView([latitude, longitude], 16);
            }

            var searchResultContainer = $("#searchResult");

            $("#live_search").click(function() {
                fetchAndDisplayResults();
            });
            $("#live_search").keyup(function() {
                fetchAndDisplayResults();
            });

            function fetchAndDisplayResults() {
                var input = $("#live_search").val();

                if (input !== "") {
                    $.ajax({
                        url: "liveSearch.php",
                        method: "POST",
                        data: {
                            input: input
                        },
                        success: function(data) {
                            searchResultContainer.html(data).show();

                            $(".search-result").click(function() {
                                var latitude = $(this).data("latitude");
                                var longitude = $(this).data("longitude");
                                handleResultClick(latitude, longitude);
                            });
                        }
                    });
                } else {
                    searchResultContainer.html("").hide();
                }
            }
        });
    </script>
</body>

</html>