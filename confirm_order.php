<?php
session_start();

if (!isset($_SESSION['order'])) {
    header('Location: products.php');
    exit;
}

$order = $_SESSION['order'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('db_connect.php');
    
    $customer_name = $order['customer_name'];
    $address = $order['address'];
    $phone = $order['phone'];
    $product_id = $order['product']['id'];
    $quantity = 1; // Assuming one product per order for simplicity

    $stmt = $conn->prepare("INSERT INTO orders (customer_name, address, phone) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $customer_name, $address, $phone);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $order_id, $product_id, $quantity);
    $stmt->execute();

    unset($_SESSION['order']);
    $_SESSION['message'] = "Your Order Successfully Placed";
    header('Location: products.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Order</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('assets/img/background5.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            padding-top: 100px;
        }

        .container {
            width: 90%;
            max-width: 600px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
        }

        h2 {
            text-align: center;
        }

        .order-details {
            margin-bottom: 20px;
        }

        .order-details h3 {
            margin-bottom: 10px;
        }

        .order-details p {
            margin: 5px 0;
        }

        .confirm-order-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .confirm-order-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Confirm Order</h2>
        <div class="order-details">
            <h3>Product Details</h3>
            <p>Name: <?php echo $order['product']['name']; ?></p>
            <p>Description: <?php echo $order['product']['description']; ?></p>
            <p>Price: LKR <?php echo $order['product']['price']; ?></p>
        </div>
        <div class="order-details">
            <h3>Shipping Details</h3>
            <p>Name: <?php echo $order['customer_name']; ?></p>
            <p>Address: <?php echo $order['address']; ?></p>
            <p>Phone: <?php echo $order['phone']; ?></p>
        </div>
        <form method="post">
            <button type="submit" class="confirm-order-btn">Confirm Order</button>
        </form>
    </div>
</body>
</html>
