<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Üye Olun</title>
</head>
<body>
    <div class="container">
        <h3>Üye Olun</h3>
        <?php
                if(isset($_SESSION['status'])){
                    echo "<h5 class='button' id='statusTxt'>".$_SESSION['status']."</h5>";
                    unset($_SESSION['status']);  
                }
            ?>
        <form id="registerForm" action="../components/code.php" method="POST" >
            <div class="form">
                <label>Email</label>
                <input type="email" id="email" placeholder="E-Posta" name="email" required>
            </div>
            <div class="form">
                <label>Password</label>
                <input type="password" id="password" placeholder="Şifre" name="password" required>
            </div>
            <button id="registerBtn" type="submit" name="registerBtn">Üye Ol</button>
        </form>
        <hr>
        <p class="center-text">
            Eğer üye iseniz hemen <a href="login.php">Giriş Yapın</a>
        </p>
    </div>
    
</body>
</html>
