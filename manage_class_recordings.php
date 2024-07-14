<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header('Location: login.php');
    exit;
}

include('db_connect.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addRecording'])) {
    $className = $_POST['className'];
    $instructorName = $_POST['instructorName'];
    $dateTime = $_POST['dateTime'];
    $recordingLink = $_POST['recordingLink'];

    $stmt = $conn->prepare("INSERT INTO class_recordings (class_name, instructor_name, date_time, recording_link) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $className, $instructorName, $dateTime, $recordingLink);

    if ($stmt->execute()) {
        $feedback = "Class recording added successfully!";
    } else {
        $feedback = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle delete recording
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM class_recordings WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $feedback = "Class recording deleted successfully!";
    } else {
        $feedback = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch class recordings
$recordings = [];
$result = $conn->query("SELECT * FROM class_recordings");
while ($row = $result->fetch_assoc()) {
    $recordings[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Class Recordings</title>
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
            margin-bottom: 100px;
            margin-left: 390px;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 800px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        form {
            margin: 20px 0;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
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

        .delete-button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .delete-button:hover {
            background-color: #c82333;
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
                <li><a href="manage_class_recordings.php">Manage Recordings</a></li>
                <li><a href="manage_class_fees.php">Manage Fees</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="container">
    <h2>Manage Class Recordings</h2>
    <?php if (isset($feedback)): ?>
        <p><?php echo $feedback; ?></p>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label for="className">Class Name</label>
            <input type="text" id="className" name="className" required>
        </div>
        <div class="form-group">
            <label for="instructorName">Instructor Name</label>
            <input type="text" id="instructorName" name="instructorName" required>
        </div>
        <div class="form-group">
            <label for="dateTime">Date & Time</label>
            <input type="datetime-local" id="dateTime" name="dateTime" required>
        </div>
        <div class="form-group">
            <label for="recordingLink">Recording Link</label>
            <input type="url" id="recordingLink" name="recordingLink" required>
        </div>
        <button type="submit" name="addRecording">Add Recording</button>
    </form>
    <h3>Class Recordings</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Class Name</th>
                <th>Instructor Name</th>
                <th>Date & Time</th>
                <th>Recording Link</th>
                <th>Actions</th>
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
                <td>
                    <a href="manage_class_recordings.php?delete_id=<?php echo $recording['id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this recording?')">Delete</a>
                </td>
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
