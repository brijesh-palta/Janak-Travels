<<<<<<< HEAD
<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "travels";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
=======
<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "travels";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
>>>>>>> 9653623 (Initial commit with Dockerfile, compose files and Jenkinsfile)
?>