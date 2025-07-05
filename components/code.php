<?php
    session_start();
    include('dbcon.php');

    if (isset($_POST['savePassword'])) {
        // Kullanıcı kimliğini al
        $id = $_SESSION['verified_user_id'];
    
        // Kullanıcıyı veritabanından al
        $user = $auth->getUser($id);
    
        if ($user) {
            // Formdan gelen verileri al
            $lastPassword = $_POST['lastPassword'];
            $newPassword = $_POST['newPassword'];
            $newPasswordAgain = $_POST['newPasswordAgain'];
    
            // Eski şifreyi doğrula
            try {
                $auth->signInWithEmailAndPassword($user->email, $lastPassword);
    
                if ($newPassword === $newPasswordAgain) {
                    if (strlen($newPassword) >= 6) { // Şifre uzunluğunu kontrol et
                        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    
                        // Kullanıcı bilgilerini güncelle
                        $properties = [
                            'password' => $newPasswordHash,
                        ];
                        $updateUserResult = $auth->updateUser($id, $properties);
    
                        if ($updateUserResult) {
                            $_SESSION['status'] = 'Şifre başarıyla değiştirildi';
                        } else {
                            $_SESSION['status'] = 'Şifre değiştirme başarısız';
                        }
                    } else {
                        $_SESSION['status'] = 'Şifreniz en az 6 haneli olmalıdır';
                    }
                } else {
                    $_SESSION['status'] = 'Yeni şifreler eşleşmiyor';
                }
            } catch (\Kreait\Firebase\Auth\Exception\InvalidPassword $e) {
                $_SESSION['status'] = 'Eski şifreyi yanlış girdiniz';
            } catch (\Kreait\Firebase\Auth\Exception\UnknownUser $e) {
                $_SESSION['status'] = 'Kullanıcı bulunamadı';
            } catch (\Kreait\Firebase\Auth\Exception\FailedToSignIn $e) {
                $_SESSION['status'] = 'Giriş başarısız, lütfen bilgilerinizi kontrol edin';
            } catch (Exception $e) {
                $_SESSION['status'] = 'Eski şifreyi yanlış girdiniz';
            }
        } else {
            $_SESSION['status'] = 'Kullanıcı bulunamadı';
        }
    
        // Sonuç sayfasına yönlendir
        header('Location: ../adminPanel/settings.php');
        exit();
    }

    if(isset($_POST['registerBtn'])){
        $email = $_POST['email'];
        $password = $_POST['password'];
        $userProperties = [
            'email' => $email,
            'emailVerified' => false,
            'password' => $password,
            'disabled' => false,
        ];
        
        $createdUser = $auth->createUser($userProperties);
        if(isset($createdUser)){
            $_SESSION['status'] = 'Kullanıcı Başarıyla Kaydedildi';
            header('Location: ../adminPanel/register.php');
            exit();
        }else{
            $_SESSION['status'] = 'Kullanıcı Kaydedilmedi';
            header('Location: ../adminPanel/register.php');
            exit();
        }
    }

    if (isset($_POST['delete_btn'])) {
        $delete_id = $_POST['delete_btn'];
        
        // Ürün verilerini al
        $ref_table = 'products/' . $delete_id;
        $productData = $database->getReference($ref_table)->getValue();
    
        // Resmin yolunu al
        $imagePath = $productData['image'];
    
        // Ürünü sil
        $deleteResult = $database->getReference($ref_table)->remove();
    
        if ($deleteResult) {
            // Resmi dosya sisteminden sil
            if (file_exists($imagePath)) {
                unlink($imagePath); // Resmi sil
            }
            $_SESSION['status'] = 'Başarıyla Silindi';
        } else {
            $_SESSION['status'] = 'Silinemedi';
        }
        
        header('Location: ../adminPanel/products.php');
        exit();
    }
    






    if (isset($_POST['cancel_btn'])) {
        $key = $_POST['cancel_btn'];
        $status = 'canceled';
    
        $ref_table = 'orders/' . $key;
        $existingProduct = $database->getReference($ref_table)->getValue();
        
        $postData = [
            'status' => $status
        ];
    
        $postRef_result = $database->getReference($ref_table)->update($postData);
    
        if ($postRef_result) {
            $_SESSION['status'] = 'Başarıyla Güncellendi';
        } else {
            $_SESSION['status'] = 'Güncellenemedi';
        }
    
        header('Location: ../adminPanel/orders.php');
        exit();
    }








    if (isset($_POST['updateProduct'])) {
    $key = $_POST['id'];
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $size = $_POST['size'];
    $color = $_POST['color'];
    $price = $_POST['price'];

    // Mevcut ürün verilerini alın
    $ref_table = 'products/' . $key;
    $existingProduct = $database->getReference($ref_table)->getValue();
    
    $postData = [
        'product_name' => $product_name,
        'description' => $description,
        'size' => $size,
        'color' => $color,
        'price' => $price,
    ];

    // Resmi yükle
    if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] == UPLOAD_ERR_OK) {
        // Eski resmin yolunu al
        $oldImagePath = $existingProduct['image'];

        $imageTmpPath = $_FILES['new_image']['tmp_name'];
        $imageName = $_FILES['new_image']['name'];
        $imageSize = $_FILES['new_image']['size'];
        $imageType = $_FILES['new_image']['type'];

        // Dosya uzantısını kontrol edin
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($imageType, $allowedTypes)) {
            // Benzersiz bir dosya adı oluşturun
            $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
            $newImageName = uniqid('product_', true) . '.' . $imageExtension;
            $uploadDir = '../images/';
            $imagePath = $uploadDir . $newImageName;

            // Resmi hedef dizine taşıyın
            if (move_uploaded_file($imageTmpPath, $imagePath)) {
                $postData['image'] = $imagePath; // Yüklenen resmin yolunu ekleyin

                // Eski resmi sil
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            } else {
                $_SESSION['status'] = 'Resim yüklenirken hata oluştu';
                header('Location: ../adminPanel/products.php');
                exit();
            }
        } else {
            $_SESSION['status'] = 'Geçersiz dosya türü';
            header('Location: ../adminPanel/products.php');
            exit();
        }
    } else if ($_FILES['new_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Resim seçilmedi veya başka bir hata oluştu
        $_SESSION['status'] = 'Resim seçilmedi veya hata oluştu';
        header('Location: ../adminPanel/products.php');
        exit();
    }

    // Veritabanına güncellemeyi yap
    $postRef_result = $database->getReference($ref_table)->update($postData);

    if ($postRef_result) {
        $_SESSION['status'] = 'Başarıyla Güncellendi';
    } else {
        $_SESSION['status'] = 'Güncellenemedi';
    }

    header('Location: ../adminPanel/products.php');
    exit();
}

    

    if (isset($_POST['saveProduct'])) {
        $product_name = $_POST['product_name'];
        $description = $_POST['description'];
        $size = $_POST['size'];
        $color = $_POST['color'];
        $price = $_POST['price'];
    
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $imageTmpPath = $_FILES['image']['tmp_name'];
            $imageName = $_FILES['image']['name'];
            $imageSize = $_FILES['image']['size'];
            $imageType = $_FILES['image']['type'];
    
            $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
    
            $newImageName = uniqid('product_', true) . '.' . $imageExtension;
    
            $uploadDir = '../images/';
            $imagePath = $uploadDir . $newImageName;
    
            if (move_uploaded_file($imageTmpPath, $imagePath)) {
                $postData = [
                    'product_name' => $product_name,
                    'description' => $description,
                    'size' => $size,
                    'color' => $color,
                    'price' => $price,
                    'image' => $imagePath,
                ];
                $ref_table = 'products';
                $postRef_result = $database->getReference($ref_table)->push($postData);
    
                if (isset($postRef_result)) {
                    $_SESSION['status'] = 'Başarıyla Eklendi';
                    header('Location: ../adminPanel/products.php');
                    exit();
                } else {
                    $_SESSION['status'] = 'Eklenemedi';
                    header('Location: ../adminPanel/products.php');
                    exit();
                }
            } else {
                $_SESSION['status'] = 'Resim yüklenirken hata oluştu';
                header('Location: ../adminPanel/products.php');
                exit();
            }
        } else {
            $_SESSION['status'] = 'Resim seçilmedi veya hata oluştu';
            header('Location: ../adminPanel/products.php');
            exit();
        }
    }
    
?>