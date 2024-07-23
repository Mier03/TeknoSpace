<?php

// Reset Password
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['resetpasswordbyadmin'])) {
    $userId = $_POST['resetpasswordbyadmin'];

    // Check if the password is already reset 
    $checkSql = "SELECT password FROM users WHERE Id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $userId);
    $checkStmt->execute();
    $checkStmt->bind_result($password);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($password === NULL || $password === '') {
        echo "<script>alert('Password reset already.');</script>";
    } else {

        $sql = "UPDATE users SET password = NULL WHERE Id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            echo "<script>alert('Password reset successfully.');</script>";
            $_SESSION['password_reset'] = true;
        } else {
            echo "<script>alert('Error resetting password: " . $conn->error . "');</script>";
        }

        $stmt->close();
    }


    $conn->close();
}
