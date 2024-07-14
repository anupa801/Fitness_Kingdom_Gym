<?php
session_start();
include('db_connect.php');

// Fetch class recordings
$recordings = [];
$result = $conn->query("SELECT * FROM class_recordings ORDER BY date_time ASC");
while ($row = $result->fetch_assoc()) {
    $recordings[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Online Class Recordings</title>
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
            opacity: 0;
            animation: fadeIn 1s forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        header, footer {
            position: relative;
            z-index: 2;
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
            margin-top: 100px;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 1200px;
            margin: 100px auto 20px;
            flex: 1;
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
                <li><a href="index.php">Home</a></li>
                <li><a href="about_us.php">About Us</a></li>
                <li><a href="pricing.php">Pricing</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="news.php">News</a></li>
                <li><a href="contact_us.php">Contact Us</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="container">
    <h2>Online Class Recordings</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Class Name</th>
                <th>Instructor Name</th>
                <th>Date & Time</th>
                <th>Recording Link</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recordings as $recording): ?>
            <tr>
                <td><?php echo htmlspecialchars($recording['id']); ?></td>
                <td><?php echo htmlspecialchars($recording['class_name']); ?></td>
                <td><?php echo htmlspecialchars($recording['instructor_name']); ?></td>
                <td><?php echo htmlspecialchars($recording['date_time']); ?></td>
                <td><a href="<?php echo htmlspecialchars($recording['recording_link']); ?>" target="_blank">View Recording</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<footer>
    <div class="footer-content">
        <div>
            <h2>About Us</h2>
            <p>Fitness Kingdom is your ultimate destination for achieving your fitness goals. Our certified trainers and state-of-the-art facilities ensure you get the best out of your workouts.</p>
        </div>
        <div>
            <h2>Quick Links</h2>
            <p><a href="index.php">Home</a></p>
            <p><a href="about_us.php">About Us</a></p>
            <p><a href="pricing.php">Pricing</a></p>
            <p><a href="products.php">Products</a></p>
            <p><a href="news.php">News</a></p>
            <p><a href="login.php">Login</a></p>
            <p><a href="contact_us.php">Contact Us</a></p>
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
