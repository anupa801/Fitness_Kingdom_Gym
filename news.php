<?php
// Database connection
include('db_connect.php');

// Fetch all news
$news_list = [];
$result = $conn->query("SELECT * FROM news ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    $news_list[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: black;
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

        .nav-bar nav ul li a.active {
            color: #007bff;
        }

        .container {
            background-color: #fff;
            color: #333;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            width: 90%;
            max-width: 1200px;
            margin: 100px auto 20px;
            position: relative;
        }

        .container h2 {
            margin-bottom: 20px;
            font-size: 2em;
            font-weight: 600;
            text-align: center;
        }

        .news-item {
            display: none;
            margin-bottom: 40px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        .news-item.active {
            display: block;
        }

        .news-item img {
            max-width: 100%;
            border-radius: 10px;
        }

        .news-item h3 {
            margin-top: 0;
            font-size: 1.5em;
            color: #007bff;
        }

        .news-item p {
            font-size: 1.2em;
            line-height: 1.6;
        }

        .news-item time {
            display: block;
            margin-top: 10px;
            font-size: 0.9em;
            color: #666;
        }

        .navigation {
            text-align: center;
            margin-top: 20px;
        }

        .navigation .arrow {
            font-size: 3em;
            color: #007bff;
            cursor: pointer;
            margin: 0 40px;
            transition: color 0.3s ease;
        }

        .navigation .arrow:hover {
            color: #0056b3;
        }

        footer {
            background-color: #353a3f;
            color: white;
            padding: 30px 0;
            text-align: center;
            width: 100%;
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
                    <li><a href="contact_us.php">Contact Us</a></li>
                </ul>
            </nav>
        </div>
    </header>

<div class="container">
    <h2>News</h2>
    <?php if (!empty($news_list)): ?>
        <?php foreach ($news_list as $index => $news): ?>
            <div class="news-item <?php echo $index === 0 ? 'active' : ''; ?>">
                <img src="uploads/<?php echo htmlspecialchars($news['news_image']); ?>" alt="News Image">
                <h3><?php echo htmlspecialchars($news['news_title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($news['news_description'])); ?></p>
                <time datetime="<?php echo $news['created_at']; ?>"><?php echo date('F j, Y', strtotime($news['created_at'])); ?></time>
            </div>
        <?php endforeach; ?>
        <div class="navigation">
            <span class="arrow" onclick="showPrevious()">&#9664;</span>
            <span class="arrow" onclick="showNext()">&#9654;</span>
        </div>
    <?php else: ?>
        <p>No news available.</p>
    <?php endif; ?>
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
    let currentIndex = 0;
    const newsItems = document.querySelectorAll('.news-item');

    function showNews(index) {
        newsItems.forEach((item, i) => {
            item.classList.remove('active');
            if (i === index) {
                item.classList.add('active');
            }
        });
    }

    function showNext() {
        currentIndex = (currentIndex + 1) % newsItems.length;
        showNews(currentIndex);
    }

    function showPrevious() {
        currentIndex = (currentIndex - 1 + newsItems.length) % newsItems.length;
        showNews(currentIndex);
    }
</script>
</body>
</html>
