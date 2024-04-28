<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Janak Travels Admin Panel - Add Booking</title>
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
    flex: 1; /* Occupy remaining space */
    padding: 20px;
    overflow-y: auto; /* Enable vertical scroll if needed */
}

.form-container {
    width: 80%;
    margin: auto;
}

.form-container h2 {
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group input, 
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.form-group span {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    background-color: #fff;
    padding: 0 5px;
    color: #999;
    transition: transform 0.3s ease, font-size 0.3s ease, color 0.3s ease;
}

.form-group input:focus, 
.form-group select:focus {
    outline: none;
    border-color: #555;
}

.form-group input:focus + span,
.form-group select:focus + span,
.form-group input:not(:placeholder-shown) + span,
.form-group select:not(:placeholder-shown) + span {
    transform: translateY(-80%) scale(0.8);
    font-size: 12px;
    color: #333;
}

.form-submit {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.form-submit:hover {
    background-color: #0056b3;
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

    <!-- Main Content Area -->
    <div class="content">
        <!-- Add Booking Form -->
        <div class="form-container">
            <h2>Add Booking</h2>
            <form id="add-booking-form" action="addbookingprocess.php" method="POST">
                <div class="form-group">
                    <input class="form-control" type="text" name="destination" placeholder="Destination" required>
                    <span class="form-label">Destination</span>
                </div>
                <div class="form-group">
                    <input class="form-control" type="date" name="date_from" required>
                    <span class="form-label">Check In</span>
                </div>
                <div class="form-group">
                    <input class="form-control" type="date" name="date_to" required>
                    <span class="form-label">Check out</span>
                </div>
                <div class="form-group">
                    <select class="form-control" name="people" required>
                        <option value="" selected hidden>People</option>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                    </select>
                    <span class="form-label">People</span>
                </div>
                <div class="form-group">
                    <select class="form-control" name="adults" required>
                        <option value="" selected hidden>Adults</option>
                        <option>0</option>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                    </select>
                    <span class="form-label">Adults</span>
                </div>
                <div class="form-group">
                    <select class="form-control" name="children" required>
                        <option value="" selected hidden>Children</option>
                        <option>0</option>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                    </select>
                    <span class="form-label">Children</span>
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" name="name" placeholder="Name" required>
                    <span class="form-label">Name</span>
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" name="source" placeholder="Source" required>
                    <span class="form-label">Source</span>
                </div>
                <div class="form-group">
                    <input class="form-control" type="email" name="email" placeholder="Email" required>
                    <span class="form-label">Email</span>
                </div>
                <div class="form-group">
                    <input class="form-control" type="tel" name="phone" placeholder="Phone" required>
                    <span class="form-label">Phone</span>
                </div>
                <button type="submit" class="form-submit">Submit</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>

