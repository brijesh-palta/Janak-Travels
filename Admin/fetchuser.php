<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Janak Travels Admin Panel - View Users</title>
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
    padding: 10px;
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

.view-container {
    margin: auto;
    width: 70%; /* Adjust width as needed */
    padding: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

table th,
table td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
}

table th {
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
                    <a href="#">Add Booking</a>
                    <a href="booking.php">View Booking</a>
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

    <!-- View User -->
    <div class="view-container">
        <h2>View Users</h2>
        <table>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Mobile Number</th>
                <th>Status</th>
            </tr>
            <?php
            require_once 'C:\xampp\htdocs\Travels_Website\includes\conn.php';

            // Retrieve users from the database
            $sql = "SELECT * FROM users";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["username"] . "</td>
                            <td>" . $row["email"] . "</td>
                            <td>" . $row["mobile_number"] . "</td>
                            <td>" . ($row["status"] == 1 ? 'Active' : 'Inactive') . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No users found</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
</div>
</body>
</html>
