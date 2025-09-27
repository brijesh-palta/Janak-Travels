<<<<<<< HEAD
<?php
session_start();
require_once 'conn.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE (username = '$username' OR email = '$username')";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['status'] == 0) {
            // Account verification pending
            $response['success'] = false;
            $response['message'] = "Verification pending. Please verify your account to login.";
        } elseif (password_verify($password, $row['password'])) {
            // Password is correct
            // Set session variables
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];

            // Set cookies for username and email
            setcookie('username', $row['username'], time() + (86400 * 30), "/"); 
            setcookie('email', $row['email'], time() + (86400 * 30), "/");

            $response['success'] = true;
            $response['message'] = "Login successful!";
            $response['redirect'] = "userindex.php"; // Redirect to userindex.php
        } else {
            // Invalid password
            $response['success'] = false;
            $response['message'] = "Invalid username/email or password.";
        }
    } else {
        // User not found
        $response['success'] = false;
        $response['message'] = "User not found.";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
=======
<?php
session_start();
require_once 'conn.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE (username = '$username' OR email = '$username')";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['status'] == 0) {
            // Account verification pending
            $response['success'] = false;
            $response['message'] = "Verification pending. Please verify your account to login.";
        } elseif (password_verify($password, $row['password'])) {
            // Password is correct
            // Set session variables
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];

            // Set cookies for username and email
            setcookie('username', $row['username'], time() + (86400 * 30), "/"); 
            setcookie('email', $row['email'], time() + (86400 * 30), "/");

            $response['success'] = true;
            $response['message'] = "Login successful!";
            $response['redirect'] = "userindex.php"; // Redirect to userindex.php
        } else {
            // Invalid password
            $response['success'] = false;
            $response['message'] = "Invalid username/email or password.";
        }
    } else {
        // User not found
        $response['success'] = false;
        $response['message'] = "User not found.";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
>>>>>>> 9653623 (Initial commit with Dockerfile, compose files and Jenkinsfile)
