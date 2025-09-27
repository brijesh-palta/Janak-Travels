<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "travels";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set charset to avoid encoding issues
mysqli_set_charset($conn, "utf8mb4");
?>
