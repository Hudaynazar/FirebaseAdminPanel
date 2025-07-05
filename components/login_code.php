<?php
    session_start();
    include('dbcon.php');

    if(isset($_POST['login_btn'])){
        $email = $_POST['email'];
        $clearTextPassword = $_POST['password'];

        try {
            $user = $auth->getUserByEmail($email);
            try{
                $signInResult = $auth->signInWithEmailAndPassword($email, $clearTextPassword);
                $idTokenString = $signInResult -> idToken();;

                try {
                    $verifiedIdToken = $auth->verifyIdToken($idTokenString);
                    $uid = $verifiedIdToken->claims()->get('sub');

                    $_SESSION['verified_user_id'] = $uid;
                    $_SESSION['idTokenString'] = $idTokenString;

                    $_SESSION['status'] = 'Basariyla Giris Yapildi';
                    header('Location: ../adminPanel/dashboard.php');
                    exit();

                } catch (FailedToVerifyToken $e) {
                    header('Location: logout.php');
                    echo 'The token is invalid: '.$e->getMessage();
                    exit();
                }
            }catch(Exception $e){
                $_SESSION['status'] = 'Sifre Yanlis';
                header('Location: ../adminPanel/login.php');
                exit();
            }
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
            $_SESSION['status'] = 'Email yanlis girdiniz';
            header('Location: ../adminPanel/login.php');
            exit();
        }
    }else{
        $_SESSION['status'] = 'Email yanlis girdiniz';
        header('Location: ../adminPanel/login.php');
        exit();
    }
?>