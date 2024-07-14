<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $coach_id = $_POST['coach_id'];

    // Check if the coach is available for the selected time slot
    $check_query = $conn->prepare("SELECT * FROM appointments WHERE appointment_date = ? AND appointment_time = ? AND coach_id = ?");
    $check_query->bind_param("ssi", $appointment_date, $appointment_time, $coach_id);
    $check_query->execute();
    $result = $check_query->get_result();
    
    if ($result->num_rows > 0) {
        $error_message = "The selected coach is not available for this time slot.";
    } else {
        $query = $conn->prepare("INSERT INTO appointments (username, appointment_date, appointment_time, coach_id) VALUES (?, ?, ?, ?)");
        $query->bind_param("sssi", $username, $appointment_date, $appointment_time, $coach_id);
        
        if ($query->execute()) {
            $success_message = "Appointment booked successfully!";
        } else {
            $error_message = "Error: " . $query->error;
        }
    }
}

// Fetch coaches from the database
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('assets/img/background7.jpg'); 
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
            max-width: 600px;
            background: rgba(255, 255, 255, 0.9);
            padding: 40px 40px;
            margin-right: 60px;
            margin-top: 100px;
            margin-bottom: 40px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            animation: fadeIn 1s ease-in-out;
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
            background: #28a748;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
            margin-top: 15px;
            padding: 10px;
            width: 97%;
            text-align: center;
            text-decoration: none;
            display: block;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .time-slots {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .time-slot {
            padding: 10px;
            background: #007bff;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            flex: 1 0 30%;
        }

        .time-slot.unavailable {
            background: #dc3545;
            cursor: not-allowed;
        }

        .time-slot.selected {
            background: #28a745;
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

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
       /* New Section: Footer */
       footer {
            background-color: #353a3f;
            color: white;
            padding: 30px 50px;
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
        <h2>Book Appointment</h2>
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="book_appointment.php" method="post">
            <div class="form-group">
                <label for="appointment_date">Appointment Date:</label>
                <input type="date" id="appointment_date" name="appointment_date" required>
            </div>
            
            <div class="form-group">
                <label for="coach_id">Select Coach:</label>
                <select id="coach_id" name="coach_id" required>
                    <option value="">Choose a coach</option>
                    <?php foreach ($coaches as $coach): ?>
                        <option value="<?php echo $coach['id']; ?>"><?php echo $coach['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="appointment_time">Select Time Slot:</label>
                <div class="time-slots">
                    <?php foreach ($time_slots as $slot): ?>
                        <div class="time-slot" data-time="<?php echo $slot; ?>"><?php echo $slot; ?></div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" id="appointment_time" name="appointment_time" required>
            </div>
            
            <button type="submit" class="btn">Book Appointment</button>
        </form>
        <a href="member_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <script>
        document.querySelectorAll('.time-slot').forEach(slot => {
            slot.addEventListener('click', function() {
                if (!this.classList.contains('unavailable')) {
                    document.getElementById('appointment_time').value = this.getAttribute('data-time');
                    document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
                    this.classList.add('selected');
                }
            });
        });
    </script>
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
