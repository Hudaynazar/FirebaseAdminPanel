<?php
    session_start();
    unset($_SESSION['verified_user_id']);
    unset($_SESSION['idTokenString']);

    if(isset($_SESSION['expiry_status'])){
        $_SESSION['status'] = "Session expired";
    }else{
        $_SESSION['status'] = "Cikis Yapildi";
    }

    $_SESSION['status'] = 'Basariyla Cikis Yapildi';
    header('Location: ../adminPanel/login.php');
    exit();
?>