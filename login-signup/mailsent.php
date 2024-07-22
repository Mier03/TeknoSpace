<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('../config.php');
    include('loginPhp.php');
}


$prefill_identifier = isset($_GET['identifier']) ? htmlspecialchars($_GET['identifier']) : '';


?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="common-auth.css">
    <link rel="stylesheet" href="login.css">
    <title>Forgot Password</title>

    <!-- boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="bg">
        <!-- HERE -->
        <img src="../images/signin-bg.png" alt="Background About Us">
    </div>
    <div class="login-container">
        <div class="login-form" id="loginForm">
            <!-- <h1>Sign In</h1> -->
        </div>
        <img class="upper-cit" src="../images/cit-logo-upper.png">
        <img class="cit" src="../images/cit-logo.png">

        <div class="forgot-password">
            <p>We have sent a password reset link to your email address. <br/> If not sent, contact your administration.</p>
        </div>
    </div>

    </div>
</body>
<style>
    .forgot-password{
        background-color: green;
        color: white; 
        padding: 10px;
        border-radius: 10px; 
        font-size: 18px;

    }
    </style>
</html>