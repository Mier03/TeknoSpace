<?php

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
    <link rel="stylesheet" href="common-auth.css">
    <link rel="stylesheet" href="login.css">
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
        </div>
            <img class="upper-cit"src="images/cit-logo-upper.png" >
            <img   class="cit"src="images/cit-logo.png"  >
           
            <form method="post" action="">
            <?php
                        if(isset($errors)){
                            foreach($errors as $error){
                                echo '<span class="error-msg">'.$error.'</span>';
                            }
                        }

                    ?>
                <input type="text" name="email_or_id" placeholder="Email or ID Number" required >
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="submit_signin">Sign In</button>
                <a href="forgotPass.php">Forgot Your Password?</a>
            </form>
            <p class="dont-account">Don't have an account? <a href="signup.php" id="showSignup">Sign Up</a></p>
        </div>
           
    </div>
    <script src="script.js"></script>
</body>

</html>