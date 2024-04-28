<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-xyJcnFUzET4QISb1KTsTl9dpqfHGE0I4we2yiOOSWnUJp4PoUOT7BEhj7x1a4JMXeXc+3pxtEvgSZb0UllXVZQ==" crossorigin="anonymous" />

    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url("LOGIN_BG.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .reset-form {
            width: 350px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            animation: slide-in 0.5s ease-out;
            transition: box-shadow 0.3s ease-in-out;
            background: rgba(255, 255, 255, 0.8);
        }

        .reset-form:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        .reset-form h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .reset-form input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .reset-form button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .reset-form button:hover {
            background-color: #45a049;
        }

        .reset-form .link-container {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .reset-form .link-container a {
            text-decoration: none;
            color: #333;
            font-size: 14px;
            transition: color 0.3s ease-in-out;
        }

        .reset-form .link-container a:hover {
            color: #45a049;
        }

        @keyframes slide-in {
            from {
                transform: translateX(-100%);
            }

            to {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <form class="reset-form" onsubmit="return validateForm()">
            <h1>Password Reset</h1>
            <input type="text" id="email" placeholder="Email" required>
            <button type="submit">Reset Password</button>
            <div class="link-container">
                <a href="loginpage" class="back-to-login">Back to Login</a>
            </div>
        </form>
    </div>

    <script>
        function validateForm() {
            var email = document.getElementById('email').value;

            // Empty Field Validation
            if (email.trim() === '') {
                alert('Please enter your email.');
                return false;
            }

            // Email Format Validation
            var emailFormat = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailFormat.test(email)) {
                alert('Please enter a valid email address.');
                return false;
            }


            // Show Success Message 
            alert('Password reset email sent to ' + email);

            return true; 
        }
    </script>
</body>

</html>
