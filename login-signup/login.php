<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('../config.php');
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
    <title>Sign In/Sign Up Page | Dan Aleko</title>

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
            <img class="upper-cit"src="../images/cit-logo-upper.png" >
            <img   class="cit"src="../images/cit-logo.png"  >
           
            <form method="post" action="">
            <?php
                        if(isset($errors)){
                            foreach($errors as $error){
                                echo '<span class="error-msg">'.$error.'</span>';
                            }
                        }

                    ?>
                     
                <div class = "container-email">
                <i class='bx bxs-user'></i>
                <input type="text" id="email" name="email_or_id" placeholder="Email or ID Number" required >
                    </div>
                    <div class = "container-pass">
                <i class='bx bxs-lock'></i>
                <input type="password" id="pass" name="password" placeholder="Password" required>
                </div>
                <button type="submit" name="submit_signin">Sign In</button>
                <a href="forgot_pass.php">Forgot Your Password?</a>
            </form>
            <p class="dont-account">Don't have an account? <a href="signup.php" id="showSignup">Sign Up</a></p>
        </div>
           
    </div>
                    <!--LOGIN MODAL DIV-->
    <div id="loginSuccessModal" class="login-success-modal">
        <div class="login-success-modal-content">
            <img src="../images/check_gif.webp" alt="Success" class="login-success-icon">
            <p>Logged In Successfully</p>
        </div>
    </div>
    <script src="script.js"></script>
                    <!--LOGIN MODAL SCRIPT-->
    <script>
        function showLoginSuccessModal(redirectUrl) {
            var modal = document.getElementById('loginSuccessModal');
            if (modal) {
                modal.style.display = "block";
                setTimeout(function () {
                    modal.style.display = "none";
                    window.location.href = redirectUrl;
                }, 1250);
            } else {
                console.error("Login success modal not found");
                window.location.href = redirectUrl;
            }
        }

        <?php
        if (isset($_SESSION['login_success']) && $_SESSION['login_success'] === true) {
            $redirect_url = '';
            switch ($_SESSION['userType']) {
                case 'Admin':
                    $redirect_url = '../users/ADMIN.php';
                    break;
                case 'Student':
                    $redirect_url = '../users/studentHomepage.php';
                    break;
                case 'Faculty':
                    $redirect_url = '../users/facultyHomepage.php';
                    break;
            }
            if (!empty($redirect_url)) {
                echo "document.addEventListener('DOMContentLoaded', function() { showLoginSuccessModal('$redirect_url'); });";
            }
            unset($_SESSION['login_success']);
        }
        ?>
    </script>
    <!--LOGIN MODAL STYLE-->
    <style>
        .login-success-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .login-success-modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border-radius: 5px;
            width: 220px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Outfit", sans-serif;
            text-decoration: none;
        }
        .login-success-modal-content p{
            margin-top: 10px;
            color: #000000;
        }
        .login-success-icon {
            width: 30px;
            height: 30px;
            margin-right: 20px;
        }
    </style>
</body>

</html>