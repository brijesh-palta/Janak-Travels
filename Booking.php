<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Janak Travels</title>
  <link rel="stylesheet" href="bookingstyle.css"> 
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

<!-- Booking Section -->
<div id="booking" class="section">
    <div class="section-center">
        <div class="container">
            <div class="row">
                <div class="booking-form">
                    <div class="form-header">
                        <h1>Do Your Booking Here</h1>
                    </div>
                    <form id="bookingForm" method="post" action="includes/bookingProcess.php" onsubmit="return handleBooking()">
                        <div class="form-group">
                            <input class="form-control" type="text" name="destination" placeholder="Country, ZIP, city..." required>
                            <span class="form-label">Destination</span>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" type="date" name="date_from" required>
                                    <span class="form-label">Check In</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" type="date" name="date_to" required>
                                    <span class="form-label">Check out</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
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
                                    <span class="select-arrow"></span>
                                    <span class="form-label">People</span>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                                    <span class="select-arrow"></span>
                                    <span class="form-label">Adults</span>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                                    <span class="select-arrow"></span>
                                    <span class="form-label">Children</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="name" placeholder="Enter your Name" required>
                                    <span class="form-label">Name</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="source" placeholder="Enter your Source" required>
                                    <span class="form-label">Source</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" type="email" name="email" placeholder="Enter your Email" required>
                                    <span class="form-label">Email</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" type="tel" name="phone" placeholder="Enter your Phone" required>
                                    <span class="form-label">Phone</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-btn">
                            <button type="submit" class="submit-btn">Book Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function redirectToIndex() {
        window.location.href = "userindex.php";
    }

    function handleBooking() {
        // Simulating successful booking
        // Here you can perform AJAX call or any server-side validation
        // If booking is successful, show success message and redirect

        // Simulate booking success
        setTimeout(function() {
            alert("Booking successful!");
            redirectToIndex();
        }, 1000);

        // Prevent form submission
        return false;
    }
</script>
</body>
</html>
=======
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Janak Travels</title>
  <link rel="stylesheet" href="bookingstyle.css"> 
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

<!-- Booking Section -->
<div id="booking" class="section">
    <div class="section-center">
        <div class="container">
            <div class="row">
                <div class="booking-form">
                    <div class="form-header">
                        <h1>Do Your Booking Here</h1>
                    </div>
                    <form id="bookingForm" method="post" action="includes/bookingProcess.php" onsubmit="return handleBooking()">
                        <div class="form-group">
                            <input class="form-control" type="text" name="destination" placeholder="Country, ZIP, city..." required>
                            <span class="form-label">Destination</span>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" type="date" name="date_from" required>
                                    <span class="form-label">Check In</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" type="date" name="date_to" required>
                                    <span class="form-label">Check out</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
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
                                    <span class="select-arrow"></span>
                                    <span class="form-label">People</span>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                                    <span class="select-arrow"></span>
                                    <span class="form-label">Adults</span>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                                    <span class="select-arrow"></span>
                                    <span class="form-label">Children</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="name" placeholder="Enter your Name" required>
                                    <span class="form-label">Name</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="source" placeholder="Enter your Source" required>
                                    <span class="form-label">Source</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" type="email" name="email" placeholder="Enter your Email" required>
                                    <span class="form-label">Email</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" type="tel" name="phone" placeholder="Enter your Phone" required>
                                    <span class="form-label">Phone</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-btn">
                            <button type="submit" class="submit-btn">Book Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function redirectToIndex() {
        window.location.href = "userindex.php";
    }

    function handleBooking() {
        // Simulating successful booking
        // Here you can perform AJAX call or any server-side validation
        // If booking is successful, show success message and redirect

        // Simulate booking success
        setTimeout(function() {
            alert("Booking successful!");
            redirectToIndex();
        }, 1000);

        // Prevent form submission
        return false;
    }
</script>
</body>
</html>
>>>>>>> 9653623 (Initial commit with Dockerfile, compose files and Jenkinsfile)
