<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require('connect.php');
session_start();


function sendmail_verify($email, $verify_token, $email_template)
{
    //Load Composer's autoloader (created by composer, not included with PHPMailer)
    require 'vendor/autoload.php';
    $mail = new PHPMailer(true);

    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'justinmr634@gmail.com';                     //SMTP username
    $mail->Password   = 'gatqbgdzqafuinlm';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('justinmr634@gmail.com', 'BEBET');
    $mail->addAddress($email, 'User');     //Add a recipient
    $mail->addReplyTo('justinmr634@gmail.com', 'BEBET');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Verifikasi email';
    $mail->Body    = $email_template;

    $mail->send();
    echo 'Email terkirim';
}
?>