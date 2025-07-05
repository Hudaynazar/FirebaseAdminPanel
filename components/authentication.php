<?php
    session_start();
    include('dbcon.php');

    if(isset($_SESSION['verified_user_id'])){
        $uid = $_SESSION['verified_user_id'];
        $idTokenString = $_SESSION['idTokenString'];

        try {
            $verifiedIdToken = $auth->verifyIdToken($idTokenString);
        } catch (InvalidToken $e) {
            $_SESSION['expiry_status'] = 'Token ile Giris yapilamadi. Tekrar deneyin';
            header('Location: logut.php');
            exit();
        }catch(\InvalidArgumentException $e){
            echo 'The Token Couldnt be parsed: ' .$e -> getMessage();
        }
    }else{
        $_SESSION['status'] = 'Bu sayfaya ulasmak icin giris yapin';
            header('Location: login.php');
            exit();
    }
?>