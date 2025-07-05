<?php
    include('../components/authentication.php');
    $ref_table = 'orders';
    $fetch_data = $database->getReference($ref_table)->orderByChild('timestamp')->getValue();
    $status_filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

    $filtered_data = [];
    if ($fetch_data) {
        foreach ($fetch_data as $key => $row) {
            if ($status_filter === 'notDelivered' && $row['status'] === 'notDelivered') {
                $filtered_data[$key] = $row;
            } elseif ($status_filter === 'delivered' && $row['status'] === 'delivered') {
                $filtered_data[$key] = $row;
            } elseif ($status_filter === 'returned' && $row['status'] === 'returned') {
                $filtered_data[$key] = $row;
            } elseif ($status_filter === 'canceled' && $row['status'] === 'canceled') {
                $filtered_data[$key] = $row;
            }elseif ($status_filter === 'all') {
                $filtered_data[$key] = $row;
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $key = $_POST['key'];
        $status = $_POST['status'];

        $database->getReference('orders/'.$key)->update([
            'status' => $status
        ]);
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="sidebar.css">
        <link rel="stylesheet" href="products.css">
        <link
        href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css"
        rel="stylesheet"
        />
        <title>Siparişler</title>
    </head>
    <body>
        <?php include('sidebar.php'); ?>
        <div class="container">
            <div class="header">
                <h1>Siparişler</h1>
            </div>
            
            <div class="filters">
                <a href="?filter=all" class="filterButton <?= $status_filter === 'all' ? 'active' : '' ?>">Tüm Siparişler</a>
                <a href="?filter=delivered" class="filterButton <?= $status_filter === 'delivered' ? 'active' : '' ?>">İletilmiş Siparişler</a>
                <a href="?filter=notDelivered" class="filterButton <?= $status_filter === 'notDelivered' ? 'active' : '' ?>">İletilmemiş Siparişler</a>
                <a href="?filter=returned" class="filterButton <?= $status_filter === 'returned' ? 'active' : '' ?>">İade Edilmiş Siparişler</a>
                <a href="?filter=canceled" class="filterButton <?= $status_filter === 'canceled' ? 'active' : '' ?>">İptal Edilmiş Siparişler</a>
            </div>
            <div class="products">
                <?php
                if ($filtered_data) {
                    $i = 1;
                    foreach ($filtered_data as $key => $row) {
                        $row['id'] = $key;
                        ?>
                        <div class="product">
                            <div class="content">
                                <div class="headerCont">
                                    <h2>Sipariş <?= $i++; ?></h2>
                                    <?php
                                    if($row['status'] == 'delivered'){ 
                                        ?>
                                        <p style="color: green;">Teslim Edildi</p>
                                        <?php
                                    }else if($row['status'] == 'notDelivered'){ 
                                        ?>
                                        <p style="color: #E670029F;">Yolda</p>
                                        <?php
                                    }else if($row['status'] == 'canceled'){ 
                                        ?>
                                        <p style="color: red;">İptal Edildi</p>
                                        <?php
                                    }else if($row['status'] == 'returned'){ 
                                        ?>
                                        <p style="color: #C5B2059F;">İade Edildi</p>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <p><b>Ürün Ismi:</b> <?= $row['product_name'] ?></p>
                                <p><b>Sipariş Tarihi:</b> <?= $row['orderDate'] ?></p>
                            </div>
                            <div class="buttons">
                                <button id="detailBtn" class="button" onclick="showModal(<?= htmlspecialchars(json_encode($row)) ?>)">Detaylar</button>
                                <form action="../components/code.php" method="POST">
                                    <button type="submit" name="cancel_btn" class="button" style="background: #dc3545;" value="<?=$key;?>">İptal Et</button>
                                </form>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <p>Hiçbir Sipariş Bulunamadı</p>
                    <?php
                }
                ?>
            </div>
        </div>

        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Sipariş Detayları</h2>
                <p><b>Sipariş Adresi:</b> <span id="orderAddress"></span></p>
                <p><b>Ürün Ismi:</b> <span id="productName"></span></p>
                <p><b>Ürün Rengi:</b> <span id="productColor"></span></p>
                <p><b>Ürün Boyu:</b> <span id="productSize"></span></p>
                <p><b>Sipariş Tarihi:</b> <span id="orderDate"></span></p>
            </div>
        </div>

        <script>
            function showModal(order) {

                document.getElementById('orderAddress').textContent = order.adress;
                document.getElementById('productName').textContent = order.product_name;
                document.getElementById('productColor').textContent = order.color;
                document.getElementById('productSize').textContent = order.size;
                document.getElementById('orderDate').textContent = order.orderDate;
                
                updateOrderStatus(order.id);

                document.getElementById('myModal').style.display = "block";
            }

            function closeModal() {
                document.getElementById('myModal').style.display = "none";
                if (location.href.includes('orders.php?filter=notDelivered')) {
                    location.reload();
                }
            }

            window.onclick = function(event) {
                if (event.target == document.getElementById('myModal')) {
                    closeModal();
                }
            }

            function updateOrderStatus(orderKey) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "orders.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        console.log("Sipariş durumu güncellendi.");
                    } else {
                        console.error("Güncelleme başarısız.");
                    }
                };
                xhr.send("key=" + encodeURIComponent(orderKey) + "&status=delivered");
            }
        </script>
    </body>
</html>
