<?php
require('connect.php');
require('sendmailverify.php');
session_start();

function generateRandomString($length){
    $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $charactersLength = strlen($characters);
    $randomString = '';

    for($i = 0; $i < $length; $i++){
        $randomString .= $characters[rand(0, $charactersLength-1)];
    }
    return $randomString;
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $email = $_POST['email'];
    $code_reset = generateRandomString(6);
    $email_template = "
        <h2>Berikut kode reset password</h2>
        <br/>
        <h4>$code_reset</h4>
        <br/>
        <p>kode tersebut hanya berlaku satu kali, silahkan gunakan dengan sebaik mungkin!</p>
    ";

    $query = "SELECT * FROM tb_account WHERE email = '$email'";
    $sql = mysqli_query($conn, $query);
    $result = mysqli_fetch_assoc($sql);

    if($result) {
        if($result['reset_pascode'] != '0'){
            $_SESSION['status'] = "Kode sudah dikirimkan!";
            header("Location: forgot_pass.php");
            exit();
        } else {
            $query = "UPDATE tb_account SET reset_pascode = '$code_reset' WHERE email = '$email'";
            if($conn->query($query) === TRUE ){
                sendmail_verify($email, $code_reset, $email_template);
                $_SESSION['status'] = "Kode reset sudah dikirimkan!";
                header("Location: inputpass.php");
                exit();
            } else {
                $_SESSION['status'] = "Terjadi kesalahan saat mengupdate kode reset!";
                header("Location: forgot_pass.php");
                exit();
            }
        }
    } else {
        $_SESSION['status'] = "Email belum terdaftar!";
        header("Location: forgot_pass.php");
        exit();
    }
    $conn->close();
}
?>