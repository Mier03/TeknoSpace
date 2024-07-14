<?php
require_once '../phpmailer/src/PHPMailer.php';
require_once '../phpmailer/src/Exception.php';
require_once '../phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function initializePHPMailer() {
    $mail = new PHPMailer(true); // Enable verbose debug output
    $mail->isSMTP();
    $mail->Host = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth = true;
    $mail->Username = '3f5c64e3d2c216';
    $mail->Password = '114937a1308f7e';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port = 2525; // TCP port to connect to
    $mail->setFrom('info@teknospace.com', 'Teknospace');
    $mail->isHTML(true);
    return $mail;
}
?>