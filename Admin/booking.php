<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Janak Travels Admin Panel - View Bookings</title>
<style>
/* Basic styling for the layout */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    overflow: hidden; /* Hide the scroll bar */
}

.container {
    display: flex;
    height: 100vh; /* Cover the entire viewport height */
}

.sidebar {
    width: 250px;
    background-color: #333;
    color: #fff;
    padding: 20px;
    position: relative;
    overflow: hidden;
    transition: width 0.3s ease; /* Transition for sidebar width */
    display: flex;
    flex-direction: column; /* Arrange children vertically */
}

.sidebar h1 {
    font-size: 24px;
    margin-bottom: 20px;
}

.sidebar h2 {
    font-size: 20px;
    margin-bottom: 10px;
    color: #fff;
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar ul li {
    margin-bottom: 10px;
}

.sidebar ul li a {
    text-decoration: none;
    color: #fff;
    font-size: 16px;
    display: block;
    padding: 10px 20px; /* Adjusted padding */
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.sidebar ul li a:hover {
    background-color: #555;
}

.sidebar .dropdown-content {
    display: none;
    background-color: #555;
    padding-left: 20px;
    position: absolute; /* Change position to absolute */
    width: 200px; /* Set a fixed width */
    margin-top: -10px; /* Adjust margin */
    border-radius: 0 0 5px 5px; /* Add border radius */
}

.sidebar .dropdown:hover .dropdown-content {
    display: block;
}

.logout-btn {
    background-color: #ff6347;
    border: none;
    color: #fff;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: auto; /* Pushes the button to the bottom */
}

.logout-btn:hover {
    background-color: #d43f24;
}

.content {
    flex: 1; /* Occupy remaining space */
    padding: 20px;
    overflow-y: auto; /* Enable vertical scroll if needed */
}

.view-container {
    width: 80%;
    margin: auto;
}

.view-container h2 {
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid #ccc;
}

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background-color: #333;
    color: #fff;
}

tbody tr:nth-child(even) {
    background-color: #f2f2f2;
}
</style>
</head>
<body>

<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h1><a href="admindashboard.php" style="color: white; text-decoration: none;">Janak Travels</a></h1>
        <h2>Navigation Panel</h2>
        <ul>
            <li class="dropdown">
                <a href="#">User</a>
                <div class="dropdown-content">
                    <a href="adduser.php">Add User</a>
                    <a href="viewuser.php">View User</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="#">Bookings</a>
                <div class="dropdown-content">
                    <a href="addbooking.php">Add Booking</a>
                    <a href="viewbooking.php">View Booking</a>
                </div>
            </li>
        </ul>
        <h2>Packages</h2>
        <ul>
            <li><a href="#">Beach</a></li>
            <li><a href="#">Desert</a></li>
            <li><a href="#">Mountains</a></li>
            <li><a href="#">Cruise</a></li>
            <li><a href="#">Chardham Yatra</a></li>
            <li><a href="#">Historical</a></li>
        </ul>
        <button class="logout-btn">Log Out</button>
    </div>

    <!-- Main Content Area -->
    <div class="content">
        <!-- View Bookings Section -->
        <div class="view-container">
            <h2>View Bookings</h2>
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Destination</th>
                    <th>Date From</th>
                    <th>Date To</th>
                    <th>People</th>
                    <th>Adults</th>
                    <th>Children</th>
                    <th>Name</th>
                    <th>Source</th>
                    <th>Email</th>
                    <th>Phone</th>
                </tr>
                </thead>
                <tbody id="booking-table-body">
                    <!-- Booking data will be loaded here dynamically -->
                    <?php
                    require_once 'C:\xampp\htdocs\Travels_Website\includes\conn.php';

                    // Create connection
                    $conn = new mysqli($servername, $username, $password, $database);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // SQL query to select all rows from the bookings table
                    $sql = "SELECT * FROM bookings";

                    // Execute the query
                    $result = $conn->query($sql);

                    // Check if there are rows returned
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row["id"] . "</td>
                                    <td>" . $row["destination"] . "</td>
                                    <td>" . $row["date_from"] . "</td>
                                    <td>" . $row["date_to"] . "</td>
                                    <td>" . $row["people"] . "</td>
                                    <td>" . $row["adults"] . "</td>
                                    <td>" . $row["children"] . "</td>
                                    <td>" . $row["name"] . "</td>
                                    <td>" . $row["source"] . "</td>
                                    <td>" . $row["email"] . "</td>
                                    <td>" . $row["phone"] . "</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='11'>0 results</td></tr>";
                    }

                    // Close the database connection
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- JavaScript for fetching and displaying bookings -->
<script>
    window.onload = function() {
        fetch('fetchbooking.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const bookings = data.data;
                const tableBody = document.getElementById('booking-table-body');
                tableBody.innerHTML = '';

                bookings.forEach(booking => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${booking.id}</td>
                        <td>${booking.destination}</td>
                        <td>${booking.date_from}</td>
                        <td>${booking.date_to}</td>
                        <td>${booking.people}</td>
                        <td>${booking.adults}</td>
                        <td>${booking.children}</td>
                        <td>${booking.name}</td>
                        <td>${booking.source}</td>
                        <td>${booking.email}</td>
                        <td>${booking.phone}</td>
                    `;
                    tableBody.appendChild(row);
                });
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    };
</script>

</body>
</html>
