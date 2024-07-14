<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Fetch all scheduled classes
$classes_query = $conn->query("SELECT * FROM class_schedule ORDER BY class_date, class_time");
$classes = $classes_query->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Class Schedule</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('assets/img/background5.jpg');
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
            min-height: 100vh;
        }

        header {
            background-color: #353a3f;
            color: white;
            padding: 15px 0;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
            width: 100%;
            display: flex;
            justify-content: center;
            position: fixed;
            top: 0;
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
            margin-left: 48px;
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

        .container {
            max-width: 1000px;
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            margin-top: 100px;
            margin-bottom: 40px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            animation: fadeIn 1s ease-in-out;
            width: 90%;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .class-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .class-table th, .class-table td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        .class-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .class-table td {
            background-color: #fff;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
            margin-top: 15px;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            display: block;
            border-radius: 5px;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        footer {
            background-color: #353a3f;
            color: white;
            padding: 30px 50px;
            text-align: center;
            width: 100%;
            margin-top: auto;
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
                    <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                    <li><a href="manage_members.php">Manage Members</a></li>
                    <li><a href="manage_appointments.php">Manage Appointments</a></li>
                    <li><a href="manage_products.php">Manage Products</a></li>
                    <li><a href="admin_class_schedule.php">Manage Online Classes</a></li>
                    <li><a href="manage_news.php">Manage News</a></li>
                    <li><a href="manage_orders.php">Manage Orders</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <h2>Class Schedule</h2>

        <table class="class-table">
            <thead>
                <tr>
                    <th>Class Name</th>
                    <th>Instructor Name</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $class): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($class['class_name']); ?></td>
                        <td><?php echo htmlspecialchars($class['instructor_name']); ?></td>
                        <td><?php echo htmlspecialchars($class['class_date']); ?></td>
                        <td><?php echo htmlspecialchars($class['class_time']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
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
