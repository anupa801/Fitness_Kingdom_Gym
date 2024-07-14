<?php
session_start();
include('db_connect.php');

// Fetch user ID from session (make sure to set user_id in the session when the user logs in)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; // Replace with actual user ID

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payFees'])) {
    $workoutType = $_POST['workoutType'];
    $duration = $_POST['duration'];
    $totalAmount = $_POST['totalAmount'];
    $paymentMethod = $_POST['paymentMethod'];

    if ($paymentMethod === 'online') {
        $cardNumber = htmlspecialchars($_POST['cardNumber']);
        $cardExpiry = htmlspecialchars($_POST['cardExpiry']);
        $cardCVC = htmlspecialchars($_POST['cardCVC']);
        $receipt = null;
    } else {
        $cardNumber = null;
        $cardExpiry = null;
        $cardCVC = null;
        $receipt = $_FILES['receipt']['name'];

        // Upload receipt
        if ($receipt) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["receipt"]["name"]);
            move_uploaded_file($_FILES["receipt"]["tmp_name"], $target_file);
        }
    }

    $stmt = $conn->prepare("INSERT INTO class_fees_payments (user_id, workout_type, duration, total_amount, payment_method, card_number, card_expiry, card_cvc, receipt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdsssss", $user_id, $workoutType, $duration, $totalAmount, $paymentMethod, $cardNumber, $cardExpiry, $cardCVC, $receipt);

    if ($stmt->execute()) {
        $feedback = "Payment successful!";
    } else {
        $feedback = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Class Fees - Fitness Kingdom Gym</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('assets/img/payfees.jpg') ;
            background-size: cover;
            color: white;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 90px auto 80px auto;
            padding: 20px;
            background-color: rgba(53, 58, 63, 0.9);
            border-radius: 10px;
        }

        h2 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        select, input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            color: #333;
        }

        .total {
            font-size: 20px;
            margin-top: 20px;
            text-align: center;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 20px;
            cursor: pointer;
            border-radius: 15px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .payment-options {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .payment-options input[type="radio"] {
            margin-right: 10px;
        }

        header {
            background-color: #353a3f;
            color: white;
            padding: 10px 0;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
            position: fixed;
            top: 0;
            width: 100%;
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
            margin-left: 25px;
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
    </style>
    <script>
        function calculateTotal() {
            const workoutType = document.getElementById('workoutType').value;
            const duration = document.getElementById('duration').value;
            let monthlyAmount = 0;

            switch (workoutType) {
                case 'physical':
                    monthlyAmount = 3000;
                    break;
                case 'home':
                    monthlyAmount = 10000;
                    break;
                case 'online':
                    monthlyAmount = 2500;
                    break;
            }

            let totalAmount = monthlyAmount;
            if (duration == '6') {
                totalAmount = monthlyAmount * 6 * 0.75;
            } else if (duration == '12') {
                totalAmount = monthlyAmount * 12 * 0.75;
            } else {
                totalAmount = monthlyAmount;
            }

            document.getElementById('totalAmount').textContent = `Total Amount: LKR ${totalAmount.toFixed(2)}`;
            document.getElementById('totalAmountInput').value = totalAmount.toFixed(2);
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('workoutType').addEventListener('change', calculateTotal);
            document.getElementById('duration').addEventListener('change', calculateTotal);
            document.getElementById('paymentForm').addEventListener('submit', function(e) {
                const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
                if (paymentMethod === 'online') {
                    const cardNumber = document.getElementById('cardNumber').value;
                    const cardExpiry = document.getElementById('cardExpiry').value;
                    const cardCVC = document.getElementById('cardCVC').value;
                    if (!cardNumber || !cardExpiry || !cardCVC) {
                        alert('Please fill in all the card details.');
                        e.preventDefault();
                    }
                } else {
                    const receipt = document.getElementById('receipt').files[0];
                    if (!receipt) {
                        alert('Please upload the receipt.');
                        e.preventDefault();
                    }
                }
            });
        });
    </script>
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
    <h2>Pay Class Fees</h2>
    <?php if (isset($feedback)): ?>
        <p><?php echo $feedback; ?></p>
    <?php endif; ?>
    <form id="paymentForm" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="workoutType">Select Workout Type:</label>
            <select id="workoutType" name="workoutType" required>
                <option value="" disabled selected>Select an option</option>
                <option value="physical">Physical Workout - LKR 3000</option>
                <option value="home">Home Visit Workout - LKR 10000</option>
                <option value="online">Online Workout - LKR 2500</option>
            </select>
        </div>
        <div class="form-group">
            <label for="duration">Select Duration:</label>
            <select id="duration" name="duration" required>
                <option value="" disabled selected>Select an option</option>
                <option value="1">1 Month</option>
                <option value="6">6 Months</option>
                <option value="12">12 Months</option>
            </select>
        </div>
        <div class="total" id="totalAmount">Total Amount: LKR 0.00</div>
        <input type="hidden" id="totalAmountInput" name="totalAmount">
        <div class="form-group">
            <label>Payment Method:</label>
            <div class="payment-options">
                <label><input type="radio" name="paymentMethod" value="online" required> Pay Online</label>
                <label><input type="radio" name="paymentMethod" value="offline" required> Upload Receipt</label>
            </div>
        </div>
        <div id="onlinePayment" style="display: none;">
            <div class="form-group">
                <label for="cardNumber">Card Number:</label>
                <input type="text" id="cardNumber" name="cardNumber" pattern="\d{16}" placeholder="Enter 16-digit card number">
            </div>
            <div class="form-group">
                <label for="cardExpiry">Expiry Date:</label>
                <input type="text" id="cardExpiry" name="cardExpiry" pattern="\d{2}/\d{2}" placeholder="MM/YY">
            </div>
            <div class="form-group">
                <label for="cardCVC">CVC:</label>
                <input type="text" id="cardCVC" name="cardCVC" pattern="\d{3}" placeholder="Enter 3-digit CVC">
            </div>
        </div>
        <div id="offlinePayment" style="display: none;">
            <div class="form-group">
                <label for="receipt">Upload Receipt:</label>
                <input type="file" id="receipt" name="receipt" accept="image/*">
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" name="payFees">Pay Fees</button>
        </div>
    </form>
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
    document.querySelectorAll('input[name="paymentMethod"]').forEach(input => {
        input.addEventListener('change', function() {
            if (this.value === 'online') {
                document.getElementById('onlinePayment').style.display = 'block';
                document.getElementById('offlinePayment').style.display = 'none';
            } else {
                document.getElementById('onlinePayment').style.display = 'none';
                document.getElementById('offlinePayment').style.display = 'block';
            }
        });
    });
</script>
</body>
</html>
