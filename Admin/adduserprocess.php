<?php
require_once 'C:\xampp\htdocs\Travels_Website\includes\conn.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user data from POST request
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Remember to hash this password before storing it
    $mobile_number = $_POST['mobile_number'];
    $token = $_POST['token']; // You might want to generate a unique token here
    $status = $_POST['status']; // Assuming 'status' will be either active or inactive
    
    // Prepare and execute SQL query to insert user into database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, mobile_number, token, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $email, $password, $mobile_number, $token, $status);
    
    if ($stmt->execute()) {
        // Redirect to admin dashboard after successful user addition
        header('Location: adduser.php');
        exit(); // Terminate script execution after redirect
    } else {
        $response['success'] = false;
        $response['message'] = "Failed to add user: " . $conn->error;
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
