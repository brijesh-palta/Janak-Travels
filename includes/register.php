<?php
require_once 'conn.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $mobile_number = $_POST['mobile_number'];

    $sql = "INSERT INTO users (username, email, password, mobile_number, token, status) VALUES ('$username', '$email', '$password', '$mobile_number', 0, 0)";

    if ($conn->query($sql) === TRUE) {
        $response['success'] = true;
        $response['message'] = "Registration successful!";
    } else {
        $response['success'] = false;
        $response['message'] = "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

$conn->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
