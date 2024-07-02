<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('config.php');
    include('loginPhp.php');
}

?>


<!DOCTYPE html>
<html >

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
    <div class="login-container">
        <div class="login-form" id="loginForm">
            <!-- <h1>Sign In</h1> -->
        </div>
            <img class="upper-cit"src="images/cit-logo-upper.png" >
            <img   class="cit"src="images/cit-logo.png"  >
           
            <div class="forgot-password">
                <p>Forgot password?</p>
            </div>

            <form method="post" action="">
            <?php
                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        echo '<span class="error-msg">' . $error . '</span>';
                    }
                }

                if (!empty($successes)) {
                    foreach ($successes as $success) {
                        echo '<span class="success-msg">' . $success . '</span>';
                    }
                }
                ?>
                
                <input type="text" name="email_or_id" placeholder="Email or ID Number" required value="<?php echo isset($_POST['email_or_id']) ? $_POST['email_or_id'] : '' ?>">
                <input type="password" name="newpassword" placeholder="New Password" required value="<?php echo isset($_POST['newpassword']) ? $_POST['newpassword'] : '' ?>">
                <input type="password" name="cpassword" placeholder="Confirm New Password" required value="<?php echo isset($_POST['cpassword']) ? $_POST['cpassword'] : '' ?>">
                <button type="submit" name="submit_newpass">Change Password</button>
                
            </form>
            <p class="dont-account">Already have an account? <a href="login.php" id="showSignup">Sign In</a></p>
        </div>
           
    </div>
    <script src="script.js"></script>
</body>

</html>