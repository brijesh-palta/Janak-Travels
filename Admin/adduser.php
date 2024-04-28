<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Janak Travels Admin Panel</title>
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

.form-container {
    margin: auto;
    width: 50%; /* Adjust width as needed */
    padding: 20px;
    display: flex;
    flex-direction: column;
    border: 1px solid #ccc; /* Add border */
    border-radius: 5px;
}

.form-container label {
    margin-bottom: 10px;
}

.form-container input[type="text"],
.form-container input[type="email"],
.form-container input[type="password"],
.form-container input[type="submit"],
.form-container select {
    margin-bottom: 15px;
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.form-container input[type="submit"] {
    background-color: #4caf50;
    color: white;
    border: none;
    cursor: pointer;
}

.form-container input[type="submit"]:hover {
    background-color: #45a049;
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
                    <a href="fetchuser.php">View User</a>
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

    <!-- Form for adding a user -->
    <div class="form-container">
        <h2>Add User</h2>
        <form action="adduserprocess.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="mobile_number">Mobile Number:</label>
            <input type="text" id="mobile_number" name="mobile_number" required>
            <label for="token">Token:</label>
            <input type="text" id="token" name="token" required>
            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            <input type="submit" value="Add User">
        </form>
    </div>
</div>
</body>
</html>
