<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Delete appointments that have passed
$current_date = date('Y-m-d');
$current_time = date('H:i:s');
$delete_past_appointments = $conn->prepare("DELETE FROM appointments WHERE appointment_date < ? OR (appointment_date = ? AND appointment_time < ?)");
$delete_past_appointments->bind_param("sss", $current_date, $current_date, $current_time);
$delete_past_appointments->execute();

// Fetch appointments for the logged-in user
$appointments_query = $conn->prepare("SELECT appointments.*, coaches.name AS coach_name FROM appointments JOIN coaches ON appointments.coach_id = coaches.id WHERE username = ? ORDER BY appointment_date, appointment_time");
$appointments_query->bind_param("s", $username);
$appointments_query->execute();
$appointments = $appointments_query->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch coaches for the update form
$coaches_query = $conn->query("SELECT * FROM coaches");
$coaches = $coaches_query->fetch_all(MYSQLI_ASSOC);

// Generate time slots
$time_slots = [];
for ($hour = 6; $hour < 23; $hour++) {
    if ($hour != 12) { // Exclude 12 PM to 1 PM
        $start_time = sprintf('%02d:00', $hour);
        $time_slots[] = $start_time;
    }
}

// Handle update appointment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $coach_id = $_POST['coach_id'];

    $update_query = $conn->prepare("UPDATE appointments SET appointment_date = ?, appointment_time = ?, coach_id = ? WHERE id = ?");
    $update_query->bind_param("ssii", $appointment_date, $appointment_time, $coach_id, $appointment_id);
    if ($update_query->execute()) {
        $success_message = "Appointment updated successfully!";
    } else {
        $error_message = "Error: " . $update_query->error;
    }
}

// Handle delete appointment
if (isset($_GET['delete'])) {
    $appointment_id = $_GET['delete'];
    $delete_query = $conn->prepare("DELETE FROM appointments WHERE id = ?");
    $delete_query->bind_param("i", $appointment_id);
    $delete_query->execute();
    header("Location: manage_appointment_member.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('assets/img/background6.jpg'); 
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

        input[type="date"],
        select,
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

        .appointment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .appointment-table th, .appointment-table td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        .appointment-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .appointment-table td {
            background-color: #fff;
        }

        .appointment-table .btn-container {
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

        .update-form {
            display: none;
            flex-direction: column;
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 20px;
            width: 100%;
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
        <nav>
            <ul>
                <li><a href="member_dashboard.php">Member Dashboard</a></li>
                <li><a href="view_profile.php">View Profile</a></li>
                <li><a href="book_appointment.php">Book Appointment</a></li>
                <li><a href="manage_appointments.php">Manage Appointments</a></li>
                <li><a href="view_class_schedule.php">View Class Schedule</a></li>
                <li><a href="view_class_schedule.php">View Online Class Recording</a></li>
                <li><a href="pay_class_fees.php" class="active">Pay Class Fees</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>
    <div class="container">
        <h2>Manage Appointments</h2>
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <table class="appointment-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Coach</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['coach_name']); ?></td>
                        <td class="btn-container">
                            <button class="btn" onclick="showUpdateForm(<?php echo $appointment['id']; ?>)">Update</button>
                            <a href="manage_appointment_member.php?delete=<?php echo $appointment['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this appointment?');">Delete</a>
                        </td>
                    </tr>
                    <tr id="update-form-<?php echo $appointment['id']; ?>" class="update-form">
                        <td colspan="4">
                            <form action="manage_appointment_member.php" method="post">
                                <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                <div class="form-group">
                                    <label for="appointment_date">Appointment Date:</label>
                                    <input type="date" name="appointment_date" value="<?php echo htmlspecialchars($appointment['appointment_date']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="appointment_time">Appointment Time:</label>
                                    <select name="appointment_time" required>
                                        <?php foreach ($time_slots as $slot): ?>
                                            <option value="<?php echo $slot; ?>" <?php if ($slot == $appointment['appointment_time']) echo 'selected'; ?>><?php echo $slot; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="coach_id">Select Coach:</label>
                                    <select name="coach_id" required>
                                        <?php foreach ($coaches as $coach): ?>
                                            <option value="<?php echo $coach['id']; ?>" <?php if ($coach['id'] == $appointment['coach_id']) echo 'selected'; ?>><?php echo $coach['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" name="update_appointment" class="btn">Update Appointment</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="member_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
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
        function showUpdateForm(id) {
            document.querySelectorAll('.update-form').forEach(form => form.style.display = 'none');
            document.getElementById('update-form-' + id).style.display = 'table-row';
        }
    </script>
</body>
</html>
