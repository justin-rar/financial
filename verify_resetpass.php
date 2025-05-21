<?php
require('connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $code = $_POST['code'];

    $query = "SELECT * FROM tb_account WHERE reset_pascode = '$code'";
    $sql = mysqli_query($conn, $query);
    $result = mysqli_fetch_assoc($sql);

    if($result){
        $_SESSION['code'] = $code;
        header('Location: resetpassword.php');
        exit();
    } else {
        $_SESSION['status'] = "Kode tidak sesuai!";
        header('Location: inputpass.php');
        exit();
    }
}
?>