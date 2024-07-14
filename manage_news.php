<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Database connection
include('db_connect.php');

// Add news
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_news'])) {
    $news_title = $_POST['news_title'];
    $news_description = $_POST['news_description'];
    $news_image = $_FILES['news_image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["news_image"]["name"]);
    
    if (move_uploaded_file($_FILES["news_image"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO news (news_title, news_description, news_image) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("sss", $news_title, $news_description, $news_image);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch all news
$news_list = [];
$result = $conn->query("SELECT * FROM news");
while ($row = $result->fetch_assoc()) {
    $news_list[] = $row;
}

// Update news
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_news'])) {
    $news_id = $_POST['news_id'];
    $news_title = $_POST['news_title'];
    $news_description = $_POST['news_description'];

    if (!empty($_FILES['news_image']['name'])) {
        $news_image = $_FILES['news_image']['name'];
        $target_file = $target_dir . basename($_FILES["news_image"]["name"]);
        move_uploaded_file($_FILES["news_image"]["tmp_name"], $target_file);

        $stmt = $conn->prepare("UPDATE news SET news_title = ?, news_description = ?, news_image = ? WHERE id = ?");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("sssi", $news_title, $news_description, $news_image, $news_id);
    } else {
        $stmt = $conn->prepare("UPDATE news SET news_title = ?, news_description = ? WHERE id = ?");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("ssi", $news_title, $news_description, $news_id);
    }
    
    $stmt->execute();
    $stmt->close();
}

// Delete news
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn->query("DELETE FROM news WHERE id = $delete_id");
    header("Location: manage_news.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage News</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('assets/img/background5.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
        }

        body {
            color: #333;
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
            background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent white background */
            color: #333;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
            width: 90%;
            max-width: 1200px;
            margin: 100px auto 20px;
            flex: 1; /* Take up remaining space */
        }

        .container h2 {
            margin-bottom: 20px;
            font-size: 2em;
            font-weight: 600;
        }

        .container form {
            margin-bottom: 20px;
        }

        .container input[type="text"], .container textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .container input[type="file"] {
            margin-bottom: 10px;
        }

        .container input[type="submit"] {
            padding: 10px 20px;
            border: none;
            background-color: #0d6efd;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .container input[type="submit"]:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
            text-align: center;
        }

        td {
            text-align: center;
        }

        .action-buttons a {
            background-color: #0d6efd;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            margin: 0 5px;
            display: inline-block;
        }

        .action-buttons a:hover {
            background-color: #0056b3;
        }

        .action-buttons .delete-button {
            background-color: #dc3545;
        }

        .action-buttons .delete-button:hover {
            background-color: #c82333;
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
            padding: 20px 0;
            text-align: center;
            width: 100%;
            margin-top: auto; /* Push footer to the bottom */
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
        <h2>Manage News</h2>

        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="news_title" placeholder="News Title" required>
            <textarea name="news_description" placeholder="News Description" required></textarea>
            <input type="file" name="news_image" accept="image/*" required>
            <input type="submit" name="add_news" value="Add News">
        </form>

        <h3>News List:</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($news_list as $news): ?>
                <tr>
                    <td><?php echo $news['id']; ?></td>
                    <td><?php echo $news['news_title']; ?></td>
                    <td><?php echo $news['news_description']; ?></td>
                    <td><img src="uploads/<?php echo $news['news_image']; ?>" alt="<?php echo $news['news_title']; ?>" width="100"></td>
                    <td class="action-buttons">
                        <a href="update_news.php?id=<?php echo $news['id']; ?>">Update</a>
                        <a href="manage_news.php?delete_id=<?php echo $news['id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this news?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
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
