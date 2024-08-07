<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //including connection of database
    include('../config.php');

    if (!$conn) {

        die("Connection failed: " . mysqli_connect_error());
    }

    $successes = [];

    if (isset($_POST['submit_signup'])) { // name of the button in the html tag for sign up
        $errors = [];

        unset($errors);

        $userType = $conn->real_escape_string($_POST['userType']);
        $firstName = $conn->real_escape_string($_POST['firstName']);
        $middleName = $conn->real_escape_string($_POST['middleName']);
        $lastName = $conn->real_escape_string($_POST['lastName']);
        $idNumber = $conn->real_escape_string($_POST['idNumber']);
        $course = $conn->real_escape_string($_POST['course']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $confirmPassword = $_POST['confirmpassword'];
        $p = $_POST['password'];

        // Check if passwords match
        if ($p !== $confirmPassword) {
            $errors[] = 'Passwords do not match.';
        }
        // Email should only end with @citt.edu
        // if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@cit.edu$/', $email)) {
        //     $errors[] = 'Please enter a valid email ending with @cit.edu.';
        // }
        if (
            !filter_var($email, FILTER_VALIDATE_EMAIL)
            || !preg_match('/^[a-zA-Z0-9]+\.[a-zA-Z0-9]+@cit\.edu$/', $email)
            || strpos($email, '..') !== false
            || strpos($email, ' ') !== false
        ) {
            $errors[] = 'Please enter a valid email ending with @cit.edu with no spaces';
        }

        // Strict ID number format
        if (!preg_match('/^\d{2}-\d{4}-\d{3}$/', $idNumber)) {
            $errors[] = 'ID number must be in the format ##-####-###';
        }

        // The user must select a user type
        if (empty($userType) || $userType === '' || $userType === "Select user type") {

            $errors[] = "Please select a valid user type.";
        }

        // Check if email already exists
        $check_email_query = "SELECT * FROM users WHERE email = '$email' 
                      UNION 
                      SELECT * FROM verify WHERE email = '$email'";
        $result_email = $conn->query($check_email_query);

        if ($result_email->num_rows > 0) {
            $errors[] = 'Email already exists or your information is still in the verification process.';
        }

        // Check if ID number already exists
        $check_id_query = "SELECT * FROM users WHERE idNumber = '$idNumber' 
                   UNION 
                   SELECT * FROM verify WHERE idNumber = '$idNumber'";
        $result_id = $conn->query($check_id_query);

        if ($result_id->num_rows > 0) {
            $errors[] = 'ID number already exists or your information is still in the verification process.';
        }

        // if no error proceed to insertion
        if (empty($errors)) {

            if ($userType === 'Faculty' || $userType === 'Student') {
                if (empty($errors)) {
                    $sql = "INSERT INTO verify (userType, firstName, middleName, lastName, idNumber, email, course, password) 
                        VALUES ('$userType', '$firstName', '$middleName', '$lastName', '$idNumber', '$email', '$course', '$password')";

                    if ($conn->query($sql) === TRUE) {
                        if ($userType === 'Faculty' || $userType === 'Student') {
                            $_SESSION['userType'] = $userType;
                            $_SESSION['first_name'] = $firstName;
                            $_SESSION['middle_name'] = $middleName;
                            $_SESSION['last_name'] = $lastName;
                            $_SESSION['idNumber'] = $idNumber;
                            $_SESSION['valid'] = $email;
                            $_SESSION['course'] = $course;
                            $_SESSION['id'] = $conn->insert_id;

                            echo "<script>
                                document.addEventListener('DOMContentLoaded', function() {
                                var modal = document.getElementById('successModal');
                                modal.style.display = 'block';
                                setTimeout(function() {
                                    modal.style.display = 'none';
                                    window.location.href = 'login.php';
                                }, 1250);
                            });
                    </script>";
                        } else {
                            $errors[] = 'Error to insert new user. Contact admin for further investigation.';
                        }
                    }
                }
            } else {
                $errors[] = "Invalid user type.";
            }
        }
    } elseif (isset($_POST['submit_signin'])) { // name of the button in the html tag for log in
        $errors = [];

        unset($errors);
        // Handle login form submission
        // The field where the user inputs his/her email or id was named email_or_id
        // This code will get the user's input once the sign in button is clicked
        $email_or_id = mysqli_real_escape_string($conn, $_POST['email_or_id']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Check if input is email or idNumber
        if (filter_var($email_or_id, FILTER_VALIDATE_EMAIL)) {
            $sql = "SELECT * FROM users WHERE email='$email_or_id'";
            $sql_verify = "SELECT * FROM verify WHERE email='$email_or_id'"; //+
        } else {
            $sql = "SELECT * FROM users WHERE idNumber='$email_or_id'";
            $sql_verify = "SELECT * FROM verify WHERE idNumber='$email_or_id'"; //+
        }

        $result = mysqli_query($conn, $sql);
        $result_verify = mysqli_query($conn, $sql_verify);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            if (empty($row['password']) || $row['password'] === null) {
                //checks if input kay email or idnumber
                if (filter_var($email_or_id, FILTER_VALIDATE_EMAIL)) {
                    $identifier = urlencode($email_or_id);
                } else {
                    $idNumberInput = $email_or_id;
                    $stmt = $conn->prepare("SELECT email FROM users WHERE idNumber = ?");
                    $stmt->bind_param("s", $idNumberInput);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        //Fetch the email of that id number
                        $row = $result->fetch_assoc();
                        $identifier = urlencode($row['email']);
                    } else {
                        die("ID number not found.");
                    }
                    $stmt->close();
                    $conn->close();
                }
                
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        alert('Your password has been reset. Initial password setup required.');
                        // window.location.href = 'passwordReset.php?identifier=$identifier';
                        window.location.href = 'admin_reset_pass.php?identifier=$identifier';
                    });
                </script>";
            } else if (password_verify($password, $row['password'])) {
                $_SESSION['valid'] = $row['email'];
                $_SESSION['username'] = $row['firstName'] . ' ' . $row['lastName'];
                $_SESSION['firstName'] = $row['firstName'];
                $_SESSION['lastName'] = $row['lastName'];
                $_SESSION['course'] = $row['course'];
                $_SESSION['idNumber'] = $row['idNumber'];
                $_SESSION['userType'] = $row['userType'];
                $_SESSION['id'] = $row['Id'];

                $sql = "SELECT * FROM users WHERE userType='" . $row['userType'] . "'";

                $_SESSION['login_success'] = true;
                //LOGIN MODAL (echo...)
                if ($_SESSION['userType'] == "Admin") {
                    $hashedPasswordFromDB = $row['password'];
                    if (password_verify($password, $hashedPasswordFromDB)) {
                        // Password matches for admin
                        echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                        showSuccessModal();
                        setTimeout(function() {
                            window.location.href = '../users/admin.php';
                        }, 1500);
                        });
                        </script>";
                    } else {
                        $errors[] = 'Wrong Password';
                    }
                } elseif ($_SESSION['userType'] === "Student") {
                    //student homepage
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showSuccessModal();
                            setTimeout(function() {
                                window.location.href = '../users/studentHomepage.php';
                            }, 1500);
                            });
                        </script>";
                } elseif ($_SESSION['userType'] === "Faculty") {
                    //faculty homepage
                    echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showSuccessModal();
                        setTimeout(function() {
                            window.location.href = '../users/facultyHomepage.php';
                        }, 1500);
                        });
                        </script>";
                } else {
                    $errors[] = 'Unknown user type';
                }
            } else {
                $errors[] = 'Wrong Password';
            }
        } elseif ($result_verify && mysqli_num_rows($result_verify) == 1) {
            // User found in verify table
            $row = mysqli_fetch_assoc($result_verify);
            $_SESSION['verify_user'] = true;
            if ($row['userType'] == 'Faculty' || $row['userType'] == 'Student') {
                header("Location: ../users/verifyFaculty.php"); // Redirect to verifyFaculty.php for non-verified faculty users
                exit();
            }
        } else {
            $errors[] = 'User not found';
        }
    }
}
if (isset($_POST['submit_newpass'])) {
    $errors = [];

    $email_or_id = mysqli_real_escape_string($conn, $_POST['email_or_id']);
    $password = mysqli_real_escape_string($conn, $_POST['newpassword']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['cpassword']);

    if (empty($email_or_id)) {
        $errors[] = 'Email or ID Number is required.';
    }

    if (empty($password)) {
        $errors[] = 'New Password is required.';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        if (filter_var($email_or_id, FILTER_VALIDATE_EMAIL)) {
            $sql = "SELECT * FROM users WHERE email='$email_or_id'";
        } else {
            $sql = "SELECT * FROM users WHERE idNumber='$email_or_id'";
        }

        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $user_id = $row['Id'];

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $update_sql = "UPDATE users SET password='$hashed_password' WHERE Id={$row['Id']}";

            if (mysqli_query($conn, $update_sql)) {

                $reset_sql = "UPDATE users SET reset_token_hash=NULL, reset_token_expires_at=NULL WHERE Id={$row['Id']}";
                mysqli_query($conn, $reset_sql);

                $successes[] = 'Password Updated Successfully';

                $_POST['email_or_id'] = '';
                $_POST['newpassword'] = '';
                $_POST['cpassword'] = '';

                header('Location: login.php');
                exit();
            } else {
                $errors[] = 'Error Updating Password: ' . mysqli_error($conn);

                $_POST['email_or_id'] = '';
                $_POST['newpassword'] = '';
                $_POST['cpassword'] = '';
            }
        } else {

            $errors[] = 'User not found';

            $_POST['newpassword'] = '';
            $_POST['cpassword'] = '';
        }
    }


    mysqli_close($conn);
}



//reset by the admin
if (isset($_POST['submit_newResetPass'])) {
    $errors = [];

    $email_or_id = mysqli_real_escape_string($conn, $_POST['email_or_id']);
    $password = mysqli_real_escape_string($conn, $_POST['newpassword']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['cpassword']);

    if (empty($email_or_id)) {
        $errors[] = 'Email or ID Number is required.';
    }

    if (empty($password)) {
        $errors[] = 'New Password is required.';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        if (filter_var($email_or_id, FILTER_VALIDATE_EMAIL)) {
            $sql = "SELECT * FROM users WHERE email='$email_or_id'";
        } else {
            $sql = "SELECT * FROM users WHERE idNumber='$email_or_id'";
        }

        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $user_id = $row['Id'];

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $update_sql = "UPDATE users SET password='$hashed_password' WHERE Id={$row['Id']}";

            if (mysqli_query($conn, $update_sql)) {

                $reset_sql = "UPDATE users SET reset_token_hash=NULL, reset_token_expires_at=NULL WHERE Id={$row['Id']}";
                mysqli_query($conn, $reset_sql);

                $successes[] = 'Password Updated Successfully';

                $_POST['email_or_id'] = '';
                $_POST['newpassword'] = '';
                $_POST['cpassword'] = '';

                header('Location: loginPhp.php');
                exit();
            } else {
                $errors[] = 'Error Updating Password: ' . mysqli_error($conn);

                $_POST['email_or_id'] = '';
                $_POST['newpassword'] = '';
                $_POST['cpassword'] = '';
            }
        } else {

            $errors[] = 'User not found';

            $_POST['newpassword'] = '';
            $_POST['cpassword'] = '';
        }
    }


    mysqli_close($conn);
}
