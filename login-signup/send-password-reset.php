<?php

$conn = require __DIR__ . '/../config.php';

$email = $_POST["email"];

$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

$sql = "UPDATE users
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE email = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("sss", $token_hash, $expiry, $email);

$stmt->execute();

if ($conn->affected_rows) {
    // Send email using mailer.php
    require __DIR__ . '/mailer.php';
    $result = sendPasswordResetEmail($email, $token);

    if ($result === true) {
        header('Location: mailsent.php');
    } else {
        return $result; // Error message from mailer.php 
    }
} else {
    header('Location: mailerror.php');
}

$stmt->close();
$conn->close();
?>
