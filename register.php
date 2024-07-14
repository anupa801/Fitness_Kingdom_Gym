<?php
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "INSERT INTO users (first_name, last_name, age, address, phone, email, gender, username, password)
            VALUES ('$first_name', '$last_name', $age, '$address', '$phone', '$email', '$gender', '$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful. Please login here.'); window.location.href = 'login.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Kingdom Gym - Register</title>
    <style>
        body {
            background: url('assets/img/Register_bc.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            position: relative;
            color: white;
            opacity: 0; /* Initially hide the body for animation */
            animation: fadeIn 1s forwards; /* Fade in animation */
        }

        @keyframes fadeIn {
            to {
                opacity: 1; /* Fade to fully visible */
            }
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
            width: 100%;
            display: flex;
            justify-content: center;
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
            position: relative;
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
            width: 100%;
        }

        .register-box {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            display: flex;
            max-width: 1200px;
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        .register-box .register-form {
            width: 40%;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: black;
            animation: slideIn 1s forwards; /* Slide in animation */
        }

        .register-box .register-image {
            width: 60%;
            background: url('assets/img/register_F.jpg') no-repeat center center;
            background-size: cover;
        }

        .register-box h2 {
            margin-bottom: 20px;
            font-size: 28px;
            color: #333;
        }

        .register-box input,
        .register-box select {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .register-box button {
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

        .register-box button:hover {
            background-color: #0056b3;
        }

        .register-box .back-to-login {
            text-align: center;
            margin-top: 20px;
        }

        .register-box .back-to-login a {
            background-color: #6c757d; /* Gray color */
            color: white;
            border: none;
            padding: 15px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            transition: background-color 0.3s ease;
            width: 95%; /* Increase the width */
            text-align: center;
        }

        .register-box .back-to-login a:hover {
            background-color: #5a6268;
            text-decoration: none;
        }

        .gender-options {
            display: flex;
            margin-right:61px;
            margin-top: 10px;
        }

        .gender-options label {
            display: flex;
            align-items: center;
            margin-right:31px;
        }

        .gender-options input {
            margin-right: 20px;
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
    </style>
</head>
<body>
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
        <div class="register-box">
            <div class="register-image"></div>
            <div class="register-form">
                <h2>Register</h2>
                <form action="register.php" method="post">
                    <input type="text" name="first_name" placeholder="First Name" required>
                    <input type="text" name="last_name" placeholder="Last Name" required>
                    <input type="number" name="age" placeholder="Age" required>
                    <input type="text" name="address" placeholder="Address" required>
                    <input type="text" name="phone" placeholder="Phone Number" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <div class="gender-options"><p> &nbsp; &nbsp;Gender : </p>
                    &nbsp; &nbsp;<label><input type="radio" name="gender" value="Male" required>  Male</label>
                    &nbsp; &nbsp;<label><input type="radio" name="gender" value="Female" required>  Female</label>
                    </div>
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Register</button>
                </form>
                <div class="back-to-login">
                    <a href="login.php">Back to Login</a>
                </div>
            </div>
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
</body>
</html>
