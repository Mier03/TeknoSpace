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
    <link rel="stylesheet" href="signup.css">
    <link rel="stylesheet" href="common-auth.css">
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
        <div class="signup-container">
            <div class="signup-form hidden" id="signupForm">
            </div>
                <form method="post" action="">
                    <img   class="cit"src="../images/cit-logo.png"  >

                    <?php
                        if(isset($errors)){
                            foreach($errors as $error){
                                echo '<span class="error-msg">'.$error.'</span>';
                            }
                        }

                    ?>
                    <div class="select-container">
                        <select class="user-type" name="userType">
                            <option  class="type">Select user type</option>
                            <option  class="type" <?php echo isset($_POST['userType']) && $_POST['userType'] == 'Faculty' ? 'selected' : '' ?>>Faculty</option>
                            <option class="type" <?php echo isset($_POST['userType']) && $_POST['userType'] == 'Student' ? 'selected' : '' ?>>Student</option>
                        </select>
                        <div class="icon-container">
                            <i id="icon" class='bx bxs-down-arrow'></i>
                        </div>
                    </div>
                        <input type="text" name="firstName" placeholder="First Name" required  value="<?php echo isset($_POST['firstName']) ? $_POST['firstName'] : '' ?>">
                        <input type="text" name="middleName" placeholder="Middle Name" required value="<?php echo isset($_POST['middleName']) ? $_POST['middleName'] : '' ?>">
                        <input type="text" name="lastName" placeholder="Last Name" required value="<?php echo isset($_POST['lastName']) ? $_POST['lastName'] : '' ?>">
                        <input type="text" name="idNumber" placeholder="ID Number" required value="<?php echo isset($_POST['idNumber']) ? $_POST['idNumber'] : '' ?>">
                        <input type="text" name="course" placeholder="Course" required value="<?php echo isset($_POST['course']) ? $_POST['course'] : '' ?>">
                        <input type="email" name="email" placeholder="Email" required value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>">
                        <input type="password" name="password" placeholder="Password" required>
                        <input type="confirmpassword" name="confirmpassword" placeholder="Confirm Password" required>
                        
                        <button type="submit" name="submit_signup">Sign Up</button>
                        
                        <p class="already-account">Already have an account? <a href="login.php" id="showLogin">Sign In</a></p>
                    </div>
                
                </form>
                
            </div>
        </div>
    
    <script src="script.js"></script>
</body>

</html>