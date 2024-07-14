<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Database connection
include('db_connect.php');

// Handle update order status
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    if ($stmt->execute()) {
        $success_message = "Order status updated successfully.";
    } else {
        $error_message = "Error updating order status: " . $stmt->error;
    }
    $stmt->close();
}

// Handle delete order
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn->query("DELETE FROM orders WHERE id = $delete_id");
    header("Location: manage_orders.php");
    exit;
}

// Fetch all orders
$orders = [];
$result = $conn->query("SELECT o.*, oi.product_id, p.name as product_name, p.price FROM orders o 
    JOIN order_items oi ON o.id = oi.order_id 
    JOIN products p ON oi.product_id = p.id 
    ORDER BY o.order_date DESC");
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
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
            background-color: rgba(255, 255, 255, 0.9);
            color: #333;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
            width: 90%;
            max-width: 1200px;
            margin-top: 130px;
            margin-bottom: 80px;
            margin-left: 220px;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 2em;
            font-weight: 600;
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

        .action-buttons form,
        .action-buttons a {
            display: inline-block;
            margin: 0 5px;
        }

        .action-buttons button {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .action-buttons button:hover {
            background-color: #0056b3;
        }

        .action-buttons .delete-button {
            background-color: #dc3545;
            padding: 8px 12px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
        }

        .action-buttons .delete-button:hover {
            background-color: #c82333;
        }

        .update-status-button {
            margin-top: 10px;
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .update-status-button:hover {
            background-color: #218838;
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
    <h2>Manage Orders</h2>
    <?php if (isset($success_message)): ?>
        <div style="color: green;"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
        <div style="color: red;"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Order Date</th>
            <th>Product</th>
            <th>Price</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo $order['customer_name']; ?></td>
                <td><?php echo $order['address']; ?></td>
                <td><?php echo $order['phone']; ?></td>
                <td><?php echo $order['order_date']; ?></td>
                <td><?php echo $order['product_name']; ?></td>
                <td><?php echo $order['price']; ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <select name="status">
                            <option value="Pending" <?php if ($order['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                            <option value="Shipped" <?php if ($order['status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                            <option value="Delivered" <?php if ($order['status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                        </select>
                        <button type="submit" name="update_status" class="update-status-button">Update</button>
                    </form>
                </td>
                <td class="action-buttons">
                    <a href="manage_orders.php?delete_id=<?php echo $order['id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this order?')">Delete</a>
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
