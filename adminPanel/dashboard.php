<?php
include('../components/authentication.php');

// En çok satılan ürünleri bul
$ref_table = 'orders';
$fetch_data = $database->getReference($ref_table)->getValue();
$productCounts = [];

foreach ($fetch_data as $order => $row) {
    if ($row['status'] === 'delivered' && isset($row['product_name'])) {
        $productName = $row['product_name']; 
        if (!isset($productCounts[$productName])) {
            $productCounts[$productName] = 1;
        }
        $productCounts[$productName]++;
    }
}

arsort($productCounts);
$topSellingProducts = array_slice($productCounts, 0, 3, true);

if (empty($topSellingProducts)) {
    echo "En çok satılan ürün bulunamadı.";
}

$mergedProducts = $topSellingProducts; 

// Ciro Grafik
$monthlyRevenue = array_fill(0, 12, 0);
$currentDate = new DateTime();

foreach ($fetch_data as $order => $row) {
    if ($row['orderDate'] && $row['price'] && $row['status'] === 'delivered') {
        $date = new DateTime($row['orderDate']);
        $month = (int)$date->format('n') - 1;

        if ($date->format('Y') == $currentDate->format('Y')) {
            $monthlyRevenue[$month] += $row['price'];
        }
    }
}

$ref_table2 = 'customer_satisfaction';
$satisfaction_data = $database->getReference($ref_table2)->getValue();

$satisfactionCounts = [0, 0, 0];
foreach ($satisfaction_data as $response) {
    if ($response['satisfaction'] == 'unhappy') $satisfactionCounts[0]++;
    elseif ($response['satisfaction'] == 'neutral') $satisfactionCounts[1]++;
    elseif ($response['satisfaction'] == 'happy') $satisfactionCounts[2]++;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="sidebar.css">
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet"/><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Admin Dashboard</title>
</head>
<body>
    <?php include('sidebar.php'); ?>

    <div class="main-content">
        <div class="header">
            <h1>Dashboard</h1>
        </div>
        <div class="cards">
            <!-- Kullanıcı Sayısı -->
            <?php
            $ref_table = 'users';
            $fetch_data = $database->getReference($ref_table)->getValue();
            $userCount = $fetch_data ? count($fetch_data) : 0;
            ?>
            <div class="card">
                <div class="cardIcon">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="cardContents">
                    <h4>Kullanıcı saıyısı</h4>
                    <p><?= $userCount; ?></p>
                </div>
            </div>

            <!-- Bekleyen Siparişler -->
            <?php
            $ref_table = 'orders';
            $fetch_data = $database->getReference($ref_table)->getValue();
            $pendingOrders = 0;
            foreach ($fetch_data as $row => $orders) {
                if ($orders['status'] === 'notDelivered') {
                    $pendingOrders++;
                }
            }
            ?>
            <div class="card">
                <div class="cardIcon">
                <i class="fa-solid fa-envelope"> </i>
                </div>
                <div class="cardContents">
                    <h4>Bekleyen siparişler</h4>
                    <p><?= $pendingOrders; ?></p>
                </div>
            </div>

            <!-- Okunmamış Mesajlar -->
            <?php
            $ref_table = 'messages';
            $fetch_data = $database->getReference($ref_table)->getValue();
            $unreadMessages = 0;
            foreach ($fetch_data as $row => $orders) {
                if ($orders['status'] === 'unread') {
                    $unreadMessages++;
                }
            }
            ?>
            <div class="card">
                <div class="cardIcon">
                    <i class="fa-solid fa-comment-dots"></i>
                </div>
                <div class="cardContents">
                    <h4>Okunmamış Mesajlar</h4>
                    <p><?= $unreadMessages; ?></p>
                </div>
            </div>

            <!-- Toplam Satılan Ürün -->
            <?php
            $ref_table = 'orders';
            $fetch_data = $database->getReference($ref_table)->getValue();
            $soldProducts = 0;
            foreach ($fetch_data as $row => $orders) {
                if ($orders['status'] === 'delivered') {
                    $soldProducts++;
                }
            }
            ?>
            <div class="card">
                <div class="cardIcon">
                <i class="fa-solid fa-calendar-days"></i>
                </div>
                <div class="cardContents">
                    <h4>Toplam Satilan Urun</h4>
                    <p><?= $soldProducts; ?></p>
                </div>
            </div>

            <?php
            $ref_table = 'products';
            $fetch_data = $database->getReference($ref_table)->getValue();
            $soldProducts = 0;
            foreach ($fetch_data as $row => $orders) {
                    $soldProducts++;
            }
            ?>
            <div class="card">
                <div class="cardIcon">
                <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <div class="cardContents">
                    <h4>Toplam Urunler</h4>
                    <p><?= $soldProducts; ?></p>
                </div>
            </div>
        </div>
        <div class="graphs">
            <div class="secondRow">
                <div class="chart-container1">
                    <h3>Yıllık Ciro</h3>
                    <canvas id="myChart1" width="1000" height="300"></canvas>
                </div>
                <div class="messages-card">
                    <div class="message-header">
                        <b>Yeni Mesajlar</b>
                    </div>
                    <div class="messages">
                        <?php
                        $ref_table = 'messages';
                        $fetch_data = $database->getReference($ref_table)->getValue();
                        $soldProducts = 0;
                        $i=0;
                        foreach ($fetch_data as $row => $orders) {
                            if ($orders['status'] === 'unread' && $i < 4) {
                                $i++;
                                ?>
                                    
                                <div class="message">
                                    <div class="messageIcon">
                                        <i class="bx bx-user icon"></i>
                                    </div>
                                    <a href="messages.php?filter=unread" class="message-content"><div class="messageContent">
                                        <b><?= $orders['sender'] ?></b>
                                        <i><?= $orders['email'] ?></i>
                                    </div></a>
                                    
                                </div>
                                <?php
                            }
                        }
                        if($i == 0){
                            ?>
                            <br>
                            <b style="color: gray;">Okunmamış Mesaj Bulunamadı</b>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="firstGraphs">
                <div class="mostRated">
                        <h3>En Çok Satılan Ürünler</h3>
                        <div class="chart-container3">
                            <div class="chart-containerr3">
                                <canvas id="myChart3" width="800" height="400"></canvas>
                            </div>
                        </div>
                </div>
                
                <div class="graph2">
                    <h3>Müşteri Memnuniyeti</h3>
                    <div class="chart-container2">
                        <canvas id="myChart2" width="800" height="800"></canvas>
                    </div>
                </div>

                
                <?php
                    $ref_table = 'orders';
                    $fetch_data = $database->getReference($ref_table)->getValue();
                    $pendingOrdersAll = 0;
                    $pendingOrdersDelivered = 0;
                    $pendingOrdersNotDelivered = 0;
                    $pendingOrdersReturned = 0;
                    $pendingOrdersCanceled = 0;
                    foreach ($fetch_data as $row => $orders) {
                        $pendingOrdersAll++;
                        if ($orders['status'] === 'notDelivered') {
                            $pendingOrdersNotDelivered++;
                        }else if($orders['status'] === 'delivered') {
                            $pendingOrdersDelivered++;
                        }else if($orders['status'] === 'returned') {
                            $pendingOrdersReturned++;
                        }else if($orders['status'] === 'canceled') {
                            $pendingOrdersCanceled++;
                        }
                    }
                ?>
                <div class="order-card">
                    <div class="order-card-header">
                        <h2>Tüm Siparişler</h2>
                        <p class="total-orders"> <?= $pendingOrdersAll; ?> </p>
                    </div>
                    
                    <ul class="order-category-list">
                        <li>
                            <div class="order-list-text">
                                <span class="order-category">İletilen</span>
                                <span class="orders"><?= $pendingOrdersDelivered; ?></span>
                            </div>
                            <div class="bar"><span class="delivered" style="width:<?= ($pendingOrdersDelivered * 100) / $pendingOrdersAll?>%;"></span></div>
                        </li>
                        <li>
                            <div class="order-list-text">
                                <span class="order-category">İptal edilen</span>
                                <span class="orders"><?= $pendingOrdersCanceled; ?></span>
                            </div>
                            <div class="bar"><span class="canceled" style="width:<?= ($pendingOrdersCanceled * 100) / $pendingOrdersAll?>%;"></span></div>
                        </li>
                        <li>
                            <div class="order-list-text">
                                <span class="order-category">Yolda</span>
                                <span class="orders"><?= $pendingOrdersNotDelivered; ?></span>
                            </div>
                            <div class="bar"><span class="onWay" style="width:<?= ($pendingOrdersNotDelivered * 100) / $pendingOrdersAll?>%;"></span></div>
                        </li>
                        <li>
                            <div class="order-list-text">
                                <span class="order-category">İade edilen</span>
                                <span class="orders"><?= $pendingOrdersReturned; ?></span>
                            </div>
                            <div class="bar"><span class="return" style="width:<?= ($pendingOrdersReturned * 100) / $pendingOrdersAll?>%;"></span></div>
                        </li>
                    </ul>
                </div>


            </div>
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Son Siparişler</h3>
                    </div>
                    <a href="orders.php" class="tableRef">
                    <table>
                        <thead>
                            <tr>
                                <th>Tarih</th>
                                <th>Alıcı İsmi</th>
                                <th>Ürün</th>
                                <th>Durum</th>
                                <th>Fiyat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $ref_table = 'orders';
                                $fetch_data = $database->getReference($ref_table)->getValue();
                                
                                usort($fetch_data, function($a, $b) {
                                    return strtotime($b['orderDate']) - strtotime($a['orderDate']);
                                });

                                $i = 0;
                                foreach ($fetch_data as $row => $orders) {
                                    if ($i < 5) {
                                        $i++;
                                        ?>
                                        <tr>
                                            <td><?= $orders['orderDate'] ?></td>
                                            <td><?= $orders['name'] ?></td>
                                            <td><?= $orders['product_name'] ?></td>
                                            <td><span class="status completed"><?= $orders['status'] ?></span></td>
                                            <td><?= $orders['price'] ?></td>
                                            
                                        </tr>
                                        <?php
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                        
                        </a>
                </div>
            </div>
        </div>

        <footer>
            <p>&copy; 2024 Created by Nazarik</p>
        </footer>
    </div>
    <script>
        // Yıllık ciro grafiği
        const ctx1 = document.getElementById('myChart1').getContext('2d');
        const monthlyData = <?= json_encode($monthlyRevenue) ?>;

        const myChart1 = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'],
                datasets: [{
                    label: 'Toplam',
                    data: monthlyData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: true
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Müşteri memnuniyeti grafiği
        const ctx2 = document.getElementById('myChart2').getContext('2d');
        const satisfactionData = <?= json_encode($satisfactionCounts) ?>;
        const myChart2 = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['Mutsuz', 'Nötr', 'Mutlu'],
                datasets: [{
                    label: 'Sipariş Dağılımı',
                    data: satisfactionData,
                    backgroundColor: ['rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(75, 192, 192, 0.2)'],
                    borderColor: ['rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            }
        });

        // En çok satılan ürünler grafiği
        const productLabels = <?= json_encode(array_keys($mergedProducts)) ?>;
        const productSales = <?= json_encode(array_values($mergedProducts)) ?>;

        const ctx3 = document.getElementById('myChart3').getContext('2d');
        const myChart3 = new Chart(ctx3, {
            type: 'doughnut',
            data: {
                labels: productLabels,
                datasets: [{
                    label: 'Satış Miktarı',
                    data: productSales,
                    backgroundColor: [
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)'
                    ],
                    borderColor: [
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' adet';
                            }
                        }
                    }
                }
            }
        });

    </script>
</body>
</html>
