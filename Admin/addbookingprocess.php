<<<<<<< HEAD
<?php
require_once 'C:\xampp\htdocs\Travels_Website\includes\conn.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve booking data from POST request
    $destination = $_POST['destination'];
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
    $people = $_POST['people'];
    $adults = $_POST['adults'];
    $children = $_POST['children'];
    $name = $_POST['name'];
    $source = $_POST['source'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Prepare and execute SQL query to insert booking into database
    $stmt = $conn->prepare("INSERT INTO bookings (destination, date_from, date_to, people, adults, children, name, source, email, phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiisssss", $destination, $date_from, $date_to, $people, $adults, $children, $name, $source, $email, $phone);

    if ($stmt->execute()) {
        // Booking added successfully, redirect to addbooking.php
        header('Location: addbooking.php');
        exit(); // Terminate script execution after redirect
    } else {
        $response['success'] = false;
        $response['message'] = "Failed to add booking: " . $conn->error;
    }

    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method!";
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
=======
<?php
require_once 'C:\xampp\htdocs\Travels_Website\includes\conn.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve booking data from POST request
    $destination = $_POST['destination'];
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
    $people = $_POST['people'];
    $adults = $_POST['adults'];
    $children = $_POST['children'];
    $name = $_POST['name'];
    $source = $_POST['source'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Prepare and execute SQL query to insert booking into database
    $stmt = $conn->prepare("INSERT INTO bookings (destination, date_from, date_to, people, adults, children, name, source, email, phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiisssss", $destination, $date_from, $date_to, $people, $adults, $children, $name, $source, $email, $phone);

    if ($stmt->execute()) {
        // Booking added successfully, redirect to addbooking.php
        header('Location: addbooking.php');
        exit(); // Terminate script execution after redirect
    } else {
        $response['success'] = false;
        $response['message'] = "Failed to add booking: " . $conn->error;
    }

    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method!";
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
>>>>>>> 9653623 (Initial commit with Dockerfile, compose files and Jenkinsfile)
