<?php
session_start();
if (isset($_SESSION["username"])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reset password</title>
</head>

<body>
    <form action="proses_reset.php" method="post">
        <h1>ganti passowrd</h1>
        <?php
        if (isset($_SESSION['log'])) {
        ?>
            <script>
                alert('<?php echo $_SESSION['log']; ?>');
                window.location.href = 'loginPage.php';
            </script>
        <?php
            session_unset();
        } ?>
        <div class="infield">
            <input type="password" placeholder="Password Baru" name="password" id="password" required />
            <label>password baru</label>
        </div>
        <button type="submit">kirim</button>
    </form>
</body>

</html>