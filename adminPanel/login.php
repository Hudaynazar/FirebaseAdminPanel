<?php
    session_start();
    if(isset($_SESSION['verified_user_id'])){
        $_SESSION['status'] = 'Giris Durumu Zaten Aktif';
        header('Location: dashboard.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Giriş Yapın</title>
    
</head>
<body>
    <div class="container">
        <h3>Giriş Yapın</h3>
        <?php
                if(isset($_SESSION['status'])){
                    echo "<h5 class='button' style='color: red;'>".$_SESSION['status']."</h5>";
                    unset($_SESSION['status']);  
                }
            ?>
        <form id="loginForm" action="../components/login_code.php" method="POST" >
            <div class="form">
                <label>Email</label>
                <input type="email" id="email" name="email" placeholder="E-Posta" required>
            </div>
            <div class="form">
                <label>Password</label>
                <input type="password" id="password" name="password" placeholder="Şifre" required>
            </div>
            <button id="loginBtn" type="submit" name="login_btn">Giriş Yap</button>
        </form>
        <hr>
        <p class="center-text">
            Eğer üye değilseniz hemen <a href="register.php">Üye Olun</a>
        </p>
    </div>
</body>
</html>
