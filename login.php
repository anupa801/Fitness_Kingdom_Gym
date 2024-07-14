<?php
session_start();

// Database connection
include('db_connect.php');

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // Check if the user is an admin
        if (($username === 'admin' && $password === '12345') || ($username === 'user' && $password === '12345')) {
            $_SESSION['username'] = 'admin';
            header("Location: admin_dashboard.php");
            exit;
        }

        // Check the database for the user
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $_SESSION['username'] = $username;
            header("Location: member_dashboard.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Please enter both username and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Fitness Kingdom Gym</title>
    <style>
        body {
            background: url('assets/img/login.jpg');
            background-size: cover;
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: white;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.12);
            z-index: 1;
        }

        header, footer, .main-content {
            position: relative;
            z-index: 2;
        }

        header {
            background-color: #353a3f;
            color: white;
            padding: 10px 0;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .nav-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 90%;
            margin: 0 auto;
        }

        .nav-bar .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .nav-bar nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .nav-bar nav ul li {
            margin-left: 25px;
        }

        .nav-bar nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .nav-bar nav ul li a:hover,
        .nav-bar nav ul li a:focus {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-bar nav ul li a.active {
            color: #007bff;
        }

        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
            padding-top: 150px; 
            margin-bottom: 100px;
            opacity: 0;
            animation: fadeIn 0.3s forwards;
            animation-delay: 0.3s;
        }

        .login-box {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            display: flex;
            max-width: 900px;
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        .login-box .login-form {
            width: 50%;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-box .login-image {
            width: 50%;
            background: url('assets/img/log.jpg');
            background-size: cover;
        }

        .login-box h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 28px;
        }

        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .login-box input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .login-box input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .login-box .register-link {
            margin-top: 20px;
            text-align: center;
        }

        .login-box .register-link a {
            color: #007bff;
            text-decoration: none;
            font-size: 16px;
        }

        .login-box .register-link p {
            color: black;
        }

        .login-box .register-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            margin: 10px 0;
        }

        footer {
            background-color: #353a3f;
            color: white;
            padding: 30px 0;
            text-align: center;
            width: 100%;
        }

        .footer-content {
            display: flex;
            justify-content: space-around;
            text-align: center;
            width: 75%;
            margin: 0 auto;
            flex-wrap: wrap;
        }

        .footer-content div {
            flex: 1;
            margin: -8px 10px;
        }

        .footer-content h2 {
            font-size: 20px;
            margin-bottom: 20px;
        }

        .footer-content p,
        .footer-content a {
            font-size: 16px;
            line-height: 1.2;
            color: #ccc;
            text-decoration: none;
        }

        .footer-content a:hover {
            text-decoration: underline;
        }

        .footer-bottom {
            margin-top: 20px;
        }

        .footer-content div p {
            margin: 3px 0;
        }

        /* Spinner Styles */
        .spinner-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.9);
            z-index: 9999;
        }

        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border-top-color: #007bff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="spinner-wrapper" id="spinner-wrapper">
        <div class="spinner"></div>
    </div>

    <header>
        <div class="nav-bar">
            <div class="logo">Fitness Kingdom Gym</div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#about">About Me</a></li>
                    <li><a href="#pricing">Pricing</a></li>
                    <li><a href="#products">Products</a></li>
                    <li><a href="#news">News</a></li>
                    <li><a href="login.php">Login/Register</a></li>
                    <li><a href="#contact">Contact Us</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="main-content">
        <div class="login-box">
            <div class="login-form">
                <h2>Login</h2>
                <?php if (!empty($error)) { echo '<div class="error-message">' . $error . '</div>'; } ?>
                <form method="post" action="">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="submit" value="Login">
                </form>
                <div class="register-link">
                    <p>Don't have an account?  <a href="register.php"> Register here</a></p>
                </div>
            </div>
            <div class="login-image"></div>
        </div>
    </div>
    <footer>
        <div class="footer-content">
            <div>
                <h2>About Us</h2>
                <p>Fitness Kingdom is your ultimate destination for achieving your fitness goals. Our certified trainers and state-of-the-art facilities ensure you get the best out of your workouts.</p>
            </div>
            <div>
                <h2>Quick Links</h2>
                <p><a href="#home">Home</a></p>
                <p><a href="#about">About Me</a></p>
                <p><a href="#pricing">Pricing</a></p>
                <p><a href="#products">Products</a></p>
                <p><a href="#login">Login</a></p>
                <p><a href="#contact">Contact Us</a></p>
            </div>
            <div>
                <h2>Contact Us</h2>
                <p>678/9B, Church Rd, Ragama</p>
                <p>071 7879 168</p>
                <p>fitnesskingdomgym@gmail.com</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2024 Fitness Kingdom Gym. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        window.addEventListener('load', function() {
            const spinnerWrapper = document.getElementById('spinner-wrapper');
            spinnerWrapper.style.display = 'none';
            document.querySelector('.main-content').style.opacity = '1';
        });
    </script>
</body>
</html>
