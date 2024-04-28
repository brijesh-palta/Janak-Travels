<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $location = $_POST['location'];
    $numPeople = $_POST['people'];

    // Collect people names
    $peopleNames = "";
    for ($i = 1; $i <= $numPeople; $i++) {
        if (isset($_POST['person' . $i])) {
            $peopleNames .= $_POST['person' . $i] . ", ";
        }
    }
    $peopleNames = rtrim($peopleNames, ", "); // Remove trailing comma

    // Insert data into the table
    $sql = "INSERT INTO chardhamyatra (name, email, contact, location, num_people, people_names) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssis", $name, $email, $contact, $location, $numPeople, $peopleNames);

    if ($stmt->execute()) {
        echo "Booking successfully recorded.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
