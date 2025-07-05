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
      rel="stylesheet"/>
    <title>Document</title>
</head>
<body>
    <?php include('sidebar.php'); ?>
    
    <div class="container">
        <div class="header">
            <h1>Ürünü Düzenle</h1>
        </div>
        <?php 
            include('../components/dbcon.php');
            if(isset($_GET['id'])){
                $key_child = $_GET['id'];
                $ref_table = 'products';
                $get_data = $database->getReference($ref_table)->getChild($key_child)->getValue();
                if($get_data > 0){
                    ?>
                        <form action="../components/code.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?= $key_child ?>">
                            <div class="form-group">
                                <label for="product-name">Ürün Adı:</label>
                                <input type="text" id="product-name" name="product_name" value="<?= $get_data['product_name'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="product-description">Ürün Açıklaması:</label>
                                <textarea id="product-description" rows="4" name="description" required><?= $get_data['description'] ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="product-price">Ürün Fiyatı:</label>
                                <input type="number" id="product-price" name="price" value="<?= $get_data['price'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="product-size">Ürün Beden:</label>
                                <input type="text" id="product-size" name="size" value="<?= $get_data['size'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="product-color">Ürün Renk:</label>
                                <input type="text" id="product-color" name="color" value="<?= $get_data['color'] ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="new-product-image">Ürün Resmi(Eski):</label>
                                <img id="image-preview" src="<?= $get_data['image'] ?>" alt="Mevcut Resim" style="display:block; max-width: 200px;"/>
                            </div>
                            <div class="form-group">
                                <label for="new-product-image">Ürün Resmi(Yeni):</label>
                                <input type="file" id="new-product-image" name="new_image" accept="image/*" onchange="previewNewImage(event)">
                            </div> 
                            <div class="form-group">
                                <img id="new-image-preview" src="" alt="Yeni Resim Önizleme" style="display:none; max-width: 200px;"/>
                            </div>
                            <button type="submit" name="updateProduct" class="button">Kaydet</button>
                        </form>
                    <?php
                }else{
                    $_SESSION['status'] = 'Id Invalid';
                    header('Location: products.php');
                    exit();
                }
            }else{
                $_SESSION['status'] = 'No Found';
                header('Location: products.php');
                exit();
            }
        ?>
        
    </div>
    <script>
    function previewImage(event) {
        const imagePreview = document.getElementById('image-preview');
        const imageUrl = event.target.value;

        // Mevcut resmi göster
        imagePreview.src = imageUrl;
        imagePreview.style.display = 'block';
    }

    function previewNewImage(event) {
        const newImagePreview = document.getElementById('new-image-preview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                newImagePreview.src = e.target.result;
                newImagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            newImagePreview.src = '';
            newImagePreview.style.display = 'none';
        }
    }
</script>

</body>
</html>