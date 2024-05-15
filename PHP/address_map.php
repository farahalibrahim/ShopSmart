<?php
function getAddressMap($street_address, $city, $map_id)
{
    // URL encode the address
    $address = urlencode("$street_address, $city");

    // Construct the URL for the geocoding API
    $url = "https://api.opencagedata.com/geocode/v1/json?q=$address&key=7f51c3ddcc2c4f2993fca396db46cb06";

    // Send a request to the geocoding API and get the response
    $response = file_get_contents($url);

    // Parse the response as JSON
    $data = json_decode($response, true);

    // Check if the response contains any results
    if (isset($data['results'][0])) {
        // Get the latitude and longitude from the response
        $latitude = $data['results'][0]['geometry']['lat'];
        $longitude = $data['results'][0]['geometry']['lng'];
    } else {
        // Handle the error if the geocoding fails
        return "Could not geocode the address.";
    }

    // Return the HTML and JavaScript code for the map
    return '
        <div id="' . $map_id . '"></div>
        <script>
            // Map script
            // Initialize the map
            var map = L.map("' . $map_id . '").setView([' . $latitude . ', ' . $longitude . '], 13);
            // Add the OpenStreetMap tiles
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                maxZoom: 19,
            }).addTo(map);
            // Add a marker to the map
            L.marker([' . $latitude . ', ' . $longitude . ']).addTo(map)
                // Add a popup to the marker with a link to Google Maps
                .bindPopup("<a href=\'https://www.google.com/maps/search/?api=1&query=' . $latitude . ',' . $longitude . '\' target=\'_blank\'>Open in Google Maps</a>").openPopup();
        </script>
    ';
}
