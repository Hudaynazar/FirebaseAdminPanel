<?php
    include('../components/authentication.php');
    $ref_table = 'messages';
    $fetch_data = $database->getReference($ref_table)->orderByChild('timestamp')->getValue();
    $status_filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

    $filtered_data = [];
    if ($fetch_data) {
        $fetch_data = array_reverse($fetch_data);
        foreach ($fetch_data as $key => $row) {
            if ($status_filter === 'unread' && $row['status'] === 'unread') {
                $filtered_data[$key] = $row;
            } elseif ($status_filter === 'read' && $row['status'] === 'read') {
                $filtered_data[$key] = $row;
            } elseif ($status_filter === 'all') {
                $filtered_data[$key] = $row;
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $key = $_POST['key'];
        $status = $_POST['status'];

        $database->getReference('messages/'.$key)->update([
            'status' => $status
        ]);
        
        echo "Durum güncellendi.";
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
        <title>Mesajlar</title>
    </head>
    <body>
        <?php include('sidebar.php'); ?>
        <div class="container">
            <div class="header">
                <h1>Mesajlar</h1>
            </div>
            
            <div class="filters">
                <a href="?filter=all" class="filterButton <?= $status_filter === 'all' ? 'active' : '' ?>">Tüm Mesajlar</a>
                <a href="?filter=unread" class="filterButton <?= $status_filter === 'unread' ? 'active' : '' ?>">Okunmamış Mesajlar</a>
                <a href="?filter=read" class="filterButton <?= $status_filter === 'read' ? 'active' : '' ?>">Okunmuş Mesajlar</a>
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
                                <h2>Mesaj <?= $i++; ?></h2>
                                <p><b>Gönderici Ismi:</b> <?= $row['sender'] ?></p>
                            </div>
                            <div class="buttons">
                                <button id="detailBtn" class="button" onclick="showModal(<?= htmlspecialchars(json_encode($row)) ?>)">Detaylar</button>
                                <form action="../components/code.php" method="POST">
                                    <button type="submit" name="delete_btn" class="button" style="background: #dc3545;" value="<?=$key;?>">Sil</button>
                                </form>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <p>Hiçbir Mesaj Bulunamadı</p>
                    <?php
                }
                ?>
                    
            </div>
        </div>

        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Mesaj Detayları</h2>
                <p><b>Mesaj Göndericisi:</b> <span id="sender"></span></p>
                <p><b>Mesaj İçeriği:</b> <span id="message"></span></p>
                <p><b>Mesaj Tarihi:</b> <span id="messageDate"></span></p>
            </div>
        </div>

        <script>
            function showModal(message) {
                document.getElementById('sender').textContent = message.sender;
                document.getElementById('message').textContent = message.message;
                document.getElementById('messageDate').textContent = message.date;
                
                updatemessagestatus(message.id);

                document.getElementById('myModal').style.display = "block";
            }

            function closeModal() {
                document.getElementById('myModal').style.display = "none";
                if (location.href.includes('messages.php?filter=unread')) {
                    location.reload();
                }
            }

            window.onclick = function(event) {
                if (event.target == document.getElementById('myModal')) {
                    closeModal();
                }
            }

            function updatemessagestatus(messageKey) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "messages.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        console.log("Sipariş durumu güncellendi.");
                    } else {
                        console.error("Güncelleme başarısız.");
                    }
                };
                xhr.send("key=" + encodeURIComponent(messageKey) + "&status=read");
            }
        </script>
    </body>
</html>
