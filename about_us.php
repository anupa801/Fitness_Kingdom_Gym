<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Fitness Kingdom Gym</title>
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

        .about-section {
            background: url('assets/img/about_bg1.jpg');
            background-size: cover;
            background-position: center 10%;
            padding: 50px 0;
            text-align: center;
            color: white;
            position: relative;
        }

        .about-section img {
            border-radius: 15px;
            max-width: 300px;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            border: 1px solid white; /* Added white border */
        }

        .about-section .content {
            background: rgba(0, 0, 0, 0.85);
            padding: 30px;
            border-radius: 15px;
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .about-section h1 {
            font-size: 40px;
            margin-bottom: 20px;
        }

        .about-section p {
            font-size: 18px;
            margin: 0 auto 20px;
            line-height: 1.6;
            text-align: justify;
        }

        .team {
            background-color: #000;
            color: white;
            text-align: center;
            padding: 50px 0;
        }

        .team h1 {
            font-size: 120px;
            margin-bottom: 50px;
            text-transform: uppercase;
            color: white;
            -webkit-text-stroke: 1px #f6f7f8;
            -webkit-text-fill-color: transparent;
        }

        .team .cards {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .team .card {
            background-color: #1a1a1a;
            border-radius: 15px;
            width: 300px;
            text-align: center;
        }

        .team .card img {
            width: 100%;
            border-radius: 15px;
        }

        .team .card h2 {
            font-size: 24px;
            margin: 20px 0 10px 0;
        }

        .team .card p {
            font-size: 20px;
            margin-left: 8px;
            margin-right: 8px;
            color: #ddd;
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
        <section class="about-section" data-aos="fade-in">
            <div class="content">
                <img src="assets/img/athika_thilumindaa.jpg" alt="Athika Thiluminda">
                <h1>About Fitness Kingdom Gym</h1>
                <p>Fitness Kingdom Gym is your ultimate destination for achieving your fitness goals. We offer state-of-the-art facilities, certified trainers, and a variety of fitness programs to help you stay fit and healthy. Whether you're looking to build muscle, lose weight, or improve your overall well-being, we have something for everyone.</p>
                <p>Athika Thiluminda, the owner and senior trainer at Fitness Kingdom Gym, has years of experience in the fitness industry. His dedication and passion for fitness have helped many clients achieve their goals. Under his guidance, the gym offers personalized training programs and a supportive environment for all members.</p>
            </div>
        </section>

        <!-- New Section: Our Team -->
        <section class="team" data-aos="fade-up">
            <h1>OUR TEAM</h1>
            <div class="cards">
                <div class="card" data-aos="fade-right">
                    <img src="assets/img/nidula_perera.jpg" alt="Nidula Perera">
                    <h2>Nidula Perera</h2>
                    <p>Personal Trainer</p>
                </div>
                <div class="card" data-aos="fade-up">
                    <img src="assets/img/lakshitha_perera.jpg" alt="Lakshitha Perera">
                    <h2>Lakshitha Perera</h2>
                    <p>Personal Trainer</p>
                </div>
                <div class="card" data-aos="fade-left">
                    <img src="assets/img/sadun_sampath.jpg" alt="Sadun Sampath">
                    <h2>Sadun Sampath</h2>
                    <p>Nutritionist & Personal Trainer</p>
                </div>
                <div class="card" data-aos="fade-right">
                    <img src="assets/img/nisansala_naduni.jpg" alt="Nisansala Naduni">
                    <h2>Nisansala Naduni</h2>
                    <p>Yoga & Zumba Instructor</p>
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
