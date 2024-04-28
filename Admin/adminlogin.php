<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Janak Travels</title>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.3.1/css/swiper.css'>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <div class="login-container">
    <div class="login-form">
      <div class="login-form-inner">
        <h1>Janak Travels</h1>
        <p class="body-text">Unlock the Control Center: Admin Access to Janak Travels</p>
        <br><br>
        <form action="admin_login.php" method="post">
          <div class="login-form-group">
            <label for="email">Email <span class="required-star">*</span></label>
            <input type="text" placeholder="email@website.com" id="email" name="email"> <!-- Added name attribute -->
          </div>
          <br>
          <div class="login-form-group">
            <label for="pwd">Password <span class="required-star">*</span></label>
            <input autocomplete="off" type="password" placeholder="Minimum 8 characters" id="pwd" name="password"> <!-- Changed type to password and added name attribute -->
          </div>
          <br>
          <div class="login-form-group single-row">
            <div class="custom-check">
              <input autocomplete="off" type="checkbox" checked id="remember" name="remember"> <!-- Added name attribute -->
              <label for="remember">Remember me</label>
            </div>
          </div>
          <button type="submit" class="rounded-button login-cta">Login</button> <!-- Changed anchor tag to button element -->
        </form>
      </div>
    </div>
    <div class="onboarding">
      <div class="swiper-container">
        <div class="swiper-wrapper">
          <div class="swiper-slide color-1">
            <div class="slide-image">
              <img src="index.jpg" loading="lazy" alt="" />
            </div>
            <div class="slide-content">
              <h2>Unlock the Control Center</h2>
              <p>Admin Access to Janak Travels</p>
            </div>
          </div>
          <div class="swiper-slide color-1">
            <div class="slide-image">
              <img src="index.jpg" loading="lazy" alt="" />
            </div>
            <div class="slide-content">
              <h2>Explore the Administrative Portal</h2>
              <p>Gain Access to Janak Travels Admin Panel</p>
            </div>
          </div>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
      </div>
    </div>
  </div>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.3.0/js/swiper.min.js'></script>
  <script  src="script.js"></script>
</body>
</html>
