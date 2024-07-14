<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Handle form submission for scheduling a class
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_name = $_POST['class_name'];
    $instructor_name = $_POST['instructor_name'];
    $class_date = $_POST['class_date'];
    $class_time = $_POST['class_time'];
    $class_id = $_POST['class_id'] ?? null;

    if ($class_id) {
        // Update existing class
        $query = $conn->prepare("UPDATE class_schedule SET class_name = ?, instructor_name = ?, class_date = ?, class_time = ? WHERE id = ?");
        $query->bind_param("ssssi", $class_name, $instructor_name, $class_date, $class_time, $class_id);
    } else {
        // Schedule a new class
        $query = $conn->prepare("INSERT INTO class_schedule (class_name, instructor_name, class_date, class_time) VALUES (?, ?, ?, ?)");
        $query->bind_param("ssss", $class_name, $instructor_name, $class_date, $class_time);
    }

    if ($query->execute()) {
        $success_message = $class_id ? "Class updated successfully!" : "Class scheduled successfully!";
    } else {
        $error_message = "Error: " . $query->error;
    }
}

// Handle delete class
if (isset($_GET['delete'])) {
    $class_id = $_GET['delete'];
    $delete_query = $conn->prepare("DELETE FROM class_schedule WHERE id = ?");
    $delete_query->bind_param("i", $class_id);
    $delete_query->execute();
    header("Location: admin_class_schedule.php");
    exit();
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
    <title>Admin Class Schedule</title>
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

        .container {
            max-width: 1000px;
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            margin-top: 100px;
            margin-bottom: 40px;
            margin-left: 300px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            animation: fadeIn 1s ease-in-out;
            width: 90%;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        .btn {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .btn {
            background: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background: #218838;
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

        .btn-logout {
            background-color: #dc3545;
            color: #fff;
            margin-top: 15px;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            display: block;
            border-radius: 5px;
        }

        .btn-logout:hover {
            background-color: #c82333;
        }

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
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

        .class-table .btn-container {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        footer {
            background-color: #353a3f;
            color: white;
            padding: 30px 0;
            text-align: center;
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
        <h2>Schedule a Class</h2>
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="admin_class_schedule.php" method="post">
            <input type="hidden" name="class_id" id="class_id">
            <div class="form-group">
                <label for="class_name">Class Name:</label>
                <input type="text" id="class_name" name="class_name" required>
            </div>
            <div class="form-group">
                <label for="instructor_name">Instructor Name:</label>
                <input type="text" id="instructor_name" name="instructor_name" required>
            </div>
            <div class="form-group">
                <label for="class_date">Class Date:</label>
                <input type="date" id="class_date" name="class_date" required>
            </div>
            <div class="form-group">
                <label for="class_time">Class Time:</label>
                <input type="time" id="class_time" name="class_time" required>
            </div>
            <button type="submit" class="btn">Schedule Class</button>
        </form>

        <h2>Scheduled Classes</h2>
        <table class="class-table">
            <thead>
                <tr>
                    <th>Class Name</th>
                    <th>Instructor Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $class): ?>
                    <tr data-class-id="<?php echo $class['id']; ?>">
                        <td class="class-name"><?php echo htmlspecialchars($class['class_name']); ?></td>
                        <td class="instructor-name"><?php echo htmlspecialchars($class['instructor_name']); ?></td>
                        <td class="class-date"><?php echo htmlspecialchars($class['class_date']); ?></td>
                        <td class="class-time"><?php echo htmlspecialchars($class['class_time']); ?></td>
                        <td class="btn-container">
                            <button class="btn" onclick="editClass(<?php echo $class['id']; ?>)">Edit</button>
                            <a href="admin_class_schedule.php?delete=<?php echo $class['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this class?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="logout.php" class="btn btn-logout">Logout</a>
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

    <script>
        function editClass(classId) {
            const classRow = document.querySelector(`[data-class-id="${classId}"]`);
            const className = classRow.querySelector('.class-name').textContent;
            const instructorName = classRow.querySelector('.instructor-name').textContent;
            const classDate = classRow.querySelector('.class-date').textContent;
            const classTime = classRow.querySelector('.class-time').textContent;

            document.getElementById('class_id').value = classId;
            document.getElementById('class_name').value = className;
            document.getElementById('instructor_name').value = instructorName;
            document.getElementById('class_date').value = classDate;
            document.getElementById('class_time').value = classTime;
        }
    </script>
</body>
</html>
