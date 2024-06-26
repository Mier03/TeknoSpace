<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('php/config.php');

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if (isset($_POST['submit_signup'])) { // name of the button in the html tag for sign up
        $firstName = $conn->real_escape_string($_POST['firstName']);
        $lastName = $conn->real_escape_string($_POST['lastName']);
        $idNumber = $conn->real_escape_string($_POST['idNumber']);
        $email = $conn->real_escape_string($_POST['email']);
        $course = $conn->real_escape_string($_POST['course']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (firstName, lastName, idNumber, email, course, password) 
                VALUES ('$firstName', '$lastName', '$idNumber', '$email', '$course', '$password')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['first_name'] = $firstName;
            $_SESSION['last_name'] = $lastName;
            $_SESSION['idNumber'] = $idNumber;
            $_SESSION['valid'] = $email;
            $_SESSION['course'] = $course;
            $_SESSION['id'] = $conn->insert_id;

            header("Location: login.php");
            exit();
        } else {
            echo "<div class='message'><p>Error: " . $sql . "<br>" . $conn->error . "</p></div><br>";
        }
    } elseif (isset($_POST['submit_signin'])) { // name of the button in the html tag for log in
        // Handle login form submission
        // The field where the user inputs his/her email or id was named email_or_id
        // This code will get the user's input once the sign in button is clicked
        $email_or_id = mysqli_real_escape_string($con, $_POST['email_or_id']);
        $password = mysqli_real_escape_string($con, $_POST['password']);

        // Check if input is email or idNumber
        if (filter_var($email_or_id, FILTER_VALIDATE_EMAIL)) {
            $sql = "SELECT * FROM users WHERE email='$email_or_id'";
        } else {
            $sql = "SELECT * FROM users WHERE idNumber='$email_or_id'";
        }

        $result = mysqli_query($con, $sql);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['password'])) {
                $_SESSION['valid'] = $row['email'];
                $_SESSION['username'] = $row['firstName'] . ' ' . $row['lastName'];
                $_SESSION['firstName'] = $row['firstName'];
                $_SESSION['lastName'] = $row['lastName'];
                $_SESSION['course'] = $row['course']; 
                $_SESSION['idNumber'] = $row['idNumber']; 

                if ($_SESSION['username'] == "admin updates") {
                    $hashedPasswordFromDB = $row['password']; 
                    if (password_verify($password, $hashedPasswordFromDB)) {
                        // Password matches for admin
                        header("Location: SUNGAHID/homepage.php");
                        exit();
                    } else {
                        echo "<div class='message'><p>Wrong Password</p></div><br>";
                    }
                } else {
                    header("Location: SUNGAHID/homepage.php");
                    exit();
                }
            } else {
                echo "<div class='message'><p>Wrong Password</p></div><br>";
            }
        } else {
            echo "<div class='message'><p>User not found</p></div><br>";
        }
    }

    mysqli_close($con);
}
?>




<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Sign In/Sign Up Page | Dan Aleko</title>

    <!-- boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com' crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

</head>
<body>
    <div class="login-container">
        <div class="login-form" id="loginForm">
        <img class="upper-cit"src="cit-logo-upper.png" >
        <img   class="cit"src="cit-logo.png"  >

            <form method="post" action="">
                <input type="text" name="email_or_id" placeholder="Email or ID Number" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="submit_signin">Sign In</button>
                <a href="#">Forgot Your Password?</a>
            </form>
            <p>Don't have an account? <a href="#" id="showSignup">Sign Up</a></p>
        </div>
        
        <div class="signup-form hidden" id="signupForm">
            <img   class="cit"src="cit-logo.png"  >
            <div class="buttonuser-type">
                
                <button class="type">Faculty</button>
                <button class="type">Student</button>
            </div>

            <form method="post" action="">
                <input type="text" name="firstName" placeholder="First Name" required>
                <input type="text" name="lastName" placeholder="Last Name" required>
                <input type="text" name="idNumber" placeholder="ID Number" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="course" placeholder="Course" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="submit_signup">Sign Up</button>
            </form>
            <p>Already have an account? <a href="#" id="showLogin">Sign In</a></p>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
