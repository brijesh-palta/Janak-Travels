<?php
// Start the session
session_start();

// Check if the user is logged in
if(isset($_SESSION['user_id'])) {
    // Establish database connection
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

    // Fetch bookings for the logged-in user
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM bookings WHERE user_id = '$user_id'";
    $result = $conn->query($sql);

    // Display bookings in a table
    if ($result->num_rows > 0) {
        echo "<table class='booking-table'>";
        echo "<tr><th>Name</th><th>Mobile</th><th>Email</th><th>Type of Car</th><th>From</th><th>To</th><th>Date From</th><th>Date To</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["mobile"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["type_of_car"] . "</td>";
            echo "<td>" . $row["origin"] . "</td>";
            echo "<td>" . $row["destination"] . "</td>";
            echo "<td>" . $row["date_from"] . "</td>";
            echo "<td>" . $row["date_to"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No bookings found.";
    }

    // Close connection
    $conn->close();
} 

?>
