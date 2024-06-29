<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('php/config.php');
    include('php/loginPhp.php');
}
?>


<!DOCTYPE html>
<html >

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Sign In/Sign Up Page | Dan Aleko</title>

    <!-- boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="login-container">
        <div class="login-form" id="loginForm">
            <!-- <h1>Sign In</h1> -->
            <img class="upper-cit"src="cit-logo-upper.png" >
            <img   class="cit"src="cit-logo.png"  >
           
            <form method="post" action="">
                <input type="text" name="email_or_id" placeholder="Email or ID Number" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="submit_signin">Sign In</button>
                <a href="forgotPass.php">Forgot Your Password?</a>
            </form>
            <p>Don't have an account? <a href="#" id="showSignup">Sign Up</a></p>
        </div>
       

        <div class="signup-form hidden" id="signupForm">
            <img   class="cit"src="cit-logo.png"  >
           
           <div class="select-container">
                <select class="user-type">
                    <option  class="type">Select user type</option>
                    <option  class="type">Faculty</option>
                    <option class="type">Student</option>
                </select>
                <div class="icon-container">
                    <i id="icon" class='bx bxs-down-arrow'></i>
                </div>
           </div>
            
           

        
            <form method="post" action="">
                <input type="text" name="firstName" placeholder="First Name" required>
                <input type="text" name="middleName" placeholder="Middle Name" required>
                <input type="text" name="lastName" placeholder="Last Name" required>
                <input type="text" name="idNumber" placeholder="ID Number" pattern="[0-9]{2}-[0-9]{4}-[0-9]{3}" title="Please enter ID in format ##-####-##" required>
                <input type="email" name="email" placeholder="Email" pattern="^[a-zA-Z0-9._%+-]+@cit.edu$" title="Please enter a valid email ending with @cit.edu" required>
                <input type="text" name="course" placeholder="Course" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirmpassword" placeholder="Confirm Password" required>

                <button type="submit" name="submit_signup">Sign Up</button>
            </form>
            <p>Already have an account? <a href="#" id="showLogin">Sign In</a></p>
        </div>
    </div>
    <script src="script.js"></script>
</body>

</html>
