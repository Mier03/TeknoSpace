<?php
require_once '../mailconfig.php';

function sendPasswordResetEmail($email, $token) {
    $mail = initializePHPMailer();

    try {

        $mail->addAddress($email);
        $mail->Subject = 'Password Reset';

        $mail->Body = <<<EOT
        Click <a href="http://localhost/TeknoSpace/Login-Signup/forgotPass.php?token=$token">here</a> 
        to reset your password.
        EOT;

        $mail->send();
        return true; // Email sent successfully
        
    } catch (Exception $e) {
        return "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
    }
}

?>
