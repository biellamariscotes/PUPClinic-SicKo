<?php
$host = "localhost";
$username = "u177950032_sicko";
$password = "Sicko@31";
$database = "u177950032_sicko";

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
