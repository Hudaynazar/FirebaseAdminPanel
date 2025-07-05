<?php
    include('../components/authentication.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="products.css">
    <link rel="stylesheet" href="sidebar.css">
    <link
      href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css"
      rel="stylesheet"
    />
    <title>Document</title>
</head>
<body>
    <?php include('sidebar.php'); ?>
    
    <div class="container">
        <div class="header">
            <h1>Şifre Değiştir</h1>
        </div>
        <?php
                if(isset($_SESSION['status'])){
                    echo "<h5 class='button' id='statusTxt' style='color: green;'>".$_SESSION['status']."</h5>";
                    unset($_SESSION['status']);  
                }
            ?>
        <form action="../components/code.php" method="POST" >
            <?php
                $users = $auth->listUsers();

                $dbPassword;
                $id;
                foreach($users as $user){
                    $dbPassword = $user->passwordHash;
                } 
            ?>
            <div class="form-group">
                <label for="lastPassword">Eski şifre:</label>
                <input type="number" id="lastPassword" name="lastPassword" required value="<?= $dbPassword; ?>">
            </div>
            <div class="form-group">
                <label for="newPassword">Yeni Şifre:</label>
                <input type="number" id="newPassword" name="newPassword" required>
            </div>
            <div class="form-group">
                <label for="newPasswordAgain">Yeni Şifreyi Tekrarlayın:</label>
                <input type="number" id="newPasswordAgain" name="newPasswordAgain" required>
            </div>
            <button type="submit" name="savePassword" class="button">Kaydet</button>
        </form>
    </div>
</body>
</html>