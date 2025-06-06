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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in || Sign up from</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/loginStyle.css">
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="regist.php" method="post" >
                <h1>Register</h1>
                <span>Buat akunmu untuk memulai</span>

                <?php
                if (isset($_SESSION['log'])) {
                ?>
                    <script>
                        alert('username already exist!');
                        window.location.href = 'loginPage.php';
                    </script>
                <?php 
                session_unset(); } ?>

                <div class="infield">
                    <input type="text" placeholder="Name" name="username" required />
                    <label></label>
                </div>
                <div class="infield">
                    <input type="email" placeholder="Email" name="email" id="email" required/>
                    <label></label>
                </div>
                <div class="infield">
                    <input type="password" placeholder="Password" name="password" id="password" required/>
                    <label></label>
                </div>
                <button type="submit">Sign Up</button>
            </form>
        </div>

        <div class="form-container sign-in-container">
            <form action="login.php" method="post">
                <h1>Sign in</h1>
                <span>Gunakan akunmu untuk masuk</span>
                <?php
                if (isset($_SESSION['status'])) {
                ?>
                    <script>
                        alert('<?php echo $_SESSION['status']; ?>');
                        window.location.href = 'loginPage.php';
                    </script>
                <?php 
                session_unset(); } ?>
                <div class="infield">
                    <input type="text" placeholder="Username" name="username" required/>
                    <label></label>
                </div>
                <div class="infield">
                    <input type="password" placeholder="Password" name="password" required/>
                    <label></label>
                </div>
                <a href="forgot_pass.php" class="forgot">Lupa password?</a>
                <button type="submit">Sign In</button>
            </form>
        </div>
        <div class="overlay-container" id="overlayCon">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>Terus terhubung dengan kami melalui login aplikasi</p>
                    <button>Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Bebeters!</h1>
                    <p>Masukkan info personalmu untuk bergabung dengan kami</p>
                    <button>Sign Up</button>
                </div>
            </div>
            <button id="overlayBtn"></button>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- js code -->
    <script>
        const container = document.getElementById('container');
        const overlayCon = document.getElementById('overlayCon');
        const overlayBtn = document.getElementById('overlayBtn');

        overlayBtn.addEventListener('click', ()=>{
            container.classList.toggle('right-panel-active');
        });

        overlayBtn.classList.remove('btnScaled');
        window.requestAnimationFrame( ()=> {
            overlayBtn.classList.add('btnScaled');
        });
    </script>

</body>
</html>