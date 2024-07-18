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
    <div class="bg">
            <!-- HERE -->
            <img src="../images/signin-bg.png" alt="Background About Us">
        </div>
    <div class="login-container">
        <div class="login-form" id="loginForm">
            <!-- <h1>Sign In</h1> -->
        </div>
            <img   class="cit"src="../images/cit-logo.png"  >
           
            <div class="forgot-password">
                <p>Forgot password?</p>
            </div>

            <form method="post" action="send-password-reset.php">
                <input type="text" name="email" placeholder="Email">
                <button type="submit" name="submit_newpass">Send</button>
            </form>
        </div>
           
    </div>
    <script src="script.js"></script>
</body>
<style>
    .forgot-password p{
        margin-left:15px;
        color:#c10202;
    }
</style>
</html>