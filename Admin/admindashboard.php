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

.content {
    flex: 1;
    padding: 20px;
    transition: margin-left 0.3s ease; /* Transition for content margin */
    text-align: center; /* Center-align content */
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.content h2, .content p {
    margin: 10px; /* Adjust the margin as needed */
}
</style>
</head>
<body>

<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h1>Janak Travels</h1>
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
                    <a href="addbooking.php">Add Booking</a>
                    <a href="booking.php">View Booking</a>
                </div>
            </li>
        </ul>
        <h2>Packages</h2>
        <ul>
            <li><a href="fetchbeach.php">Beach</a></li>
            <li><a href="fetchdesert.php">Desert</a></li>
            <li><a href="fetchmountain.php">Mountains</a></li>
            <li><a href="fetchcruise.php">Cruise</a></li>
            <li><a href="fetchchardhamyatra.php">Chardham Yatra</a></li>
            <li><a href="fetchhistorical.php">Historical</a></li>
        </ul>
        <button class="logout-btn">Log Out</button>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>Welcome to Janak Travels Admin Panel</h2>
        <p>Janak Travels Admin Panel provides a comprehensive platform for managing various aspects of travel services. With its intuitive interface and robust features, administrators can efficiently handle user management, booking operations, and package management. Whether it's adding new users, viewing bookings, or updating package details, Janak Travels Admin Panel simplifies the tasks and enhances productivity. From beach getaways to historical tours, Janak Travels offers diverse travel packages to cater to every traveler's needs. Get ready to embark on a seamless administrative journey with Janak Travels Admin Panel!</p>
    </div>
</div>

</body>
</html>
