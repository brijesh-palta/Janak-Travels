<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Janak Travels - Contact Us</title>
  <link rel="stylesheet" href="contact.css">
  <style>
    .navbar {
        background-color: rgba(255, 255, 255, 0.5); 
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      color: #000000; 
      padding: 10px 20px;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 9999;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navbar-brand {
      font-size: 24px;
      text-decoration: none;
      color: #000; 
    }

    .navbar-links {
      display: flex;
    }

    .navbar-links a {
      text-decoration: none;
      color: #000; 
      margin-left: 20px;
    }

    .navbar-links a:first-child {
      margin-left: 0;
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

  <div class="contact-container">
    <div class="contact-form">
      <div class="first-container">
        <div class="info-container">
          <div>
            <img class="icon"/>
            <h3>Address</h3>
            <p>9 Patel colony Nehru Nagar Jamnagar Gujarat,361008</p>
          </div>
          <div>
            <img class="icon"/>
            <h3>Lets Talk</h3>
            <p>+91 9265825526</p>
          </div>
          <div>
            <img class="icon"/>
            <h3>General Support</h3>
            <p>brijeshpalta99@gmail.com</p>
          </div>
        </div>
      </div>
      <div class="second-container">
        <h2>Send Us A Message</h2>
        <form>
          <div class="form-group">
            <label for="name-input">Tell us your name*</label>
            <input id="name-input" type="text" placeholder="First name" required="required"/>
            <input type="text" placeholder="Last name" required="required"/>
          </div>
          <div class="form-group">
            <label for="email-input">Enter your email*</label>
            <input id="email-input" type="text" placeholder="Eg. example@email.com" required="required"/>
          </div>
          <div class="form-group">
            <label for="phone-input">Enter phone number*</label>
            <input id="phone-input" type="text" placeholder="Eg. +1 800 000000" required="required"/>
          </div>
          <div class="form-group">
            <label for="message-textarea">Message</label>
            <textarea id="message-textarea" placeholder="Write us a message"></textarea>
          </div>
          <button>Send message</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
=======
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Janak Travels - Contact Us</title>
  <link rel="stylesheet" href="contact.css">
  <style>
    .navbar {
        background-color: rgba(255, 255, 255, 0.5); 
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      color: #000000; 
      padding: 10px 20px;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 9999;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navbar-brand {
      font-size: 24px;
      text-decoration: none;
      color: #000; 
    }

    .navbar-links {
      display: flex;
    }

    .navbar-links a {
      text-decoration: none;
      color: #000; 
      margin-left: 20px;
    }

    .navbar-links a:first-child {
      margin-left: 0;
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

  <div class="contact-container">
    <div class="contact-form">
      <div class="first-container">
        <div class="info-container">
          <div>
            <img class="icon"/>
            <h3>Address</h3>
            <p>9 Patel colony Nehru Nagar Jamnagar Gujarat,361008</p>
          </div>
          <div>
            <img class="icon"/>
            <h3>Lets Talk</h3>
            <p>+91 9265825526</p>
          </div>
          <div>
            <img class="icon"/>
            <h3>General Support</h3>
            <p>brijeshpalta99@gmail.com</p>
          </div>
        </div>
      </div>
      <div class="second-container">
        <h2>Send Us A Message</h2>
        <form>
          <div class="form-group">
            <label for="name-input">Tell us your name*</label>
            <input id="name-input" type="text" placeholder="First name" required="required"/>
            <input type="text" placeholder="Last name" required="required"/>
          </div>
          <div class="form-group">
            <label for="email-input">Enter your email*</label>
            <input id="email-input" type="text" placeholder="Eg. example@email.com" required="required"/>
          </div>
          <div class="form-group">
            <label for="phone-input">Enter phone number*</label>
            <input id="phone-input" type="text" placeholder="Eg. +1 800 000000" required="required"/>
          </div>
          <div class="form-group">
            <label for="message-textarea">Message</label>
            <textarea id="message-textarea" placeholder="Write us a message"></textarea>
          </div>
          <button>Send message</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
>>>>>>> 9653623 (Initial commit with Dockerfile, compose files and Jenkinsfile)
