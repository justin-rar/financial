<?php
require('sendmailverify.php');
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    $query = "SELECT * FROM tb_account WHERE username = '$username'";
    $sql = mysqli_query($conn, $query);
    $result = mysqli_fetch_assoc($sql);

    $verify_token = md5(rand());
    $email_template = "
    <h2>Anda telah melakukan pendaftaran akun bebet</h2>
    <h4>Verifikasi emailmu agar dapat login, klik tautan berikut!</h4>
    <a href='http://localhost/LOGIN/verify_email.php?token=$verify_token'>[klik di sini] </a>
    ";

    if ($result) {
        $_SESSION['log'] = "username already used!";
        header('Location: loginPage.php');
    } else {
        $query = "INSERT INTO tb_account(username, password, email, verify_token, verify_status, reset_pascode) VALUES ('$username', '$password', '$email', '$verify_token', '0', '0')";
        if ($conn->query($query) === TRUE) {
            sendmail_verify($email, $verify_token, $email_template);
            echo "<script>
            alert('Account succesfully added. please verify email first');
            window.location.href = 'loginPage.php';
            </script>";
        }
    }
    $conn->close();
}
