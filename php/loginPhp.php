<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //including connection of database
    include('php/config.php');

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    if (isset($_POST['submit_signup'])) { // name of the button in the html tag for sign up

        $userType = $conn->real_escape_string($_POST['userType']);
        $firstName = $conn->real_escape_string($_POST['firstName']);
        $middleName = $conn->real_escape_string($_POST['middleName']);
        $lastName = $conn->real_escape_string($_POST['lastName']);
        $idNumber = $conn->real_escape_string($_POST['idNumber']);
        $email = $conn->real_escape_string($_POST['email']);
        $course = $conn->real_escape_string($_POST['course']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $confirmPassword = $_POST['confirmpassword'];
        $p = $_POST['password'];

        // Check if passwords match
        if ($p !== $confirmPassword) {
            echo "<script>alert('Error: Passwords do not match.'); window.history.back(); document.getElementById('signupForm').reset();</script>";
            exit;
        }
        // Email should only end with @citt.edu
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@cit.edu$/', $email)) {
            echo "<script>alert('Error: Please enter a valid email ending with @cit.edu.'); window.history.back(); document.getElementById('signupForm').reset();</script>";
            exit;
        }

        // Strict ID number format
        if (!preg_match('/^\d{2}-\d{4}-\d{3}$/', $idNumber)) {
            echo "<script>alert('Error: ID number must be in the format ##-####-##.'); window.history.back(); document.getElementById('signupForm').reset();</script>";
            exit;
        }

        // The user must select a user type
        if (empty($userType) || $userType === '') {
            echo "<script>alert('Error: Please select a valid user type.'); window.history.back();</script>";
            exit;
        } 

        // Check if email already exists
        $check_email_query = "SELECT * FROM users WHERE email = '$email'";
        $result_email = $conn->query($check_email_query);
        if ($result_email->num_rows > 0) {
            echo "<script>alert('Error: User already exists.'); window.history.back(); document.getElementById('signupForm').reset();</script>";
            exit;
        }

        // Check if ID number already exists
        $check_id_query = "SELECT * FROM users WHERE idNumber = '$idNumber'";
        $result_id = $conn->query($check_id_query);
        if ($result_id->num_rows > 0) {
            echo "<script>alert('Error: User already exists.'); window.history.back(); document.getElementById('signupForm').reset();</script>";
            exit;
        }

        $sql = "INSERT INTO users (userType, firstName, middleName, lastName, idNumber, email, course, password) 
                VALUES ('$userType', '$firstName', '$middleName', '$lastName', '$idNumber', '$email', '$course', '$password')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['userType'] = $userType;
            $_SESSION['first_name'] = $firstName;
            $_SESSION['middle_name'] = $middletName;
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
        $email_or_id = mysqli_real_escape_string($conn, $_POST['email_or_id']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Check if input is email or idNumber
        if (filter_var($email_or_id, FILTER_VALIDATE_EMAIL)) {
            $sql = "SELECT * FROM users WHERE email='$email_or_id'";
        } else {
            $sql = "SELECT * FROM users WHERE idNumber='$email_or_id'";
        }

        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            if (password_verify($password, $row['password'])) {

                $_SESSION['valid'] = $row['email'];
                $_SESSION['username'] = $row['firstName'] . ' ' . $row['lastName'];
                $_SESSION['firstName'] = $row['firstName'];
                $_SESSION['lastName'] = $row['lastName'];
                $_SESSION['course'] = $row['course']; 
                $_SESSION['idNumber'] = $row['idNumber']; 
                $_SESSION['userType'] = $row['userType'];

                $sql = "SELECT * FROM users WHERE userType='$userType'";

                if ($_SESSION['firstName'] == "admin") {
                    $hashedPasswordFromDB = $row['password']; 
                    if (password_verify($password, $hashedPasswordFromDB)) {
                        // Password matches for admin
                        header("Location: SUNGAHID/adminHomepage.php");
                        exit();
                    } else {
                        echo "<div class='message'><p>Wrong Password</p></div><br>";
                    }
                } elseif ($_SESSION['userType'] === "Student") {
                    //student homepage
                    header("Location: SUNGAHID/studentHomepage.php");
                    exit();
                } elseif ($_SESSION['userType'] === "Faculty") {
                    //faculty admin homepage
                    header("Location: SUNGAHID/adminHomepage.php");
                    exit();
                } else {
                    echo "<div class='message'><p>Unknown user type</p></div><br>";
                }
            } else {
                echo "<div class='message'><p>Wrong Password</p></div><br>";
            }
        } else {
            echo "<div class='message'><p>User not found</p></div><br>";
        }
    }

    mysqli_close($conn);
    
}

?>
