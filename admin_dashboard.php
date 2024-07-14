<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('assets/img/admin_dash.jpg') no-repeat center center fixed; 
            background-size: cover;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            opacity: 0;
            animation: fadeIn 1s forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        .dashboard-container {
            background-color: rgba(255, 255, 255, 0.9);
            color: #333;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
            width: 90%;
            max-width: 700px;
            margin: 100px auto 20px;
        }

        .dashboard-container h2 {
            margin-bottom: 20px;
            font-size: 2em;
            font-weight: 600;
        }

        .dashboard-container p {
            margin: 10px 0;
            font-size: 1.3em;
        }

        .dashboard-container a {
            color: #007bff;
            text-decoration: none;
        }

        .dashboard-container a:hover {
            text-decoration: underline;
        }

        .admin-options {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 10px;
        }

        .admin-options div {
            background-color: #f0f0f0;
            border-radius: 10px;
            padding: 10px;
            margin: 10px 0;
            flex: 1 1 calc(33% - 20px);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .admin-options div:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        .admin-options div button {
            width: 100%;
            padding: 15px;
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.3em;
            transition: background-color 0.3s ease;
        }

        .admin-options div button:hover {
            background-color: #0056b3;
        }

        .logout-button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.3em;
            margin-top: 20px;
            transition: background-color 0.3s ease;
            display: inline-block;
            width: 100%;
        }

        .logout-button:hover {
            background-color: #c82333;
        }

        .back-login-button {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.3em;
            margin-top: 20px;
            transition: background-color 0.3s ease;
            display: inline-block;
            width: 100%;
        }

        .back-login-button:hover {
            background-color: #5a6268;
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
            margin-left: -50px;
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

        footer {
            background-color: #353a3f;
            color: white;
            padding: 30px 0;
            text-align: center;
            width: 100%;
            position: relative;
            bottom: 0;
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
    <div class="dashboard-container">
        <h2>Welcome, Admin!</h2>
        <p>This is Fitness Kingdom Gym Admin Dashboard.</p>
        <div class="admin-options">
            <div><button onclick="window.location.href='manage_members.php'">Manage Members</button></div>
            <div><button onclick="window.location.href='manage_appointments_admin.php'">Manage Appointments</button></div>
            <div><button onclick="window.location.href='manage_products.php'">Manage Products</button></div>
            <div><button onclick="window.location.href='admin_class_schedule.php'">Manage Online Classes</button></div>
            <div><button onclick="window.location.href='manage_news.php'">Manage News</button></div>
            <div><button onclick="window.location.href='manage_orders.php'">Manage Orders</button></div>
            <div><button onclick="window.location.href='manage_messages.php'">Manage Website Messages</button></div>
            <div><button onclick="window.location.href='manage_class_recordings.php'">Manage Class Recordings</button></div>
            <div><button onclick="window.location.href='manage_class_fees.php'">Manage Member Class Fees</button></div>
        </div>
        <button class="logout-button" onclick="window.location.href='logout.php'">Logout</button>
        <button class="back-login-button" onclick="window.location.href='login.php'">Back to Login</button>
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
