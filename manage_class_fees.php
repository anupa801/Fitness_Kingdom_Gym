<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header('Location: login.php');
    exit;
}

include('db_connect.php');

// Fetch members and their payment statuses
$members = [];
$result = $conn->query("
    SELECT u.id, u.username, u.first_name, u.last_name, cfp.workout_type, cfp.duration, cfp.total_amount, cfp.payment_method, cfp.date
    FROM users u
    LEFT JOIN class_fees_payments cfp ON u.id = cfp.user_id
");
while ($row = $result->fetch_assoc()) {
    $members[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Member Class Fees</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('assets/img/background5.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
            z-index: -1;
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
            flex-wrap: wrap;
        }

        .nav-bar nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .nav-bar nav ul li {
            margin-left: 6px;
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

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 1200px;
            margin: 100px auto 20px;
        }

        .logout-button, .back-dashboard-button {
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

        .back-dashboard-button {
            background-color: #6c757d; /* Gray color */
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

        .back-dashboard-button:hover {
            background-color: #5a6268;
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
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                <li><a href="manage_members.php">Manage Members</a></li>
                <li><a href="manage_appointments_admin.php">Manage Appointments</a></li>
                <li><a href="manage_products.php">Manage Products</a></li>
                <li><a href="admin_class_schedule.php">Manage Classes</a></li>
                <li><a href="manage_news.php">Manage News</a></li>
                <li><a href="manage_orders.php">Manage Orders</a></li>
                <li><a href="manage_messages.php">Manage Messages</a></li>
                <li><a href="manage_class_recordings.php">Manage Recording</a></li>
                <li><a href="manage_class_fees.php">Manage Fees</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="container">
    <h2>Manage Member Class Fees</h2>
    <table>
        <thead>
            <tr>
                <th>Member ID</th>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Workout Type</th>
                <th>Duration</th>
                <th>Total Amount</th>
                <th>Payment Method</th>
                <th>Payment Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($members as $member): ?>
            <tr>
                <td><?php echo htmlspecialchars($member['id']); ?></td>
                <td><?php echo htmlspecialchars($member['username']); ?></td>
                <td><?php echo htmlspecialchars($member['first_name']); ?></td>
                <td><?php echo htmlspecialchars($member['last_name']); ?></td>
                <td><?php echo htmlspecialchars($member['workout_type']); ?></td>
                <td><?php echo htmlspecialchars($member['duration']); ?></td>
                <td><?php echo htmlspecialchars($member['total_amount']); ?></td>
                <td><?php echo htmlspecialchars($member['payment_method']); ?></td>
                <td><?php echo htmlspecialchars($member['date']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button class="logout-button" onclick="window.location.href='logout.php'">Logout</button>
    <button class="back-dashboard-button" onclick="window.location.href='admin_dashboard.php'">Back to Dashboard</button>
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
