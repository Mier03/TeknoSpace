<?php
if (isset($_POST['submit_signup'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <!-- HERE -->
    <link rel="stylesheet" href="Camus_Welcome/aboutus.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <!-- HERE -->
    <link rel="icon" href="images/logo circle.png" type="image/x-icon">

</head>
<body>
    <div class="intro">
        <h1 class="tekno-header">
            <span class="tekno">Tekno</span>
            <span class="tekno"> Space</span>
        </h1>
    </div>
    <header>
        <section class="header">
            <nav>
                <div class="logo-title-container">
                    <!-- HERE -->
                    <a href="aboutUs.php"><img src="images/logo circle.png" alt="Tekno Space Logo"></a>
                    <h1>Tekno Space</h1>
                </div>
                <div class="welcome-nav">
                    <a href="#aboutus">About Us</a>
                    <a href="#theteam">The Team</a>
                    <a href="#services">Services</a>
                </div>
                <div class="nav-links">
                    <ul>
                        <li><a href="login.php">Log In</a></li>
                        <li><a href="signup.php">Register Now</a></li>
                    </ul>
                </div>
            </nav>
        </section>
    </header>
    <div id="scroll-indicator"></div>
    <div class="container">
    <section class="welcome">
        <div class="bg">
            <!-- HERE -->
            <img src="images/Background.png" alt="Background About Us">
        </div>
        <div class="midscreen-text">
            <h1>Welcome to Tekno Space~!</h1>
            <p>YWelcome to Teknoys! Your go-to source for all school updates, events, and important announcements. 
                Stay informed and connected with everything happening at our school.</p>
        </div>
    </section>

    <section id="aboutus" class="alternating-section">
        <div class="bg">
            <img src="images/Background.png" alt="Background">
        </div>
        <div class="section-content">
            <div class="image-container">
                <img src="images/logo circle.png" alt="About Us Image">
            </div>
            <div class="text-container">
                <h1>About Us</h1>
                <p>Tekno Space is more than just a website; it's a digital hub designed to keep students informed 
                    and engaged. Here, you'll find timely updates, 
                    insightful articles, and a platform to connect with the school community.</p>
            </div>
        </div>
    </section>

    <section id="theteam" class="alternating-section reverse">
        <div class="bg">
            <img src="images/Background.png" alt="Background">
        </div>
        <div class="section-content">
            <div class="image-container">
                <img src="images/groufie 2.jpg" alt="Our Team">
            </div>
            <div class="text-container">
                <h1>The Team</h1>
                <p>Meet us, the creators of Tekno Space! We are passionate students dedicated to keeping our 
                    school community informed and connected. Through Tekno Space, 
                    we strive to deliver accurate updates and engaging content that enriches your school experience.</p>
            </div>
        </div>
    </section>

    <section id="services" class="alternating-section">
        <div class="bg">
            <img src="images/Background.png" alt="Background">
        </div>
        <div class="section-content">
            <div class="image-container">
                <img src="images/cit-logo-upper.png" alt="Our Services">
            </div>
            <div class="text-container">
                <h1>Services</h1>
                <p>Tekno Space was created to bridge the gap between students and school updates in an accessible 
                    and user-friendly way. We believe in 
                    transparency and communication, ensuring that every student stays informed and involved.</p>
            </div>
        </div>
    </section>

    <footer>
        <div class="creds">
            <hr>
            Author: ARMA (Angelina, Rainelyn, Mitch, Adrianne)<br>
            &copy; Copyright Reserved 2024 
        </div>
    </footer>
</div>
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="Camus_Welcome/aboutus.js"></script>
</body>
</html>