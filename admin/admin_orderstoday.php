<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: login.php');
    exit;
}

$user = $_SESSION['user'];

include('../Connection/Connection.php');

if (isset($_GET['complete']) && isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];


    $updateOrderSql = "UPDATE orders SET status = 'Complete' WHERE order_id = :order_id";
    $updateOrderStmt = $conn->prepare($updateOrderSql);
    $updateOrderStmt->bindValue(':order_id', $orderId);
    $updateOrderStmt->execute();    

   
    $insertSalesSql = "INSERT INTO sales (sales_id,sales_name, full_name, quantity, price, sales_date, status ) 
                       SELECT order_id,order_name, full_name, quantity, price,date_created, 'Unpaid' FROM orders WHERE order_id = :order_id";
    $insertSalesStmt = $conn->prepare($insertSalesSql);
    $insertSalesStmt->bindValue(':order_id', $orderId);
    $insertSalesStmt->execute();
}

$sql = "SELECT order_id, order_name, full_name, date_created, quantity, price, status 
        FROM orders 
        WHERE status = 'Approved' OR status = 'Out for Delivery'";

$stmt = $conn->prepare($sql);

$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../admin/files/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&family=Roboto+Condensed&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Titillium+Web&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"/>
    <title>Complete Orders</title>
    <link rel="icon" href="../image/Icon.png" type="image/x-icon">
</head>
<body>
    <?php include ('../admin/adminsidebar.php') ?>

    <div class="main-content">   
        <div class="admO-header"><h1>Complete Orders</h1></div>
        <?php if (empty($orders)): ?>
            <div class="order-table" data-aos="fade-up">
                <table class="tables">
                    <thead>
                        <tr>
                            <th scope="col">Order ID</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Order Name</th>
                            <th scope="col">Order Date</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Price</th>
                            <th scope="col">Status</th>
                            <th scope="col">Complete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8" class="no-orders">No Orders for Today</td>
                        </tr>
                    </tbody>
                </table>    
            </div>
        <?php else: ?>
            <div class="order-table" data-aos="fade-up">
                <table class="tables">
                    <thead>
                        <tr>
                            <th scope="col">Order ID</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Order Name</th>
                            <th scope="col">Order Date</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Price</th>
                            <th scope="col">Status</th>
                            <th scope="col">Complete</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= $order['order_id']; ?></td>
                                <td><?= $order['full_name']; ?></td>
                                <td><?= $order['order_name']; ?></td>
                                <td><?= date('F j, Y', strtotime($order['date_created'])); ?></td>
                                <td><?= $order['quantity']; ?></td>
                                <td><?= '₱' . number_format($order['price'], 2); ?></td> 
                                <td><?= $order['status']; ?></td>
                                <td>
                                    <a href="?complete=true&order_id=<?= $order['order_id']; ?>" class="btn btn-success">Complete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>    
            </div>
        <?php endif; ?>
    </div>

    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('.address-tooltip'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
    <script src="../admin/files/adminscript.js"></script>
    <script src="../admin/files/rotate.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>
