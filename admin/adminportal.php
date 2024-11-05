<?php
session_set_cookie_params(0); 
session_start();
include('../Connection/Session_check.php');
if (!isset($_SESSION['user'])) {
    header('location: login.php');
    exit;
}

$user = $_SESSION['user'];

include('../Connection/Connection.php');

// Total Inventory
$sql = "SELECT COUNT(*) AS total_inventory FROM inventory";
$stmt = $conn->prepare($sql);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$totalInventory = $row['total_inventory'];

// Total Customers
$sql = "SELECT COUNT(*) AS total_customers FROM customer";
$stmt = $conn->prepare($sql);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$totalCustomers = $row['total_customers'];

// Today's Orders
$today = date('Y-m-d');
$statusApproved = 'Pending';
$sql = "SELECT COUNT(*) AS today_orders FROM orders WHERE DATE(date_created) = :today AND status != :statusApproved";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':today', $today);
$stmt->bindValue(':statusApproved', $statusApproved);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$todayOrders = $row['today_orders'];

// Pending Orders
$statusPending = 'pending';
$sql = "SELECT COUNT(*) AS pending_orders FROM orders WHERE status = :statusPending";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':statusPending', $statusPending);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$pendingOrders = $row['pending_orders'];

// Total Sales Today
$sql = "SELECT SUM(price) AS Total_Price FROM sales WHERE DATE(sales_date) = CURDATE()";
$stmt = $conn->prepare($sql);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$totalSalesToday = $row['Total_Price'] ? $row['Total_Price'] : 0.00;

?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../admin/files/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"/>
    <title>Admin Portal</title>
    <link rel="icon" href="../image/Icon.png" type="image/x-icon">
</head>
<body>
    <?php include('../admin/adminsidebar.php') ?>

    <div class="main-content">
        <h1 class="dcp-header">Dashboard Control Panel</h1>
        <div class="welcome-message">
            <h3>Welcome back, <?php echo htmlspecialchars($user['full_name']); ?>!</h3>
        </div>
        <div class="row" data-aos="fade-up">
            <div class="bg-primary text-white p-4 rounded text-center" style="width: 180px; margin-right: 10px;">
                <h5 class="mb-0"><a href="show_Inventory.php" class="text-white">Total Inventory</a></h5>
                <p class="h1 mb-0"><a href="show_Inventory.php" class="text-white"><?php echo htmlspecialchars($totalInventory); ?></a></p>
            </div>
            <div class="bg-secondary text-white p-4 rounded text-center" style="width: 195px; margin-right: 10px;">
                <h5 class="mb-0"><a href="admin_customers.php" class="text-white">Total Customers</a></h5>
                <p class="h1 mb-0"><a href="admin_customers.php" class="text-white"><?php echo htmlspecialchars($totalCustomers); ?></a></p>
            </div>
            <div class="bg-success text-white p-4 rounded text-center" style="width: 195px; margin-right: 10px;">
                <h5 class="mb-0"><a class="text-white">Today's Orders</a></h5>
                <p class="h1 mb-0"><a class="text-white"><?php echo htmlspecialchars($todayOrders); ?></a></p>
            </div>
            <div class="bg-info text-white p-4 rounded text-center" style="width: 195px; margin-right: 10px;">
                <h5 class="mb-0"><a href="admin_acceptorder.php" class="text-white">Pending Orders</a></h5>
                <p class="h1 mb-0"><a href="admin_acceptorder.php" class="text-white"><?php echo htmlspecialchars($pendingOrders); ?></a></p>
            </div>
            <div class="bg-warning text-white p-4 rounded text-center" style="width: 195px;">
                <h5 class="mb-0"><a class="text-white">Sales Today</a></h5>
                <p class="h1 mb-0"><a class="text-white">₱<?php echo number_format($totalSalesToday, 2); ?></a></p>
            </div>
        </div>
    </div>

    <script src="../admin/files/adminscript.js"></script>
    <script src="../admin/files/rotate.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>
