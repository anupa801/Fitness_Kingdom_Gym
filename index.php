<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Kingdom Gym</title>
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

        .hero {
            position: relative;
            height: 90vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: left;
            color: white;
            overflow: hidden;
        }

        .hero img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .hero img.active {
            opacity: 1;
        }

        .hero-text {
            position: relative;
            z-index: 1;
            margin-left: -660px;
        }

        .hero-text h1 {
            font-size: 50px;
            display: block;
            font-weight: 700;
            text-transform: uppercase;
            color: #fff;
            -webkit-text-stroke: 1px #f6f7f8;
            -webkit-text-fill-color: transparent;
            line-height: 0.1;
        }

        .hero-text h2 {
            font-size: 140px;
            margin: 10px 0;
            line-height: 1.0;
        }

        .hero-text button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 20px;
            cursor: pointer;
            margin-top: 20px;
            margin-left: 20px;
            border-radius: 15px;
        }

        .hero-text button:hover {
            background-color: #0056b3;
        }

        .carousel-indicators {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }

        .carousel-indicators div {
            width: 10px;
            height: 10px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            cursor: pointer;
        }

        .carousel-indicators .active {
            background: #fff;
        }

        .carousel-nav {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
        }

        .carousel-nav span {
            background: rgba(255, 255, 255, 0.5);
            padding: 10px;
            cursor: pointer;
        }

        .carousel-nav span:hover {
            background: #fff;
        }

        /* New Section: What I Offer */
        .offerings {
            background-color: #000;
            color: white;
            text-align: center;
            padding: 50px 0;
        }

        .offerings h1 {
            font-size: 120px;
            margin-bottom: 50px;
            text-transform: uppercase;
            color: white;
            -webkit-text-stroke: 1px #f6f7f8;
            -webkit-text-fill-color: transparent;
        }

        .offerings .cards {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .offerings .card {
            background-color: #1a1a1a;
            border-radius: 15px;
            width: 400px;
            text-align: center;
        }

        .offerings .card img {
            width: 100%;
            border-radius: 15px;
        }

        .offerings .card h2 {
            font-size: 24px;
            margin: 20px 0 10px 0;
        }

        .offerings .card p {
            font-size: 20px;
            margin-left: 8px;
            margin-right: 8px;
            color: #ddd;
        }

        /* New Section: Pricing */
        .pricing {
            background-color: #000;
            color: white;
            text-align: center;
            padding: 50px 0;
        }

        .pricing h1 {
            font-size: 120px;
            margin-bottom: 50px;
            text-transform: uppercase;
            color: white;
            -webkit-text-stroke: 1px #f6f7f8;
            -webkit-text-fill-color: transparent;
        }

        .pricing .plans {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .pricing .plan {
            background-color: #1a1a1a;
            border-radius: 15px;
            width: 360px;
            padding: 20px;
            text-align: center;
        }

        .pricing .plan img {
            width: 50px;
            margin-bottom: 20px;
        }

        .pricing .plan h2 {
            font-size: 24px;
            margin: 20px 0 10px 0;
        }

        .pricing .plan .price {
            font-size: 30px;
            color: #ff6347;
            margin: 10px 0;
        }

        .pricing .plan p {
            font-size: 16px;
            color: #ddd;
            text-align: left;
            margin-left: 20px;
            margin-right: 20px;
        }

        .pricing .plan .join-now {
            color: #007bff;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .pricing .plan .join-now:hover {
            color: #0056b3;
        }

        .more-details {
            margin-top: 20px;
        }

        .more-details a {
            color: #007bff;
            text-decoration: none;
            font-size: 18px;
        }

        .more-details a:hover {
            color: #0056b3;
        }

        /* New Section: Products */
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

        .products .see-more {
            margin-top: 20px;
        }

        .products .see-more a {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .products .see-more a:hover {
            background-color: #0056b3;
        }

        .more-products {
            margin-top: 20px;
        }

        .more-products a {
            color: #007bff;
            text-decoration: none;
            font-size: 18px;
        }

        .more-products a:hover {
            color: #0056b3;
        }

        /* New Section: Our Team */
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

        /* New Section: Footer */
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
        <section class="hero">
            <img src="assets/img/hero1.jpg" class="active" alt="Hero Image 1">
            <img src="assets/img/hero2.jpg" alt="Hero Image 2">
            <img src="assets/img/hero3.jpg" alt="Hero Image 3">
            <img src="assets/img/hero4.jpg" alt="Hero Image 4">
            <img src="assets/img/hero5.jpg" alt="Hero Image 5">
            <div class="carousel-indicators">
                <div class="active"></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div class="carousel-nav">
                <span class="prev">&lt;</span>
                <span class="next">&gt;</span>
            </div>
            <div class="hero-text" data-aos="fade-up">
                <h1>HI THIS IS FITNESS KINGDOM</h1>
                <h2>GYM<BR>TRAINER</h2>
                <button onclick="window.location.href='login.php'">Book Appointments</button>
            </div>
        </section>

        <!-- New Section: What I Offer -->
        <section class="offerings">
            <h1 data-aos="fade-up">WHAT I OFFER</h1>
            <div class="cards">
                <div class="card" data-aos="fade-right">
                    <img src="assets/img/body_building_p.png" alt="Body Building"> 
                    <h2>Body Building</h2>
                    <p>You’ll look at graphs and charts in Task One, how to approach the task</p>
                </div>
                <div class="card" data-aos="fade-up">
                    <img src="assets/img/muscle_gain_p.png" alt="Muscle Gain"> 
                    <h2>Muscle Gain</h2>
                    <p>You’ll look at graphs and charts in Task One, how to approach the task</p>
                </div>
                <div class="card" data-aos="fade-left">
                    <img src="assets/img/weight_loss_p.png" alt="Weight Loss"> 
                    <h2>Weight Loss</h2>
                    <p>You’ll look at graphs and charts in Task One, how to approach the task</p>
                </div>
            </div>
        </section>

        <!-- New Section: Pricing -->
        <section class="pricing">
            <h1 data-aos="fade-up">PRICING</h1>
            <div class="plans">
                <div class="plan" data-aos="fade-right">
                    <img src="assets/img/icon.png" alt="Icon"> 
                    <h2>1 MONTH</h2>
                    <div class="price">LKR.3,000 <span>(SINGLE CLASS)</span></div>
                    <p>✓ Free Riding</p>
                    <p>✓ Unlimited Equipments</p>
                    <p>✓ Personal Trainer</p>
                    <p>✓ Weight Losing Classes</p>
                    <p>✓ Month To Mouth</p>
                </div>
                <div class="plan" data-aos="fade-up">
                    <img src="assets/img/icon.png" alt="Icon"> 
                    <h2>6 MONTH</h2>
                    <div class="price">LKR.13,500 <span>(SINGLE CLASS)</span></div>
                    <p>✓ Free Riding</p>
                    <p>✓ Unlimited Equipments</p>
                    <p>✓ Personal Trainer</p>
                    <p>✓ Weight Losing Classes</p>
                    <p>✓ Month To Mouth</p>
                </div>
                <div class="plan" data-aos="fade-left">
                    <img src="assets/img/icon.png" alt="Icon">
                    <h2>12 MONTH</h2>
                    <div class="price">LKR.27,000 <span>(SINGLE CLASS)</span></div>
                    <p>✓ Free Riding</p>
                    <p>✓ Unlimited Equipments</p>
                    <p>✓ Personal Trainer</p>
                    <p>✓ Weight Losing Classes</p>
                    <p>✓ Month To Mouth</p>
                </div>
            </div>
            <div class="more-details">
                <a href="pricing.php">More Package details</a>
            </div>
        </section>

        <!-- New Section: Products -->
        <section class="products">
            <h1 data-aos="fade-up">PRODUCTS</h1>
            <div class="cards">
                <div class="card" data-aos="fade-right">
                    <img src="assets/img/Equipments.jpg" alt="Equipment"> 
                    <h2>Equipment</h2>
                    <p>Top-quality gym equipment for your workout needs.</p>
                </div>
                <div class="card" data-aos="fade-up">
                    <img src="assets/img/supplements.jpg" alt="Supplements"> 
                    <h2>Supplements</h2>
                    <p>High-quality supplements to support your fitness journey.</p>
                </div>
                <div class="card" data-aos="fade-left">
                    <img src="assets/img/apparel.jpg" alt="Apparel"> 
                    <h2>Apparel</h2>
                    <p>Comfortable and stylish gym apparel.</p>
                </div>
            </div>
           
            <div class="more-products">
                <a href="products.php">More Package details</a>
            </div>
        </section>

        <!-- New Section: Our Team -->
        <section class="team">
            <h1 data-aos="fade-up">OUR TEAM</h1>
            <div class="cards">
                <div class="card" data-aos="fade-right">
                    <img src="assets/img/athika_thilumindaa.jpg" alt="Athika Thiluminda">
                    <h2>Athika Thiluminda</h2>
                    <p>Owner & Senior Trainer</p>
                </div>
                <div class="card" data-aos="fade-up">
                    <img src="assets/img/nidula_perera.jpg" alt="Nidula Perera">
                    <h2>Nidula Perera</h2>
                    <p>Personal Trainer</p>
                </div>
                <div class="card" data-aos="fade-left">
                    <img src="assets/img/lakshitha_perera.jpg" alt="Lakshitha Perera">
                    <h2>Lakshitha Perera</h2>
                    <p>Personal Trainer</p>
                </div>
                <div class="card" data-aos="fade-right">
                    <img src="assets/img/sadun_sampath.jpg" alt="Sadun Sampath">
                    <h2>Sadun Sampath</h2>
                    <p>Nutritionist & Personal Trainer</p>
                </div>
                <div class="card" data-aos="fade-left">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({
            duration: 2000, // duration of animation in milliseconds
            once: false, // disable this to animate elements on every scroll
        });

        // Slideshow for hero section
        let currentSlide = 0;
        const slides = document.querySelectorAll('.hero img');
        const indicators = document.querySelectorAll('.carousel-indicators div');
        const prevButton = document.querySelector('.carousel-nav .prev');
        const nextButton = document.querySelector('.carousel-nav .next');

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.remove('active');
                indicators[i].classList.remove('active');
                if (i === index) {
                    slide.classList.add('active');
                    indicators[i].classList.add('active');
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        }

        nextButton.addEventListener('click', nextSlide);
        prevButton.addEventListener('click', prevSlide);
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => showSlide(index));
        });

        setInterval(nextSlide, 5000); // Change image every 5 seconds
    </script>
</body>
</html>
