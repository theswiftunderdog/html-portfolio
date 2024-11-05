<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: login.php');
    exit;
}

$user = $_SESSION['user'];

include('../Connection/Connection.php');

if (isset($_GET['Paid']) && isset($_GET['sales_id'])) {
    $salesId = $_GET['sales_id'];

    // Update the sales status to "Paid"
    $updateSalesSql = "UPDATE sales SET status = 'Paid' WHERE sales_id = :sales_id";
    $updateSalesStmt = $conn->prepare($updateSalesSql);
    $updateSalesStmt->bindValue(':sales_id', $salesId);
    $updateSalesStmt->execute();  
}

$sql = "SELECT sales_id, sales_name, full_name, sales_date, quantity, price, status
        FROM sales 
        WHERE status = 'Unpaid'";

$stmt = $conn->prepare($sql);
$stmt->execute();
$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Add Sales</title>
    <link rel="icon" href="../image/Icon.png" type="image/x-icon">
</head>
<body>
    <?php include ('../admin/adminsidebar.php') ?>

    <div class="main-content">   
        <div class="admO-header"><h1>Add Sales</h1></div>
        <?php if (empty($sales)): ?>
            <div class="order-table" data-aos="fade-up">
                <table class="tables">
                    <thead>
                        <tr>
                            <th scope="col">Sales ID</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Sales Name</th>
                            <th scope="col">Sales Date</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Price</th>
                            <th scope="col">Status</th>
                            <th scope="col">Paid?</th>
              
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8" class="no-orders">No Unpaid Sales</td>
                        </tr>
                    </tbody>
                </table>    
            </div>
        <?php else: ?>
            <div class="order-table" data-aos="fade-up">
                <table class="tables">
                    <thead>
                        <tr>
                           <th scope="col">Sales ID</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Sales Name</th>
                            <th scope="col">Sales Date</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Price</th>
                            <th scope="col">Status</th>
                            <th scope="col">Paid?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sales as $sales): ?>
                            <tr>
                                <td><?= $sales['sales_id']; ?></td>
                                <td><?= $sales['full_name']; ?></td>
                                <td><?= $sales['sales_name']; ?></td>
                                <td><?= date('F j, Y', strtotime($sales['sales_date'])); ?></td>
                                <td><?= $sales['quantity']; ?></td>
                                <td><?= '₱' . number_format($sales['price'], 2); ?></td> 
                                <td><?= $sales['status']; ?></td>
                                <td>
                                    <a href="?Paid=true&sales_id=<?= $sales['sales_id']; ?>" class="btn btn-success">Paid</a>
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
