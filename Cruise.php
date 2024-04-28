<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cruise Vacation Package - Janak Travels</title>
    <link rel="stylesheet" type="text/css" href="cruise.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .content-wrapper {
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(5px);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.5); /* Adjust the alpha (fourth) value for transparency */
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

        .package-details img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .package-details h2 {
            margin-top: 0;
        }

        .package-details p {
            margin-top: 0;
        }

        .package-details form {
            margin-top: 20px;
        }

        .package-details label {
            display: block;
            margin-bottom: 5px;
        }

        .package-details input[type="text"],
        .package-details input[type="email"],
        .package-details select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        .package-details input[type="text"]:focus,
        .package-details input[type="email"]:focus,
        .package-details select:focus {
            border-color: #4CAF50;
            outline: none;
        }
    </style>
</head>

<body>
    
    <div class="navbar">
        <a href="userindex.php" class="navbar-brand">Janak Travels</a>
        <div class="navbar-links">
            <a href="Booking.php">Booking</a>
            <a href="#">Payment</a>
            <a href="Packages.php">Packages</a>
            <a href="Aboutus.php">About Us</a>
            <a href="Contactus.php">Contact Us</a>
        </div>
    </div>

    <div class="container">
        <div class="booking-wrapper">
            <div class="booking-form">
                <div class="package-details">
                    <img src="cruise.jpg" alt="Cruise Vacation">
                </div>
            </div>
        </div>
        <div class="content-wrapper">
            <h1>Cruise Vacation Package</h1>
            <h2>Cruise Vacation</h2>
            <form id="bookingForm" action="includes/booking_beach.php" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email ID:</label>
                <input type="email" id="email" name="email" required>

                <label for="contact">Contact No:</label>
                <input type="text" id="contact" name="contact" required>

                <label for="location">Where do you live:</label>
                <input type="text" id="location" name="location" required>

                <label for="people">Number of people:</label>
                <select id="people" name="people" onchange="showPeopleNames(this.value)">
                    <option value="0">Select</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                </select>

                <div id="peopleNames"></div>

                <button type="submit" value="Book Now" class="button">Book Now </button>
            </form>
        </div>
    </div>

    <script>
        function showPeopleNames(numPeople) {
            var container = document.getElementById("peopleNames");
            container.innerHTML = "";
            for (var i = 1; i <= numPeople; i++) {
                var label = document.createElement("label");
                label.innerHTML = "Name of Person " + i + ":";
                var input = document.createElement("input");
                input.setAttribute("type", "text");
                input.setAttribute("name", "person" + i);
                container.appendChild(label);
                container.appendChild(input);
                container.appendChild(document.createElement("br"));
            }
        }
        document.getElementById("bookingForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent the default form submission
            // Perform an AJAX form submission
            var formData = new FormData(this);
            fetch(this.getAttribute("action"), {
                    method: this.getAttribute("method"),
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    // Display the confirmation message
                    alert("Booking successfully recorded.");
                    // Redirect to Packages.php after 3 seconds
                    setTimeout(function() {
                        window.location.href = "Packages.php";
                    }, 1000);
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>

</html>