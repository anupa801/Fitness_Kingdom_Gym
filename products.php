<?php
session_start();
include('db_connect.php');

// Fetch all products
$products = [];
$result = $conn->query("SELECT * FROM products");
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Handle search request
$searchResults = [];
if (isset($_GET['search'])) {
    $search = htmlspecialchars($_GET['search']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ?");
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $searchResults[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Fitness Kingdom Gym</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('assets/img/background5.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }

        header {
            background-color: #353a3f;
            color: white;
            padding: 10px 0;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
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
            margin-left: -50px;
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

        .products {
            background-color: #000;
            color: white;
            text-align: center;
            padding: 50px 0;
        }

        .products h1 {
            font-size: 120px;
            margin-bottom: 50px;
            text-transform: uppercase;
            color: white;
            -webkit-text-stroke: 1px #f6f7f8;
            -webkit-text-fill-color: transparent;
        }

        .products .cards {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .products .card {
            background-color: #1a1a1a;
            border-radius: 15px;
            width: 300px;
            text-align: center;
        }

        .products .card img {
            width: 100%;
            border-radius: 15px;
        }

        .products .card h2 {
            font-size: 24px;
            margin: 20px 0 10px 0;
        }

        .products .card p {
            font-size: 20px;
            margin-left: 8px;
            margin-right: 8px;
            color: #ddd;
        }

        .products .card span {
            font-weight: bold;
            display: block;
            margin: 20px 0;
            color: #ff6347;
        }

        .add-to-cart-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .add-to-cart-btn:hover {
            background-color: #0056b3;
        }

        .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-container input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px 0 0 5px;
            width: 300px;
        }

        .search-container button {
            padding: 10px;
            font-size: 16px;
            border: none;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            border-radius: 0 5px 5px 0;
        }

        .search-container button:hover {
            background-color: #0056b3;
        }

        .search-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
            max-width: 80%;
            max-height: 65%;
            overflow-y: auto;
        }

        .search-popup h2 {
            margin-top: 0;
        }

        .search-popup .close-btn {
            background-color: #000000;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
        }

        .search-popup .close-btn:hover {
            background-color: #000000;
        }

        .search-popup .cards {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .search-popup .card {
            background-color: #1a1a1a;
            border-radius: 15px;
            width: 300px;
            text-align: center;
            margin: 10px 0;
        }

        .search-popup .card img {
            width: 100%;
            border-radius: 15px;
        }

        .search-popup .card h2 {
            font-size: 24px;
            margin: 20px 0 10px 0;
        }

        .search-popup .card p {
            font-size: 20px;
            margin-left: 8px;
            margin-right: 8px;
            color: #ddd;
        }

        .search-popup .card span {
            font-weight: bold;
            display: block;
            margin: 20px 0;
            color: #ff6347;
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

        .close-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 24px;
            color: white;
        }

        .close-icon:hover {
            color: #ff4500;
        }

    </style>
</head>
<body>
    <header>
        <div class="nav-bar">
            <div class="logo">Fitness Kingdom Gym</div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about_us.php">About Us</a></li>
                    <li><a href="pricing.php">Pricing</a></li>
                    <li><a href="products.php" class="active">Products</a></li>
                    <li><a href="news.php">News</a></li>
                    <li><a href="login.php">Login/Register</a></li>
                    <li><a href="contact_us.php">Contact Us</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <section class="products">
            <div class="search-container">
                <form id="searchForm" method="GET" action="">
                    <input type="text" name="search" placeholder="Search for products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit">Search</button>
                </form>
            </div>
            <h1>PRODUCTS</h1>
            <div class="cards">
                <?php foreach ($products as $product): ?>
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <span>LKR <?php echo number_format($product['price'], 2); ?></span>
                        <a href="shipping_details.php?product_id=<?php echo $product['id']; ?>" class="add-to-cart-btn">Add to Cart</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    <div class="search-popup" id="searchPopup">
        <span class="close-icon" onclick="closeSearchPopup()">&times;</span>
        <h2>Search Results</h2>
        <div id="searchResults">
            <!-- Search results will be displayed here -->
            <?php if (!empty($searchResults)): ?>
                <div class="cards">
                    <?php foreach ($searchResults as $product): ?>
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                            <p><?php echo htmlspecialchars($product['description']); ?></p>
                            <span>LKR <?php echo number_format($product['price'], 2); ?></span>
                            <a href="shipping_details.php?product_id=<?php echo $product['id']; ?>" class="add-to-cart-btn">Add to Cart</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No products found matching your search.</p>
            <?php endif; ?>
        </div>
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
        <?php if (!empty($searchResults)): ?>
            document.getElementById('searchPopup').style.display = 'block';
        <?php endif; ?>

        function closeSearchPopup() {
            document.getElementById('searchPopup').style.display = 'none';
        }
    </script>
</body>
</html>
