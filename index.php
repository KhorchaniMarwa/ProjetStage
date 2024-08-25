<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan Your Trip</title>
    <link rel="stylesheet" href="welcome.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        
        #map {
            height: 400px;
            margin-top: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        #result {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            width: 300px;
        }
        #result h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        #result p, #result ul {
            margin: 5px 0;
            padding: 0;
        }
        #result ul {
            list-style-type: none;
            padding: 0;
        }
        #result li {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <nav>
        <ul id="navbar">
            <li><a href="#">Home</a></li>
        </ul>
    </nav>
    <main>
        <div id="info">
            <form action="" method="POST">
                <p>Depuis</p>
                <input type="text" name="start_station" placeholder="address / station" required>
                <p>À</p>
                <input type="text" name="end_station" placeholder="address / station" required>
                <p>Par</p>
                <select id="options" name="transport_mode">
                    <option value="Train">Train</option>
                    <option value="Metro">Métro</option>
                    <option value="Bus">Bus</option>
                </select>
                <button type="submit">Planifier mon voyage</button>
            </form>
        </div>

        <div id="result">
            <?php
           
            $stations = [
    //train            
    'Tunis Marine' => ['coords' => [36.8065, 10.1815], 'connections' => ['Lac' => 1.6, 'La goulette' => 13, 'Kheireddine' => 12, 'Marsa' => 20], 'train'],
    'Lac' => ['coords' => [36.834, 10.238], 'connections' => ['Tunis Marine' => 1.6, 'La goulette' => 15, 'Kheireddine' => 15, 'Marsa' => 20], 'train'],
    'La goulette' => ['coords' => [36.821, 10.299], 'connections' => ['Tunis Marine' => 13, 'Lac' => 15, 'Kheireddine' => 2.8, 'Marsa' => 12], 'train'],
    'Kheireddine' => ['coords' => [36.838, 10.306], 'connections' => ['Tunis Marine' => 12, 'Lac' => 15, 'La goulette' => 2.8, 'Marsa' => 15], 'train'],
    'Marsa' => ['coords' => [36.878, 10.324], 'connections' => ['Tunis Marine' => 20, 'Lac' => 20, 'La goulette' => 12, 'Kheireddine' => 15], 'train'],
    
    // métro
    'Station de Bus Barcelone' => ['coords' => [36.8100, 10.1900], 'connections' => [],'métro'], 
    'Station de rabattement Slimen Kahia' => ['coords' => [36.8150, 10.1950], 'connections' => [], 'métro'],
    'Station Bab Alioua' => ['coords' => [36.8120, 10.2000], 'connections' => [] , 'métro'], 
    'Station de rabattement Charguia' => ['coords' => [36.8200, 10.2100], 'connections' => [], 'métro'], 
    'Station de rabattement El Montzah' => ['coords' => [36.8250, 10.2150], 'connections' => [], 'métro'], 
    'Station Mornag' => ['coords' => [36.8300, 10.2200], 'connections' => [], 'métro'], 
    'Station de rabattement El Intilaka' => ['coords' => [36.8350, 10.2250], 'connections' => [],'métro'],
    'Station Bellevue' => ['coords' => [36.8400, 10.2300], 'connections' => [], 'métro'], 
    'Station Jardin Thameur' => ['coords' => [36.8450, 10.2350], 'connections' => [], 'métro'], 
    'Station de Bus Tunis Marine' => ['coords' => [36.8065, 10.1815], 'connections' => [], 'métro'],
    'Station de rabattement Ariana' => ['coords' => [36.8500, 10.2400], 'connections' => [], 'métro'], 
    'Station Carthage' => ['coords' => [36.8350, 10.2450], 'connections' => [], 'métro'], 
    'Station de rabattement Khaireddine' => ['coords' => [36.8400, 10.2500], 'connections' => [], 'métro'], 
    'Station Tebourba' => ['coords' => [36.8450, 10.2550], 'connections' => [], 'métro'], 
    'Station Belehouen' => ['coords' => [36.8500, 10.2600], 'connections' => [],'métro'],
    'Station de rabattement 10 Décembre' => ['coords' => [36.8550, 10.2650], 'connections' => [], 'métro'],
    'Le reste des lignes Bus' => ['coords' => [36.8600, 10.2700], 'connections' => [], 'métro'],
    'Station de Metro Tunis Marine' => ['coords' => [36.8065, 10.1815], 'connections' => [], 'métro'], 
    'Station TGM Tunis Marine' => ['coords' => [36.8065, 10.1815], 'connections' => [], 'métro'], 

    //bus
    
];


            function dijkstra($graph, $start, $end) {
                $distances = [];
                $previous = [];
                $queue = [];
                
                foreach ($graph as $vertex => $edges) {
                    $distances[$vertex] = INF;
                    $previous[$vertex] = null;
                    $queue[$vertex] = $distances[$vertex];
                }
                
                $distances[$start] = 0;
                $queue[$start] = 0;
                
                while (!empty($queue)) {
                    asort($queue);
                    $closest = key($queue);
                    unset($queue[$closest]);
                    
                    if ($closest === $end) {
                        break;
                    }
                    
                    foreach ($graph[$closest]['connections'] as $neighbor => $cost) {
                        $alt = $distances[$closest] + $cost;
                        if ($alt < $distances[$neighbor]) {
                            $distances[$neighbor] = $alt;
                            $previous[$neighbor] = $closest;
                            $queue[$neighbor] = $alt;
                        }
                    }
                }
                
                $path = [];
                $u = $end;
                while (isset($previous[$u])) {
                    array_unshift($path, $u);
                    $u = $previous[$u];
                }
                if (!empty($path)) {
                    array_unshift($path, $start);
                }
                
                return ['distance' => $distances[$end], 'path' => $path];
            }

            // Get the data from the form
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $start_station = $_POST['start_station'];
                $end_station = $_POST['end_station'];
                $transport_mode = $_POST['transport_mode']; 
                
                // Call Dijkstra's algorithm
                $result = dijkstra($stations, $start_station, $end_station);
                
                if ($result['distance'] < INF) {
                    echo "<h2>Shortest Path</h2>";
                    echo "<p>From: $start_station</p>";
                    echo "<p>To: $end_station</p>";
                    echo "<p>Mode: $transport_mode</p>";
                    echo "<p>Distance: " . $result['distance'] . " km</p>";
                    echo "<ul>";
                    foreach ($result['path'] as $index => $station) {
                        echo "<li>" . ($index + 1) . ". " . $station . "</li>";
                    }
                    echo "</ul>";
                    
                    
                    echo "<script>
                        var pathCoords = " . json_encode(array_map(function($station) use ($stations) {
                            return $stations[$station]['coords'];
                        }, $result['path'])) . ";
                    </script>";
                } else {
                    echo "<p>No path found between $start_station and $end_station.</p>";
                }
            }
            ?>
        </div>

        <div id="map"></div>
    </main>
    <footer></footer>

    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([36.8065, 10.1815], 13); // Centered on Tunis
        
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);
        

        if (typeof pathCoords !== 'undefined' && pathCoords.length > 0) {
           
            for (var i = 0; i < pathCoords.length; i++) {
                L.marker(pathCoords[i]).addTo(map);
            }
            
            // Draw the path on the map
            var polyline = L.polyline(pathCoords, {color: 'blue'}).addTo(map);
            
            // Zoom the map to the polyline
            map.fitBounds(polyline.getBounds());
        }
    </script>
</body>
</html>