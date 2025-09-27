<<<<<<< HEAD
<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "travels";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch booking data from the database
$sql = "SELECT * FROM bookings";
$result = $conn->query($sql);

$response = array();

if ($result->num_rows > 0) {
    $response['success'] = true;
    $response['data'] = array();

    while ($row = $result->fetch_assoc()) {
        // Add booking data to the response array
        $response['data'][] = $row;
    }
} else {
    $response['success'] = false;
    $response['message'] = "No bookings found.";
}

// Return JSON response
echo json_encode($response);

$conn->close();
?>
=======
<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "travels";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch booking data from the database
$sql = "SELECT * FROM bookings";
$result = $conn->query($sql);

$response = array();

if ($result->num_rows > 0) {
    $response['success'] = true;
    $response['data'] = array();

    while ($row = $result->fetch_assoc()) {
        // Add booking data to the response array
        $response['data'][] = $row;
    }
} else {
    $response['success'] = false;
    $response['message'] = "No bookings found.";
}

// Return JSON response
echo json_encode($response);

$conn->close();
?>
>>>>>>> 9653623 (Initial commit with Dockerfile, compose files and Jenkinsfile)
