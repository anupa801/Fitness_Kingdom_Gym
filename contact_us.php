<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Fitness_Kingdom_Database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Prepare an insert statement
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        $feedback = "Message sent successfully!";
    } else {
        $feedback = "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Fitness Kingdom Gym</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('assets/img/contact_us3.jpg');
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

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 60px 0;
        }

        .contact-form {
            margin-top:-60px;
            background-color: rgba(26, 26, 26, 0.9);
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 800px;
            color: white;
        }

        .contact-form h2 {
            margin-bottom: 20px;
            color: #ffffff;
            text-align: center;
        }

        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 15px;
            margin: 15px 0;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: white;
        }

        .contact-form input::placeholder, .contact-form textarea::placeholder {
            color: #ccc;
        }

        .contact-form button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 20px 40px;
            font-size: 20px;
            cursor: pointer;
            margin-top: 20px;
            border-radius: 15px;
            display: block;
            width: 100%;
        }

        .contact-form button:hover {
            background-color: #0056b3;
        }

        .about h1 {
            font-size: 100px;
            margin-bottom: 50px;
            text-transform: uppercase;
            color: white;
            text-align: center;
            -webkit-text-stroke: 1px #f6f7f8;
            -webkit-text-fill-color: transparent;
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
                    <li><a href="products.php">Products</a></li>
                    <li><a href="news.php">News</a></li>
                    <li><a href="login.php">Login/Register</a></li>
                    <li><a href="contact_us.php" class="active">Contact Us</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- New Section: Contact Us -->
    <section class="about">
        <h1>Contact Us</h1>
    </section>

    <main>
        <section class="container">
            <div class="contact-form">
                <h2>Send Your Message</h2>
                <?php if (isset($feedback)): ?>
                    <p><?php echo $feedback; ?></p>
                <?php endif; ?>
                <form action="" method="POST">
                    <input type="text" name="name" placeholder="Enter your Name" required>
                    <input type="email" name="email" placeholder="Enter a valid email address" required>
                    <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
                    <button type="submit">Submit</button>
                </form>
            </div>
        </section>
    </main>
    <footer>
        <div class="footer-content">
            <div>
                <h2>About Us</h2>
                <p>Fitness Kingdom is your ultimate destination for achieving your fitness goals. Our certified trainers and state-of-the-art facilities ensure you get the best out of your workouts.</p>
            </div>
            <div>
                <h2>Quick Links</h2>
                <p><a href="index.php">Home</a></p>
                <p><a href="about_us.php">About Me</a></p>
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
