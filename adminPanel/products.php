<?php
    include('../components/authentication.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Ürünler</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="products.css">
    <link
      href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css"
      rel="stylesheet"
    />
</head>
<body>
    <?php include('sidebar.php'); ?>
    <div class="container">
        <div class="header">
            <h1>Ürünler</h1>
        </div>
        <div class="status">
            <a href="newProduct.php" class="button" style="background: #079402FF;" id="newProductBtn">+ Yeni Ürün</a>
            <?php
                if(isset($_SESSION['status'])){
                    echo "<h5 class='button' id='statusTxt'>".$_SESSION['status']."</h5>";
                    unset($_SESSION['status']);  
                }
            ?>
        </div>
        <h2>Mevcut Ürünler</h2>
        <div class="products">
            <?php
                include('../components/dbcon.php');
                $ref_table = 'products';
                $fetch_data = $database->getReference($ref_table)->orderByChild('timestamp')->getValue();

                if ($fetch_data > 0) {
                    $fetch_data = array_reverse($fetch_data);
                    $i = 1;
                    foreach ($fetch_data as $key => $row) {
                        ?>
                        <div class="product">
                            <div class="content0">
                                <h2> <?= $i++; ?></h2>
                                <div class="content1">
                                    <div class="form-group">
                                        <img id="image-preview" src="<?= $row['image'] ?>" alt="Mevcut Resim" style="display:block; width: 100px; height: 50px;"/>
                                    </div>
                                    <div class="product-content">
                                        <p><b>İsim:</b> <?= $row['product_name'] ?></p>
                                        <p><b>Fiyat: </b><?= $row['price'] ?>$</p>
                                    </div>
                                </div>
                            </div>
                            <div class="buttons">
                                <a href="editProduct.php?id=<?=$key;?>" class="button" id="edit_btn">Düzenle</a>
                                <form action="../components/code.php" method="POST">
                                    <button type="submit" name="delete_btn" class="button" style="background: #dc3545;" value="<?=$key;?>">Sil</button>
                                </form>
                            </div>  
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <p>Hiç Bir Ürün Bulunamadı</p>
                    <?php
                }
            ?>
        </div>
    </div>
</body>
</html>
