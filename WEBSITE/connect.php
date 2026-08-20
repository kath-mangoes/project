<?php
$host = 'localhost';
$user = 'u720889503_caps';
$password = 'Kaladin19';
$database = 'u720889503_barangay';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

?>
