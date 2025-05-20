<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require('connect.php');
session_start();


function sendmail_verify($email, $verify_token)
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
    $mail->setFrom('from@example.com', 'BEBET');
    $mail->addAddress('gustinmr7@gmail.com', 'User');     //Add a recipient
    $mail->addReplyTo('no-reply@example.com', 'Information');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $email_template = "
    <h2>Anda telah melakukan pendaftaran akun bebet</h2>
    <h4>Verifikasi emailmu agar dapat login, klik tautan berikut!</h4>
    <a href='http://localhost/LOGIN/verify_email.php?token=$verify_token'>[klik di sini] </a>
    ";
    $mail->Subject = 'Verifikasi email';
    $mail->Body    = $email_template;

    $mail->send();
    echo 'Email terkirim';
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    $query = "SELECT * FROM tb_account WHERE username = '$username'";
    $sql = mysqli_query($conn, $query);
    $result = mysqli_fetch_assoc($sql);

    $verify_token = md5(rand());

    if ($result) {
        $_SESSION['log'] = "username already used!";
        header('Location: loginPage.php');
    } else {
        $query = "INSERT INTO tb_account(username, password, email, verify_token, verify_status, reset_pascode) VALUES ('$username', '$password', '$email', '$verify_token', '0', '0')";
        if ($conn->query($query) === TRUE) {
            sendmail_verify($email, $verify_token);
            echo "<script>
            alert('Account succesfully added. please verify email first');
            window.location.href = 'loginPage.php';
            </script>";
        }
    }
    $conn->close();
}
