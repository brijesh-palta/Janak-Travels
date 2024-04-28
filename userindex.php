<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page
    header("Location: loginpage.php");
    exit(); // Ensure that no other code is executed after redirection
}

// If the user is logged in, you can proceed with the rest of your code here
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Janak Travels</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <div class="container">
      <div class="navbar">
        <div class="menu">
          <h3 class="logo">Janak<span>Travels</span></h3>
          <div class="hamburger-menu">
            <div class="bar"></div>
          </div>
        </div>
      </div>

      <div class="main-container">
        <div class="main">
          <header>
            <div class="overlay">
              <div class="inner">
                <h2 class="title">Janak Travels</h2>
                <p>
                  Explore the india with us. Where to next?         
                </p>
              </div>
            </div>
          </header>
        </div>

        <div class="shadow one"></div>
        <div class="shadow two"></div>
      </div>

      <div class="links">
        <ul>
          <li><a href="Booking.php" style="--i: 0.05s;">Booking</a></li>
          <li><a href="#" style="--i: 0.15s;">Payment</a></li>
          <li><a href="Packages.php" style="--i: 0.2s;">Packages</a></li>
          <li><a href="Aboutus.php" style="--i: 0.25s;">About Us</a></li>
          <li><a href="Contactus.php" style="--i: 0.3s;">Contact Us</a></li>
          <li><a href="logout.php" style="--i: 0.35s;">logout</a></li>
        </ul>
      </div>

  </body>
</html>
<!-- partial -->
  <script  src="./script.js"></script>

</body>
</html>
