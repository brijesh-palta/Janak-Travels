<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Janak Travels - Packages</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('index.jpg'); /* Add your background image URL here */
            background-size: cover;
            background-position: center;
            backdrop-filter: blur(10px); /* Apply blur effect to the background */
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.9); /* Adjust the background color with transparency */
            padding: 10px 0;
            box-shadow: 0 2px 4px rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ccc;
            cursor: pointer;
        }

        .navbar-brand {
            font-size: 25px;
            font-weight: bold;
            color: #333;
            margin-left: 20px;
        }

        .navbar-links {
            display: flex;
            margin-right: 20px;
        }

        .navbar-links a {
            text-decoration: none;
            color: #333;
            font-size: 14px;
            margin-left: 20px;
        }

        .navbar-links a:hover {
            text-decoration: underline;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9); /* Adjust the background color with transparency */
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.8);
            margin-top: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .packages-container {
            display: flex;
            justify-content: space-between;
        }

        .package {
            width: 30%;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0);
        }

        .package:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0);
        }

        .package img {
            max-width: 100%;
            height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .package h2 {
            margin-top: 0;
        }

        .package p {
            margin-top: 0;
            display: none;
            position: absolute;
            background-color: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            bottom: 0;
            left: 0;
            right: 0;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .package:hover p {
            display: block;
            opacity: 1;
        }

        .package .button {
            display: inline-block;
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .package:hover .button {
            opacity: 0;
        }

        .package:hover p {
            opacity: 1;
        }

        .package .button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="navbar" onclick="redirectToIndex()">
        <div class="navbar-brand">Janak Travels</div>
        <div class="navbar-links">
            <a href="Booking.php">Booking</a>
            <a href="#">Payment</a>
            <a href="Packages.php">Packages</a>
            <a href="Aboutus.php">About Us</a>
            <a href="Contactus.php">Contact Us</a>
        </div>
    </div>

    <div class="container">
        <h1>Travel Packages</h1>

        <div class="packages-container">
            <div class="package" onclick="toggleDescription(this)" data-url="beach.php">
                <img src="Beach.jpg" alt="Package 1">
                <h2>Beach</h2>
                <p>Enjoy a relaxing vacation on some of the world's most beautiful beaches.</p>
            </div>

            <div class="package" onclick="toggleDescription(this)" data-url="Cruise.php">
                <img src="cruise.jpg" alt="Package 7">
                <h2>Cruise Vacation</h2>
                <p>Sail away to exotic destinations on luxurious cruise ships.</p>
            </div>

            <div class="package" onclick="toggleDescription(this)" data-url="Mountain.php">
                <img src="Mountain.jpg" alt="Package 3">
                <h2>Mountain Retreat</h2>
                <p>Escape to the serenity of the mountains and reconnect with nature.</p>
            </div>
        </div>

        <div class="packages-container">
            <div class="package" onclick="toggleDescription(this)" data-url="Desert.php">
                <img src="Desert.jpg" alt="Package 4">
                <h2>Desert Safari</h2>
                <p>Experience the thrill of a desert adventure with camel rides and traditional dinners.</p>
            </div>

            <div class="package" onclick="toggleDescription(this)" data-url="Historical.php">
                <img src="Historical.jpg" alt="Package 5">
                <h2>Historical Tour</h2>
                <p>Explore ancient ruins and historic landmarks with expert guides.</p>
            </div>

            <div class="package" onclick="toggleDescription(this)" data-url="Char Dham Yatra.php">
                <img src="Kedarnath.jpg" alt="Package 6">
                <h2>Char Dham Yatra</h2>
                <p>This Yatra or pilgrimage is a tour of four holy sites - Yamunotri, Gangotri, Kedarnath and Badrinath .</p>
            </div>
        </div>
    </div>

    <script>
        function openProfileModal() {
            var profileModal = document.getElementById('profileModal');
            profileModal.style.display = 'block';
        }

        // Redirect to the package URL when clicked
        document.querySelectorAll('.package').forEach(item => {
            item.addEventListener('click', event => {
                const packageUrl = item.getAttribute('data-url');
                if (packageUrl) {
                    window.location.href = packageUrl;
                }
            });
        });

        function toggleDescription(packageElement) {
            var description = packageElement.querySelector('p');
            if (description) {
                description.classList.toggle('active');
            }
        }

        function redirectToIndex() {
            window.location.href = "userindex.php";
        }
    </script>
</body>

</html>
=======
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Janak Travels - Packages</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('index.jpg'); /* Add your background image URL here */
            background-size: cover;
            background-position: center;
            backdrop-filter: blur(10px); /* Apply blur effect to the background */
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.9); /* Adjust the background color with transparency */
            padding: 10px 0;
            box-shadow: 0 2px 4px rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ccc;
            cursor: pointer;
        }

        .navbar-brand {
            font-size: 25px;
            font-weight: bold;
            color: #333;
            margin-left: 20px;
        }

        .navbar-links {
            display: flex;
            margin-right: 20px;
        }

        .navbar-links a {
            text-decoration: none;
            color: #333;
            font-size: 14px;
            margin-left: 20px;
        }

        .navbar-links a:hover {
            text-decoration: underline;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9); /* Adjust the background color with transparency */
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.8);
            margin-top: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .packages-container {
            display: flex;
            justify-content: space-between;
        }

        .package {
            width: 30%;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0);
        }

        .package:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0);
        }

        .package img {
            max-width: 100%;
            height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .package h2 {
            margin-top: 0;
        }

        .package p {
            margin-top: 0;
            display: none;
            position: absolute;
            background-color: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            bottom: 0;
            left: 0;
            right: 0;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .package:hover p {
            display: block;
            opacity: 1;
        }

        .package .button {
            display: inline-block;
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .package:hover .button {
            opacity: 0;
        }

        .package:hover p {
            opacity: 1;
        }

        .package .button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="navbar" onclick="redirectToIndex()">
        <div class="navbar-brand">Janak Travels</div>
        <div class="navbar-links">
            <a href="Booking.php">Booking</a>
            <a href="#">Payment</a>
            <a href="Packages.php">Packages</a>
            <a href="Aboutus.php">About Us</a>
            <a href="Contactus.php">Contact Us</a>
        </div>
    </div>

    <div class="container">
        <h1>Travel Packages</h1>

        <div class="packages-container">
            <div class="package" onclick="toggleDescription(this)" data-url="beach.php">
                <img src="Beach.jpg" alt="Package 1">
                <h2>Beach</h2>
                <p>Enjoy a relaxing vacation on some of the world's most beautiful beaches.</p>
            </div>

            <div class="package" onclick="toggleDescription(this)" data-url="Cruise.php">
                <img src="cruise.jpg" alt="Package 7">
                <h2>Cruise Vacation</h2>
                <p>Sail away to exotic destinations on luxurious cruise ships.</p>
            </div>

            <div class="package" onclick="toggleDescription(this)" data-url="Mountain.php">
                <img src="Mountain.jpg" alt="Package 3">
                <h2>Mountain Retreat</h2>
                <p>Escape to the serenity of the mountains and reconnect with nature.</p>
            </div>
        </div>

        <div class="packages-container">
            <div class="package" onclick="toggleDescription(this)" data-url="Desert.php">
                <img src="Desert.jpg" alt="Package 4">
                <h2>Desert Safari</h2>
                <p>Experience the thrill of a desert adventure with camel rides and traditional dinners.</p>
            </div>

            <div class="package" onclick="toggleDescription(this)" data-url="Historical.php">
                <img src="Historical.jpg" alt="Package 5">
                <h2>Historical Tour</h2>
                <p>Explore ancient ruins and historic landmarks with expert guides.</p>
            </div>

            <div class="package" onclick="toggleDescription(this)" data-url="Char Dham Yatra.php">
                <img src="Kedarnath.jpg" alt="Package 6">
                <h2>Char Dham Yatra</h2>
                <p>This Yatra or pilgrimage is a tour of four holy sites - Yamunotri, Gangotri, Kedarnath and Badrinath .</p>
            </div>
        </div>
    </div>

    <script>
        function openProfileModal() {
            var profileModal = document.getElementById('profileModal');
            profileModal.style.display = 'block';
        }

        // Redirect to the package URL when clicked
        document.querySelectorAll('.package').forEach(item => {
            item.addEventListener('click', event => {
                const packageUrl = item.getAttribute('data-url');
                if (packageUrl) {
                    window.location.href = packageUrl;
                }
            });
        });

        function toggleDescription(packageElement) {
            var description = packageElement.querySelector('p');
            if (description) {
                description.classList.toggle('active');
            }
        }

        function redirectToIndex() {
            window.location.href = "userindex.php";
        }
    </script>
</body>

</html>
>>>>>>> 9653623 (Initial commit with Dockerfile, compose files and Jenkinsfile)
