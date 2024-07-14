<?php
session_start();
include('db_connect.php');

// Fetch all appointments
$appointments_query = $conn->query("SELECT appointments.*, coaches.name AS coach_name FROM appointments JOIN coaches ON appointments.coach_id = coaches.id ORDER BY appointment_date, appointment_time");
$appointments = $appointments_query->fetch_all(MYSQLI_ASSOC);

// Handle delete appointment
if (isset($_GET['delete'])) {
    $appointment_id = $_GET['delete'];
    $delete_query = $conn->prepare("DELETE FROM appointments WHERE id = ?");
    $delete_query->bind_param("i", $appointment_id);
    $delete_query->execute();
    header("Location: manage_appointments_admin.php");
    exit();
}

// Handle search appointments by member ID
if (isset($_GET['search_member_id'])) {
    $member_id = $_GET['search_member_id'];
    $search_query = $conn->prepare("SELECT appointments.*, coaches.name AS coach_name FROM appointments JOIN coaches ON appointments.coach_id = coaches.id WHERE username = ? ORDER BY appointment_date, appointment_time");
    $search_query->bind_param("s", $member_id);
    $search_query->execute();
    $search_results = $search_query->get_result()->fetch_all(MYSQLI_ASSOC);
} else {
    $search_results = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments - Admin</title>
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
            margin-left: 280px;
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
            text-align: center; /* Center the form group */
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="date"],
        select,
        .btn,
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            text-align: center; /* Center text in input fields */
            max-width: 400px; /* Limit the width of input fields */
            margin: 0 auto; /* Center input fields */
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
            width: 100%; 
            max-width: 980px; 
            margin: 15px auto; 
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
            width: 100%; 
            max-width: 980px; 
            margin: 15px auto; 
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

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            z-index: 2000;
            max-width: 90%;
            max-height: 80%;
            overflow: auto;
        }

        .popup.active {
            display: block;
        }

        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .popup-close {
            cursor: pointer;
            font-size: 1.5em;
            background: none;
            border: none;
            color: #333;
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
        <h2>Manage Appointments - Admin</h2>
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="form-group">
            <label for="search_member_id">Search Appointments by Member ID:</label>
            <input type="text" id="search_member_id" name="search_member_id" placeholder="Enter Member ID">
            <button class="btn" onclick="searchAppointments()">Search</button>
        </div>

        <table class="appointment-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Coach</th>
                    <th>Member</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['coach_name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['username']); ?></td>
                        <td class="btn-container">
                            <a href="manage_appointments_admin.php?delete=<?php echo $appointment['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this appointment?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <a href="logout.php" class="btn btn-logout">Logout</a>
        <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        
    </div>

    <div id="searchResultsPopup" class="popup">
        <div class="popup-header">
            <h2>Search Results</h2>
            <button class="popup-close" onclick="closePopup()">&times;</button>
        </div>
        <table class="appointment-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Coach</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="searchResultsBody">
                <!-- Search results will be inserted here -->
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

    <script>
        function searchAppointments() {
            const memberId = document.getElementById('search_member_id').value;
            if (memberId) {
                fetch(`search_appointments.php?member_id=${memberId}`)
                    .then(response => response.json())
                    .then(data => {
                        const searchResultsBody = document.getElementById('searchResultsBody');
                        searchResultsBody.innerHTML = '';
                        data.forEach(appointment => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${appointment.appointment_date}</td>
                                <td>${appointment.appointment_time}</td>
                                <td>${appointment.coach_name}</td>
                                <td class="btn-container">
                                    <a href="manage_appointments_admin.php?delete=${appointment.id}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this appointment?');">Delete</a>
                                </td>
                            `;
                            searchResultsBody.appendChild(row);
                        });
                        document.getElementById('searchResultsPopup').classList.add('active');
                    });
            }
        }

        function closePopup() {
            document.getElementById('searchResultsPopup').classList.remove('active');
        }
    </script>
</body>
</html>
