<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Janak Travels - About Us</title>
    <style>
        .navbar {
            background-color: #fff;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 25px;
            font-weight: bold;
            color: #000;
            text-decoration: none; 
        }

        .navbar-links {
            display: flex;
        }

        .navbar-links a {
            text-decoration: none;
            color: #000;
            font-size: 14px;
            margin-left: 20px;
        }

        .navbar-links a:hover {
            text-decoration: underline;
        }
        
        /* Additional styles for the content */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .section {
            margin-bottom: 40px;
        }

        .section-content {
            display: flex;
            align-items: center;
        }

        .section-text {
            flex: 1;
            padding-right: 20px;
        }

        .section-text p {
            font-size: 16px;
            line-height: 1.6;
            color: #333;
            margin-bottom: 20px;
        }

        .section-image {
            flex: 1;
            max-width: 200px; /* Adjust image size */
        }

        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
<div class="navbar">
    <div class="navbar-brand">
        <a href="userindex.php" style="text-decoration: none; color: black;">Janak Travels</a>
    </div>
    <div class="navbar-links">
        <a href="Booking.php">Booking</a>
        <a href="#">Payment</a>
        <a href="Packages.php">Packages</a>
        <a href="Aboutus.php">Aboutus</a>
        <a href="Contactus.php">Contact Us</a>
    </div>
</div>

    <div class="container">
        <h1>About Janak Travels</h1>

        <!-- Section 1 -->
        <div class="section">
            <div class="section-content">
                <div class="section-text">
                    <p>Janak Travels is a family-owned travel company that has been providing exceptional travel experiences for over 30 years. Our mission is to make every journey memorable, every destination accessible, and every traveler feel like a VIP.</p>
                </div>
                <div class="section-image">
                    <img src="index.jpg" alt="Image 1">
                </div>
            </div>
        </div>

        <!-- Section 2 -->
        <div class="section">
            <div class="section-content">
                <div class="section-text">
                    <p>We take pride in offering the best service in the industry. Our team of experienced professionals works tirelessly to ensure that your travel experience exceeds your expectations.</p>
                </div>
                <div class="section-image">
                    <img src="LOGIN_BG.jpg" alt="Image 2">
                </div>
            </div>
        </div>

        <!-- Section 3 -->
        <div class="section">
            <div class="section-content">
                <div class="section-text">
                    <p>At Janak Travels, customer satisfaction is our top priority. Whether you're planning a relaxing vacation, an adventurous getaway, or a business trip, we are committed to providing personalized service tailored to your needs.</p>
                </div>
                <div class="section-image">
                    <img src="Aboutus.jpg" alt="Image 3">
                </div>
            </div>
        </div>
    </div>
</body>
</html>
