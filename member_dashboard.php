<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include('db_connect.php');

// Fetch user details from the database
$username = $_SESSION['username'];
$query = $conn->prepare("SELECT id, username, email FROM users WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$query->bind_result($userId, $username, $email);
$query->fetch();
$query->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('assets/img/background5.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            width: 100%;
        }

        .dashboard-container {
            background-color: rgba(255, 255, 255, 0.8); /* White background with reduced opacity */
            color: #333;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 90%;
            max-width: 700px;
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .dashboard-container h2 {
            margin-bottom: 30px;
            font-size: 2.5em;
            font-weight: 700;
            text-align: center;
            color: #333;
        }

        .dashboard-options {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .dashboard-options .member-options {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            width: 100%;
        }

        .dashboard-options .member-options div {
            flex: 1 1 calc(45% - 20px);
            background-color: #f9f9f9;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
            margin-bottom: 20px;
        }

        .dashboard-options .member-options div:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .dashboard-options .member-options div button {
            width: 100%;
            padding: 15px;
            background-color: #007bff; /* Classic blue color */
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1.2em;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .dashboard-options .member-options div button:hover {
            background-color: #0056b3; /* Darker blue color for hover effect */
            transform: translateY(-3px);
        }

        .logout-button {
            width: 100%;
            padding: 15px;
            background-color: #dc3545; /* Classic red color */
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1.3em;
            margin-top: 30px;
            transition: background-color 0.3s ease;
        }

        .logout-button:hover {
            background-color: #c82333; /* Darker red color for hover effect */
        }

        @media (max-width: 768px) {
            .dashboard-options .member-options div {
                flex: 1 1 calc(100% - 20px);
            }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <div class="dashboard-options">
            <div class="member-options">
                <div><button onclick="window.location.href='view_profile.php'">View Profile</button></div>
                <div><button onclick="window.location.href='book_appointment.php'">Book Appointment</button></div>
                <div><button onclick="window.location.href='manage_appointment_member.php'">Manage Appointments</button></div>
                <div><button onclick="window.location.href='view_class_schedule.php'">View Class Schedule</button></div>
                <div><button onclick="window.location.href='view_online_class_recording.php'">View Online Class Recording</button></div>
                <div><button onclick="window.location.href='pay_class_fees.php'">Pay Class Fees</button></div>
            </div>
            <button class="logout-button" onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </div>
</div>
</body>
</html>
