<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing - Fitness Kingdom Gym</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #000;
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

        .nav-bar nav ul li a.active {
            color: #007bff;
        }

        .section {
            padding: 50px 0;
            text-align: center;
        }

        .section h1 {
            font-size: 80px;
            margin-bottom: 50px;
            text-transform: uppercase;
            color: white;
            -webkit-text-stroke: 1px #f6f7f8;
            -webkit-text-fill-color: transparent;
        }

        .section .plans {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .section .plan {
            background-color: #1a1a1a;
            border-radius: 15px;
            width: 360px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }

        .section .plan img {
            width: 50px;
            margin-bottom: 20px;
        }

        .section .plan h2 {
            font-size: 24px;
            margin: 20px 0 10px 0;
        }

        .section .plan .price {
            font-size: 30px;
            color: #ff6347;
            margin: 10px 0;
        }

        .section .plan p {
            font-size: 16px;
            color: #ddd;
            text-align: left;
            margin-left: 20px;
            margin-right: 20px;
        }

        .section .plan .join-now {
            color: #007bff;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .section .plan .join-now:hover {
            color: #0056b3;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
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
    <main>
        <section class="section" id="physical-workout" data-aos="fade-up">
            <h1>Physical Workout Plans</h1>
            <div class="plans">
                <div class="plan" data-aos="fade-right">
                    <img src="assets/img/icon.png" alt="Icon">
                    <h2>1 Month Plan</h2>
                    <div class="price">LKR.3,000</div>
                    <p>✓ Free Riding</p>
                    <p>✓ Unlimited Equipments</p>
                    <p>✓ Personal Trainer</p>
                    <p>✓ Weight Losing Classes</p>
                    <p>✓ Month To Mouth</p>
                    
                </div>
                <div class="plan" data-aos="fade-up">
                    <img src="assets/img/icon.png" alt="Icon">
                    <h2>6 Month Plan</h2>
                    <div class="price">LKR.13,500</div>
                    <p>✓ Free Riding</p>
                    <p>✓ Unlimited Equipments</p>
                    <p>✓ Personal Trainer</p>
                    <p>✓ Weight Losing Classes</p>
                    <p>✓ Month To Mouth</p>
                    
                </div>
                <div class="plan" data-aos="fade-left">
                    <img src="assets/img/icon.png" alt="Icon">
                    <h2>12 Month Plan</h2>
                    <div class="price">LKR.27,000</div>
                    <p>✓ Free Riding</p>
                    <p>✓ Unlimited Equipments</p>
                    <p>✓ Personal Trainer</p>
                    <p>✓ Weight Losing Classes</p>
                    <p>✓ Month To Mouth</p>
                    
                </div>
            </div>
        </section>

        <section class="section" id="home-visit" data-aos="fade-up">
            <h1>Home Visit Workout Plans</h1>
            <div class="plans">
                <div class="plan" data-aos="fade-right">
                    <img src="assets/img/icon.png" alt="Icon">
                    <h2>Per Visit</h2>
                    <div class="price">LKR.1,250</div>
                    <p>✓ Personal Trainer Visit</p>
                    <p>✓ Customized Workout Plan</p>
                    <p>✓ Nutritional Advice</p>
                    <p>✓ Weekly Progress Check</p>
                    <p>✓ Month To Mouth</p>
                   
                </div>
                <div class="plan" data-aos="fade-up">
                    <img src="assets/img/icon.png" alt="Icon">
                    <h2>1 Month Plan</h2>
                    <div class="price">LKR.10,000</div>
                    <p>✓ Personal Trainer Visit</p>
                    <p>✓ Customized Workout Plan</p>
                    <p>✓ Nutritional Advice</p>
                    <p>✓ Weekly Progress Check</p>
                    <p>✓ Month To Mouth</p>
                    
                </div>
                <div class="plan" data-aos="fade-left">
                    <img src="assets/img/icon.png" alt="Icon">
                    <h2>6 Month Plan</h2>
                    <div class="price">LKR.45,000</div>
                    <p>✓ Personal Trainer Visit</p>
                    <p>✓ Customized Workout Plan</p>
                    <p>✓ Nutritional Advice</p>
                    <p>✓ Weekly Progress Check</p>
                    <p>✓ Month To Mouth</p>
                    
                </div>
            </div>
        </section>

        <section class="section" id="zumba-yoga" data-aos="fade-up">
            <h1>Online Workout Plans</h1>
            <div class="plans">
                <div class="plan" data-aos="fade-right">
                    <img src="assets/img/icon.png" alt="Icon">
                    <h2>1 Month Plan</h2>
                    <div class="price">LKR.2,500</div>
                    <p>✓ Live Zumba Classes</p>
                    <p>✓ Live Yoga Classes</p>
                    <p>✓ Recorded Sessions</p>
                    <p>✓ Nutritional Advice</p>
                    <p>✓ Month To Mouth</p>
                   
                </div>
                <div class="plan" data-aos="fade-up">
                    <img src="assets/img/icon.png" alt="Icon">
                    <h2>6 Month Plan</h2>
                    <div class="price">LKR.11,250</div>
                    <p>✓ Live Zumba Classes</p>
                    <p>✓ Live Yoga Classes</p>
                    <p>✓ Recorded Sessions</p>
                    <p>✓ Nutritional Advice</p>
                    <p>✓ Month To Mouth</p>
                   
                </div>
                <div class="plan" data-aos="fade-left">
                    <img src="assets/img/icon.png" alt="Icon">
                    <h2>12 Month Plan</h2>
                    <div class="price">LKR.22,500</div>
                    <p>✓ Live Zumba Classes</p>
                    <p>✓ Live Yoga Classes</p>
                    <p>✓ Recorded Sessions</p>
                    <p>✓ Nutritional Advice</p>
                    <p>✓ Month To Mouth</p>
                    
                </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({
            duration: 2000, // duration of animation in milliseconds
            once: false, // disable this to animate elements on every scroll
        });
    </script>
</body>
</html>
