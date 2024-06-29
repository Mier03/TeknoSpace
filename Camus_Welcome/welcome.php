<?php
if (isset($_POST['submit_signup'])) {
    header('Location: /TeknoSpaceLogin/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="welcome.css" />
    <link rel="stylesheet" href="welcome_startup.css" />
    <title>Tekno Space</title>
    <link rel="icon" href="../images/logo circle.png" type="image/x-icon">
  </head>
  <body>
    <header>
        <div class="wrapper header-wrapper">
          <nav>
            <a href="#" onclick="window.location.reload(true)" class="logo">
              <img src="../images/logo circle.png" height="50" width="50" class="logo-img" alt="Logo">Tekno Space
            </a>
            <ul class="header-links">
              <li><a href="../login.php" class="navbar__link">Log In</a></li>
              <li><a href="../login.php" class="navbar__link">Sign Up</a></li>
            </ul>
          </nav>
        </div>
      </header>
    <div id="scroll-indicator"></div>
    <main>
      <div class="wrapper">
        <section class="hero">
          <div class="intro">
            <h1>Welcome Teknoys!</h1>
            <p>
                Welcome to Teknoys! Your go-to source for all school updates, events, and important announcements. 
                Stay informed and connected with everything happening at our school.
            </p>

            <!-- Sign up button -->
            <form method="post">
              <button type="submit" name="submit_signup" class="btn btn-cta">
                <i class="sign-up"></i> Sign Up Now!
              </button>
            </form>

          </div>
          <div class="image">
          <img src="../images/logotekno2.png" alt="" />
          </div>
        </section>
        <section class="about">
          <div class="intro">
            <h1>About Tekno Space</h1>
            <p>Tekno Space is more than just a website; it's a digital hub designed to keep students informed 
                and engaged. Here, you'll find timely updates, 
                insightful articles, and a platform to connect with the school community.</p>
          </div>
          <div class="img">
            <img src="../images/LOGO_OKAY.jpg" alt="">
          </div>
        </section>
        <section class="services">
          <div class="intro">
            <h1>Why did we make Tekno Space?</h1>
            <p>Tekno Space was created to bridge the gap between students and school updates in an accessible 
                and user-friendly way. We believe in 
                transparency and communication, ensuring that every student stays informed and involved.</p>
          </div>
          <div class="img">
            <img src="../images/groufie 2.jpg" alt="">
          </div>
        </section>
        <section class="portfolio">
          <div class="intro">
            <h1>The Team</h1>
            <p>Meet us, the creators of Tekno Space! We are passionate students dedicated to keeping our 
                school community informed and connected. Through Tekno Space, 
                we strive to deliver accurate updates and engaging content that enriches your school experience.</p>
          </div>
          <div class="img">
            <img src="../images/groufie 2.jpg" alt="">
          </div>
        </section>
        <section class="projects">
          <div class="intro">
            <h1>Others...</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Placeat illum sit voluptatem voluptatibus sequi at amet explicabo quam. Aut reiciendis obcaecati enim ut sed magni unde quae quam ipsum ratione?</p>
          </div>
          <div class="img">
            <img src="../images/groufie 2.jpg" alt="">
          </div>
        </section>
        <section class="Clients">
          <div class="intro">
            <h1>Others...</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Placeat illum sit voluptatem voluptatibus sequi at amet explicabo quam. Aut reiciendis obcaecati enim ut sed magni unde quae quam ipsum ratione?</p>
          </div>
          <div class="img">
            <img src="../images/groufie 2.jpg" alt="">
          </div>
        </section>
    </main>

    <footer>
      Copyright &copy;2024 
    </footer>
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="welcome.js"></script>
  </body>
</html>