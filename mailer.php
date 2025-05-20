<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader (created by composer, not included with PHPMailer)
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings                     //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'justinmr634@gmail.com';                     //SMTP username
    $mail->Password   = 'gatqbgdzqafuinlm';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('from@example.com', 'BEBET');
    $mail->addAddress('gustinmr7@gmail.com', 'User');     //Add a recipient
    $mail->addReplyTo('no-reply@example.com', 'Information');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $email_template = "
    <h2>Anda telah melakukan pendaftaran akun bebet</h2>
    <h4>Verifikasi emailmu agar dapat login, klik tautan berikut!</h4>
    <a href=\"#\">[klik di sini] </a>
    " ;
    $mail->Subject = 'Verifikasi email';
    $mail->Body    = $email_template;

    $mail->send();
    echo 'Email terkirim';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}