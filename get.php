<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include the database connection script
require 'connect.php';

// Set the content type to application/json
header('Content-Type: application/json');

// Initialize an array to hold the response
$response = [];

// Check the connection
if ($conn->connect_error) {
    $response['error'] = true;
    $response['message'] = "Connection failed: " . $conn->connect_error;
    echo json_encode($response);
    exit();
}

// Replace 'your_table_name' with the actual name of your table
$query = "SELECT * FROM users";
$result = $conn->query($query);

// Check if there are results
if ($result) {
    $data = [];

    // Fetch all the rows in the result set
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // If there are rows, add them to the response
    if (count($data) > 0) {
        $response['error'] = false;
        $response['data'] = $data;
    } else {
        // No rows means no data
        $response['error'] = true;
        $response['message'] = "No data found";
    }
} else {
    // If the query failed, return the error
    $response['error'] = true;
    $response['message'] = "Error in query: " . $conn->error;
}

// Output the JSON response
echo json_encode($response);

// Close the connection

?>
