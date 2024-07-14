<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Database connection
include('db_connect.php');

$username = $_SESSION['username'];

// Fetch member details
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();

// Update member details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];

    $profile_picture = $member['profile_picture']; // Keep the current profile picture by default

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/profile_pictures/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
        $profile_picture = basename($_FILES["profile_picture"]["name"]);
    }

    $update_stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, age = ?, address = ?, phone = ?, email = ?, gender = ?, profile_picture = ? WHERE username = ?");
    $update_stmt->bind_param("ssissssss", $first_name, $last_name, $age, $address, $phone, $email, $gender, $profile_picture, $username);
    $update_stmt->execute();

    // Refresh member details
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();

    $success_message = "Profile updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <style>
        body {
            background-image: url('assets/img/background5.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: 'Roboto', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
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
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .nav-bar nav ul li a:hover,
        .nav-bar nav ul li a:focus {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            display: flex;
            overflow: hidden;
            margin-bottom: 60px; 
            width: 90%;
            max-width: 1200px;
            margin: 100px auto 0;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .left-panel {
            background-color: #fff;
            text-align: center;
            padding: 30px;
            border-right: 1px solid #ddd;
            flex: 1;
        }

        .left-panel img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }

        .left-panel h2 {
            font-size: 1.5em;
            font-weight: bold;
        }

        .left-panel p {
            color: #888;
            font-size: 1em;
        }

        .form-section {
            padding: 30px;
            flex: 2;
            margin-bottom: 20px; 
        }

        .form-section h3 {
            margin-bottom: 20px;
            font-size: 1.5em;
            font-weight: bold;
        }

        .form-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .form-group div {
            flex: 1;
            margin: 0 10px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1em;
        }

        .form-section .btn {
            background-color: #28a745;
            color: #fff;
            padding: 20px; 
            margin: 0 auto; 
            border: none;
            font-size: 1.2em;
            border-radius: 5px;
            margin-left: 15px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 96%; 
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .form-section .btn:hover {
            background-color: #218838;
        }

        .profile-picture-upload {
            width: 70%;
            padding: 20px;
            border: 2px dashed #ddd;
            text-align: center;
            margin-bottom: 20px;
            margin-left: 15px;
            transition: all 0.3s ease;
        }

        .profile-picture-upload:hover {
            border-color: #6a1b9a;
            background-color: rgba(106, 27, 154, 0.1);
        }

        .profile-picture-upload input {
            width: 100%;
        }

        .success-message {
            color: green;
            font-size: 1em;
            margin-bottom: 10px;
        }

        .right-panel {
            padding: 30px;
            flex: 1;
            border-left: 1px solid #ddd;
        }

        .right-panel h3 {
            margin-bottom: 20px;
            font-size: 1.5em;
            font-weight: bold;
        }

        .right-panel .form-group {
            margin-bottom: 20px;
        }

        .right-panel .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .right-panel .form-group input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1em;
        }

        .dashboard-btn {
            background-color: gray; 
            color: #fff;
            padding: 20px 20px; 
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: block;
            text-align: center;
            font-size: 18px;
            margin-top: 10px; 
            margin-left: 30px; 
            text-decoration: none; 
            width: 650px; 
            margin: 0 auto; 
        }

        .dashboard-btn:hover {
            background-color: #505050; 
        }

        /* New Section: Footer */
        footer {
            background-color: #353a3f;
            color: white;
            padding: 30px 0;
            text-align: center;
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
            margin: 10px 10px;
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

        .loader {
            position: fixed;
            left: 50%;
            top: 50%;
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 3s linear infinite;
            z-index: 1001;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<div class="loader" id="loader"></div>

<header>
    <div class="nav-bar">
        <nav>
            <ul>
                <li><a href="member_dashboard.php">Member Dashboard</a></li>
                <li><a href="view_profile.php">View Profile</a></li>
                <li><a href="book_appointment.php">Book Appointment</a></li>
                <li><a href="manage_appointments.php">Manage Appointments</a></li>
                <li><a href="view_class_schedule.php">View Class Schedule</a></li>
                <li><a href="view_class_schedule.php">View Online Class Recording</a></li>
                <li><a href="pay_class_fees.php">Pay Class Fees</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="container" id="content">
    <div class="left-panel">
        <img src="uploads/profile_pictures/<?php echo htmlspecialchars($member['profile_picture']); ?>" alt="Profile Picture">
        <h2><?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></h2>
        <p><?php echo htmlspecialchars($member['email']); ?></p>
    </div>
    <div class="form-section">
        <?php if (isset($success_message)): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <h3>Profile Settings</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <div>
                    <label for="first_name">First Name:</label>
                    <input type="text" name="first_name" value="<?php echo htmlspecialchars($member['first_name']); ?>" placeholder="First Name" required>
                </div>
                <div>
                    <label for="last_name">Last Name:</label>
                    <input type="text" name="last_name" value="<?php echo htmlspecialchars($member['last_name']); ?>" placeholder="Last Name" required>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <label for="age">Age:</label>
                    <input type="number" name="age" value="<?php echo htmlspecialchars($member['age']); ?>" placeholder="Age" required>
                </div>
                <div>
                    <label for="address">Address:</label>
                    <input type="text" name="address" value="<?php echo htmlspecialchars($member['address']); ?>" placeholder="Address" required>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <label for="phone">Phone:</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($member['phone']); ?>" placeholder="Phone" required>
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($member['email']); ?>" placeholder="Email" required>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <label for="gender">Gender:</label>
                    <select name="gender" required>
                        <option value="Male" <?php if ($member['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if ($member['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                        <option value="Other" <?php if ($member['gender'] == 'Other') echo 'selected'; ?>>Other</option>
                    </select>
                </div>
                <div>
                    <label for="username">Username:</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($member['username']); ?>" placeholder="Username" readonly>
                </div>
            </div>
            <label for="password">&nbsp;&nbsp;&nbsp;Password:</label>
            <input type="password" name="password" value="<?php echo htmlspecialchars($member['password']); ?>" placeholder="Password" readonly>
            <br><br>
            <div class="profile-picture-upload">
                <label for="profile_picture">Upload Profile Picture</label>
                <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
            </div>
            <input type="submit" name="update_profile" value="Update Profile" class="btn" style="margin-bottom: 20px;">
        </form>
        <a href="member_dashboard.php" class="dashboard-btn">Back to Dashboard</a>
    </div>
</div><br><br>

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
    window.onload = function() {
        document.getElementById('loader').style.display = 'none';
        document.getElementById('content').style.opacity = '1';
    }
</script>

</body>
</html>
