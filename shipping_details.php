<?php
session_start();
include('db_connect.php');

if (!isset($_GET['product_id'])) {
    header('Location: products.php');
    exit;
}

$product_id = $_GET['product_id'];

// Fetch product details
$product = $conn->query("SELECT * FROM products WHERE id = $product_id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['order'] = [
        'product' => $product,
        'customer_name' => $_POST['customer_name'],
        'address' => $_POST['address'],
        'phone' => $_POST['phone']
    ];
    header('Location: confirm_order.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Details</title>
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

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        input, textarea {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        .product-preview {
            text-align: center;
            margin-bottom: 20px;
        }

        .product-preview img {
            width: 100px;
            height: auto;
            display: block;
            margin: 0 auto 10px;
        }

        .place-order-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .place-order-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Shipping Details</h2>
        <div class="product-preview">
            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
            <h3><?php echo $product['name']; ?></h3>
            <p><?php echo $product['description']; ?></p>
            <span>LKR <?php echo $product['price']; ?></span>
        </div>
        <form method="post">
            <label for="customer_name">Name</label>
            <input type="text" id="customer_name" name="customer_name" required>
            <label for="address">Address</label>
            <textarea id="address" name="address" required></textarea>
            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" required>
            <button type="submit" class="place-order-btn">Place Order</button>
        </form>
    </div>
</body>
</html>
s