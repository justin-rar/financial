<?php
require('connect.php');
require('sendmailverify.php');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    // Cek apakah username sudah ada
    $check_username = "SELECT * FROM tb_account WHERE username = '$username'";
    $check_email = "SELECT * FROM tb_account WHERE email = '$email'";

    $result_username = mysqli_query($conn, $check_username);
    $result_email = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($result_username) > 0) {
        echo "<script>
        alert('Username sudah digunakan!');
        window.location.href = 'loginPage.php';
        </script>";
    } else if (mysqli_num_rows($result_email) > 0) {
        echo "<script>
        alert('Email sudah terdaftar!');
        window.location.href = 'loginPage.php';
        </script>";
    } else {
        $verify_token = md5(rand());
        $email_template = "
        <h2>Anda telah melakukan pendaftaran akun bebet</h2>
        <h4>Verifikasi emailmu agar dapat login, klik tautan berikut!</h4>
        <a href='http://localhost/financial/verify_email.php?token=$verify_token'>[klik di sini]</a>
        ";

        $query = "INSERT INTO tb_account(username, password, email, verify_token, verify_status, reset_pascode)
                  VALUES ('$username', '$password', '$email', '$verify_token', '0', '0')";

        if ($conn->query($query) === TRUE) {
            sendmail_verify($email, $verify_token, $email_template);
            echo "<script>
            alert('Akun berhasil dibuat. Silakan verifikasi email terlebih dahulu.');
            window.location.href = 'loginPage.php';
            </script>";
        } else {
            echo "<script>
            alert('Terjadi kesalahan saat menyimpan data.');
            window.location.href = 'loginPage.php';
            </script>";
        }
    }

    $conn->close();
}
?>
