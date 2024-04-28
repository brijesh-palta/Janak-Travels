<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-nkp9Qav2VVsLm7JyyQzGz0RIm1KQ3bF3eoJkDEqFfxIix0U6PmkkdUp1r3RNf1br" crossorigin="anonymous">
    <title>Booking Detail</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        .container {
            display: flex;
            height: 100%;
        }

        .sidebar {
            background: linear-gradient(to bottom, #2c3e50, #34495e);
            width: 250px;
            min-width: 200px;
            max-height: 100%;
            overflow-y: auto;
            position: relative;
            transition: all 0.5s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            text-align: center;
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            padding: 15px 20px;
            color: #fff;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.2s;
        }

        .sidebar a:hover {
            background-color: #34495e;
            transform: scale(1.1);
        }

        .brand {
            text-align: center;
            margin-bottom: 20px;
            color: #fff;
            font-size: 24px;
            padding: 20px 0;
        }

        #main-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .navbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 10px 20px;
            background-color: #34495e;
            color: #fff;
            margin-left: 250px;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
        }

        .navbar a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Navbar for logout button -->
    <div class="navbar">
        <a href="#" onclick="logout()">Logout</a>
    </div>
    </div>
  <div class="container" id="container">
    <nav class="sidebar" id="sidebar">
      <div class="brand">
        <a href="admindashboard.php" style="color: inherit; text-decoration: none;">Janak Travels</a>
      </div>
      <a href="user.php" onclick="loadPage('user.php')"><i class="fas fa-user"></i> Users</a>
      <a href="seebooking.php" onclick="loadPage('seebooking.php')"><i class="fas fa-calendar-check"></i> Booking</a>
      <a href="#" onclick="togglePackagesDropdown()"><i class="fas fa-box"></i> Packages <i class="fas fa-caret-down"></i></a>
      <div id="packagesDropdown">
        <a href="seebeach.php" onclick="loadPage('seebeach.php')">Beach</a>
        <a href="seecruise.php" onclick="loadPage('seecruise.php')">Cruise</a>
        <a href="seedesert.php" onclick="loadPage('seedesert.php')">Desert</a>
        <a href="seemountain.php" onclick="loadPage('seemountain.php')">Mountain</a>
        <a href="seehistrocial.php" onclick="loadPage('seehistrocial.php')">Historical</a>
        <a href="seechardhamyatra.php" onclick="loadPage('seechardhamyatra.php')">Char Dham Yatra</a>
      </div>
      <a href="#" onclick="loadPage('about.html')">About Us</a>
      <a href="#" onclick="loadPage('contact.html')">Contact Us</a>
    </nav>

        <!-- Main content -->
        <div id="main-content">
            <h1>Booking Details</h1>
            <table id="bookingTable" border="1">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Location</th>
                    <th>Number of People</th>
                    <th>People Names</th>
                </tr>
                <!-- Booking data will be appended here dynamically -->
            </table>
        </div>
    </div>

    <!-- jQuery and Ajax script -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: 'fetchbeach.php',
                type: 'POST',
                dataType: 'html',
                success: function(response) {
                    $('#bookingTable').html(response);
                }
            });
        });

        // Function to load pages dynamically
        function loadPage(page) {
            fetch(page)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('main-content').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error loading page:', error);
                });
        }

        function togglePackagesDropdown() {
            var dropdown = document.getElementById("packagesDropdown");
            if (dropdown.style.display === "none") {
                dropdown.style.display = "block";
            } else {
                dropdown.style.display = "none";
            }
        }

        function logout() {
            // Redirect to login page
            window.location.href = 'logout.php';
        }
    </script>
</body>
</html>
