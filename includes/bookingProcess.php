<<<<<<< HEAD
<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "travels";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Function to sanitize input data
    function sanitize($data) {
        global $conn;
        return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($data))));
    }

    // Sanitize and retrieve form data
    $destination = sanitize($_POST["destination"]);
    $date_from = sanitize($_POST["date_from"]);
    $date_to = sanitize($_POST["date_to"]);
    $people = sanitize($_POST["people"]);
    $adults = sanitize($_POST["adults"]);
    $children = sanitize($_POST["children"]);
    $name = sanitize($_POST["name"]);
    $source = sanitize($_POST["source"]);
    $email = sanitize($_POST["email"]);
    $phone = sanitize($_POST["phone"]);

    // Insert data into database
    $sql = "INSERT INTO bookings (destination, date_from, date_to, people, adults, children, name, source, email, phone) VALUES ('$destination', '$date_from', '$date_to', '$people', '$adults', '$children', '$name', '$source', '$email', '$phone')";

    if ($conn->query($sql) === TRUE) {
        // Booking successful
        echo json_encode(array("success" => true, "message" => "Booking successful!"));
    } else {
        // Booking failed
        echo json_encode(array("success" => false, "message" => "Error: " . $conn->error));
    }

    $conn->close();
} else {
    // If the form is not submitted, return an error message
    echo json_encode(array("success" => false, "message" => "Form submission error."));
}
?>
=======
<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "travels";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Function to sanitize input data
    function sanitize($data) {
        global $conn;
        return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($data))));
    }

    // Sanitize and retrieve form data
    $destination = sanitize($_POST["destination"]);
    $date_from = sanitize($_POST["date_from"]);
    $date_to = sanitize($_POST["date_to"]);
    $people = sanitize($_POST["people"]);
    $adults = sanitize($_POST["adults"]);
    $children = sanitize($_POST["children"]);
    $name = sanitize($_POST["name"]);
    $source = sanitize($_POST["source"]);
    $email = sanitize($_POST["email"]);
    $phone = sanitize($_POST["phone"]);

    // Insert data into database
    $sql = "INSERT INTO bookings (destination, date_from, date_to, people, adults, children, name, source, email, phone) VALUES ('$destination', '$date_from', '$date_to', '$people', '$adults', '$children', '$name', '$source', '$email', '$phone')";

    if ($conn->query($sql) === TRUE) {
        // Booking successful
        echo json_encode(array("success" => true, "message" => "Booking successful!"));
    } else {
        // Booking failed
        echo json_encode(array("success" => false, "message" => "Error: " . $conn->error));
    }

    $conn->close();
} else {
    // If the form is not submitted, return an error message
    echo json_encode(array("success" => false, "message" => "Form submission error."));
}
?>
>>>>>>> 9653623 (Initial commit with Dockerfile, compose files and Jenkinsfile)
