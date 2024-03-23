<?php
ob_start();
session_start();
// ... rest of your code

/*if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page
    header('Location: ./index.php');
    exit;
} */

require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} catch (Exception $e) {
    die('Could not open .env file.');
}
$apiKey = $_ENV['API_KEY'];


$baseUrl = 'http://api.aviationstack.com/v1/flights';


$queryString = http_build_query([
  'access_key' => $apiKey,
  
]);


$ch = curl_init(sprintf('%s?%s', $baseUrl, $queryString));


curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


$response = curl_exec($ch);
$error = curl_error($ch);


curl_close($ch);


if ($response === false) {
    echo "cURL Error: " . $error;
    exit;
}


$apiResult = json_decode($response, true);


if (isset($apiResult['error'])) {
    echo "API Error: " . $apiResult['error']['message'];
    exit;
}


$flightsInfoHtml = "";



if (isset($apiResult['data']) && is_array($apiResult['data'])) {
    foreach ($apiResult['data'] as $flight) {
        
        if (isset($flight['flight']) && isset($flight['flight']['number'])) {
           
            $flightsInfoHtml .= "<li class='flight-info'>
                <div class='flight-number'>Flight Number: " . htmlspecialchars($flight['flight']['number'] ?? 'Not available') . "</div>
                <div class='flight-status'>Status: " . htmlspecialchars($flight['flight_status'] ?? 'Not available') . "</div>
                <div class='flight-departure'>Departure from: " . htmlspecialchars($flight['departure']['airport'] ?? 'Not available') . "</div>
                <div class='flight-arrival'>Arrival to: " . htmlspecialchars($flight['arrival']['airport'] ?? 'Not available') . "</div>
                <div class='flight-time'>Scheduled Departure: " . htmlspecialchars($flight['departure']['scheduled'] ?? 'Not available') . "</div>
            </li>";
        }
    }
} else {
    $flightsInfoHtml = "<li>No flight data available.</li>";
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Status</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Flight Status</h1>

        <!-- div class="search-container">
    <form action="" method="get">
        <input type="text" placeholder="Search by Airport..." name="search" required>
        <button type="submit">Search</button>
    </form>
</div-->

        <ul class="flight-list">
            <?php echo $flightsInfoHtml; ?>
        </ul>
    </div>
</body>
</html>
