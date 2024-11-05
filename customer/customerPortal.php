<?php
    // Start the session.
    session_start();
    if (!isset($_SESSION['user'])) {
        header('location: login.php');
        exit;
    }

    $user = $_SESSION['user'];

    include('../Connection/Connection.php');

    // Fetch the latest (ongoing) order details
    $queryLatestOrder = "SELECT a.status, a.order_name, a.quantity, a.date_created
                         FROM orders a 
                         JOIN customer b ON a.customer_id = b.customer_id
                         WHERE b.customer_id = :customerId AND a.status != 'Complete' and a.status !='Declined'
                         ORDER BY a.date_created DESC
                         LIMIT 1";
    $stmtLatest = $conn->prepare($queryLatestOrder);
    $stmtLatest->bindParam(':customerId', $user['customer_id']);
    $stmtLatest->execute();
    $latestOrder = $stmtLatest->fetch(PDO::FETCH_ASSOC);

    if ($latestOrder) {
        $orderStatus = $latestOrder['status'];
        $orderName = $latestOrder['order_name'];
        $quantity = $latestOrder['quantity'];
        $dateCreated = DateTime::createFromFormat('Y-m-d H:i:s', $latestOrder['date_created'])->format('F d, Y H');
    } else {
        $orderStatus = 'No ongoing order';
        $orderName = '';
        $quantity = '';
        $dateCreated = '';
    }

    // Fetch the previous order details
    $queryPreviousOrder = "SELECT a.order_name, a.quantity, a.date_created
                           FROM orders a 
                           JOIN customer b ON a.customer_id = b.customer_id
                           WHERE b.customer_id = :customerId
                           ORDER BY a.date_created DESC
                           LIMIT 1";
    $stmtPrevious = $conn->prepare($queryPreviousOrder);
    $stmtPrevious->bindParam(':customerId', $user['customer_id']);
    $stmtPrevious->execute();
    $previousOrder = $stmtPrevious->fetch(PDO::FETCH_ASSOC);

    if ($previousOrder) {
        $previousOrderName = $previousOrder['order_name'];
        $previousQuantity = $previousOrder['quantity'];
        $previousDateCreated = DateTime::createFromFormat('Y-m-d H:i:s', $previousOrder['date_created'])->format('F d, Y');
    } else {
        $previousOrderName = '';
        $previousQuantity = '';
        $previousDateCreated = 'No previous order';
    }
?>


<!DOCTYPE html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/navstyle.css">
    <title>Customer Portal</title>
    <link rel="icon" href="../image/Icon.png" type="image/x-icon">
</head>
<body>
    <?php include('../customer/customerSidebar.php') ?>

    <div class="welcome-message">
        <h3>Welcome back, <?php echo $user['full_name']; ?>!</h3>
    </div>

    <div class="row" data-aos="fade-up">
        <div class="bg-primary text-white p-4 rounded text-center" style="width: 290px; height: 180px; margin-left: 176px;">
            <h5 class="mb-0"><a class="text-white">Ongoing Order Status</a></h5>
            <p class="mb-0 text-white">Order Name: <?php echo $orderName ? $orderName : 'N/A'; ?></p>
            <p class="mb-0 text-white">Quantity: <?php echo $quantity ? $quantity : 'N/A'; ?></p>
            <p class="mb-0 text-white">Status: <?php echo $orderStatus; ?></p>

        </div>
        <div class="bg-secondary text-white p-4 rounded text-center" style="width: 290px; height: 180px; margin-left: 20px;">
            <h5 class="mb-0"><a class="text-white">Previous Order</a></h5>
            <p class="mb-0 text-white">Order Name: <?php echo $previousOrderName ? $previousOrderName : 'N/A'; ?></p>
            <p class="mb-0 text-white">Quantity: <?php echo $previousQuantity ? $previousQuantity : 'N/A'; ?></p>
            <p class="mb-0 text-white">Date Ordered: <?php echo $previousDateCreated ? $previousDateCreated : 'N/A'; ?></p>
        </div>
    </div>

    <script src="../customer/files/customerscript.js"></script>
</body>
</html>
