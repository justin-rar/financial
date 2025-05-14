<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "login_form";

$conn = new mysqli($host, $user, $password, $dbname);

if($conn->connect_error){
    die("Connection Error");
}

?>