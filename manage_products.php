<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Database connection
include('db_connect.php');

// Ensure uploads directory exists
$uploads_dir = 'uploads';
if (!is_dir($uploads_dir)) {
    mkdir($uploads_dir, 0777, true);
}

// Handle add product
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = '';

    if (!empty($_FILES['image']['name'])) {
        $image = $uploads_dir . '/' . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $name, $description, $price, $image);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Failed to upload image.";
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $name, $description, $price);
        $stmt->execute();
        $stmt->close();
    }
}

// Handle update product
if (isset($_POST['update_product'])) {
    $id = $_POST['product_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $current_image = $_POST['current_image'];
    $image = $current_image;

    if (!empty($_FILES['image']['name'])) {
        $image = $uploads_dir . '/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $image, $id);
    $stmt->execute();
    $stmt->close();
}

// Handle delete product
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn->query("DELETE FROM products WHERE id = $delete_id");
    header("Location: manage_products.php");
    exit;
}

// Fetch all products
$products = [];
$result = $conn->query("SELECT * FROM products");
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
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
            background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent white background */
            color: #333;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
            width: 90%;
            max-width: 1200px;
            margin: 100px auto 20px;
        }

        .container h2 {
            margin-bottom: 20px;
            font-size: 2em;
            font-weight: 600;
        }

        .container p {
            margin: 10px 0;
            font-size: 1.3em;
        }

        .container a {
            color: #007bff;
            text-decoration: none;
        }

        .container a:hover {
            text-decoration: underline;
        }

        form {
            margin-bottom: 20px;
        }

        form input[type="text"],
        form input[type="number"],
        form textarea {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        form input[type="file"] {
            margin-bottom: 10px;
        }

        form input[type="submit"] {
            padding: 10px 20px;
            font-size: 1em;
            border: none;
            background-color: #0d6efd;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
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
            padding: 30px 0;
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
    <script>
        function editProduct(product) {
            document.getElementById('product_id').value = product.id;
            document.getElementById('name').value = product.name;
            document.getElementById('description').value = product.description;
            document.getElementById('price').value = product.price;
            document.getElementById('current_image').value = product.image;
            document.getElementById('add_product').style.display = 'none';
            document.getElementById('update_product').style.display = 'block';
        }
    </script>
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
        <h2>Manage Products</h2>

        <form method="POST" action="" enctype="multipart/form-data">
            <h3 id="form_title">Add New Product</h3>
            <input type="hidden" id="product_id" name="product_id">
            <input type="hidden" id="current_image" name="current_image">
            <input type="text" id="name" name="name" placeholder="Product Name" required>
            <textarea id="description" name="description" placeholder="Product Description" required></textarea>
            <input type="number" step="0.01" id="price" name="price" placeholder="Price (LKR)" required>
            <input type="file" id="image" name="image">
            <input type="submit" id="add_product" name="add_product" value="Add Product">
            <input type="submit" id="update_product" name="update_product" value="Update Product" style="display: none;">
        </form>

        <div class="products-list">
            <h3>Existing Products</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $product['description']; ?></td>
                        <td><?php echo $product['price']; ?></td>
                        <td><img src="<?php echo $product['image']; ?>" alt="Product Image" width="50"></td>
                        <td class="action-buttons">
                            <a href="#" onclick='editProduct(<?php echo json_encode($product); ?>)'>Update</a>
                            <a href="manage_products.php?delete_id=<?php echo $product['id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

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
</body>
</html>
