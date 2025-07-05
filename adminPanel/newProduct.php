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
            <h1>Yeni Ürün Ekle</h1>
        </div>
        <form action="../components/code.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="product-name">Ürün Adı:</label>
                <input type="text" id="product-name" name="product_name" required>
            </div>
            <div class="form-group">
                <label for="product-description">Ürün Açıklaması:</label>
                <textarea id="product-description" rows="4" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="product-price">Ürün Fiyatı:</label>
                <input type="number" id="product-price" name="price" required>
            </div>
            <div class="form-group">
                <label for="product-size">Ürün Beden:</label>
                <input type="text" id="product-size" name="size" required>
            </div>
            <div class="form-group">
                <label for="product-color">Ürün Renk:</label>
                <input type="text" id="product-color" name="color" required>
            </div>
            <div class="form-group">
                <label for="product-image">Ürün Resmi:</label>
                <input type="file" id="product-image" name="image" accept="image/*" required onchange="previewImage(event)">
            </div>
            <div class="form-group">
                <img id="image-preview" src="" alt="Resim Önizleme" style="display:none; max-width: 200px;"/>
            </div>
            <button type="submit" name="saveProduct" class="button">Ürün Ekle</button>
        </form>
    </div>

    <script>
    function previewImage(event) {
        const imagePreview = document.getElementById('image-preview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            }

            reader.readAsDataURL(file);
        } else {
            imagePreview.src = '';
            imagePreview.style.display = 'none';
        }
    }
</script>

</body>
</html>