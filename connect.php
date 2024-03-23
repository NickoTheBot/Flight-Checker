<?php
// Typically, you'd use a library to load .env files in PHP. For simplicity, we'll define the variables here.
/* $host = '127.0.0.1';
$user = 'root';
$password = 'AceOfSpades12';
$dbname = 'characters'; */
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} catch (Exception $e) {
    die('Could not open .env file.');
}

$host = $_ENV['HOST'];
$user = $_ENV['USER'];
$password = $_ENV['PASSWORD'];
$dbname = $_ENV['DB'];
// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else {
 /*echo "Connected successfully"; */
}

/*$conn->close();>*/
?>
