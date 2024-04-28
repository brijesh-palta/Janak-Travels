<?php
session_start();

$error_message = ""; // Variable to store error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email and password are set
    if (isset($_POST['email']) && isset($_POST['password'])) {
        // Retrieve entered email and password
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validate email and password (you can add more validation)
        if ($email == "S.palta1975@yahoo.com" && $password == "12345678") {
            // Admin credentials are correct, redirect to admin panel after 1 second
            sleep(1); // Delay for 1 second
            header("Location: admindashboard.php");
            exit();
        } else {
            // Admin credentials are incorrect, set error message
            $error_message = "Invalid email or password. Please try again.";
        }
    } else {
        // Email or password not set, set error message
        $error_message = "Please enter both email and password.";
    }
}

// Redirect back to the admin_login.php page after setting error message
header("Location: adminlogin.php?error_message=" . urlencode($error_message));
exit();
?>
