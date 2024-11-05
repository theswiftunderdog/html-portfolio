<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: login.php');
    exit;
}

$user = $_SESSION['user'];

include('../Connection/Connection.php');

$recordsPerPage = 20;

$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($currentPage - 1) * $recordsPerPage;

$customerQuery = "SELECT full_name FROM customer";
$customerStmt = $conn->prepare($customerQuery);
$customerStmt->execute();
$customers = $customerStmt->fetchAll(PDO::FETCH_ASSOC);

$sortOrder = isset($_GET['sort_order']) ? $_GET['sort_order'] : '';
$selectedCustomer = isset($_GET['filter_name']) ? $_GET['filter_name'] : '';

$sql = "SELECT o.order_id, o.order_name, o.full_name, o.date_created, o.quantity, o.price, o.status, o.complete_address, o.remarks
        FROM orders o
        LEFT JOIN customer c ON o.full_name = c.full_name";

if (!empty($selectedCustomer)) {
    $sql .= " WHERE o.full_name = :selectedCustomer";
}

if (!empty($sortOrder)) {
    if ($sortOrder === 'asc') {
        $sql .= " ORDER BY o.date_created ASC";
    } elseif ($sortOrder === 'desc') {
        $sql .= " ORDER BY o.date_created DESC";
    }
}

$sql .= " LIMIT $offset, $recordsPerPage";

$stmt = $conn->prepare($sql);

if (!empty($selectedCustomer)) {
    $stmt->bindValue(':selectedCustomer', $selectedCustomer, PDO::PARAM_STR);
}

$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalRecords = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);
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
    <title>Order History</title>
    <link rel="icon" href="../image/Icon.png" type="image/x-icon">
</head>
<body>
    <?php include ('../admin/adminsidebar.php') ?>

    <div class="main-content">
        <div class="admO-header">
            <h1>Customer's Order History</h1>
            <div class="filter-form">
                <form method="GET">
                    <select name="filter_name" onchange="this.form.submit()">
                        <option value="">All Customers</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?= $customer['full_name']; ?>" <?= ($selectedCustomer === $customer['full_name']) ? 'selected' : ''; ?>>
                                <?= $customer['full_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
            <div class="sort-buttons">
            <a class="btn btn-primary" href="admin_orderHistory.php?sort_order=desc&filter_name=<?= $selectedCustomer ?>">Descending ↓</a>    
            <a class="btn btn-primary" href="admin_orderHistory.php?sort_order=asc&filter_name=<?= $selectedCustomer ?>">Ascending ↑</a>
            </div>
        </div>
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
                        <th scope="col">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $order['order_id']; ?></td>
                            <td class="address-tooltip" data-address="<?= $order['complete_address']; ?>"><?= $order['full_name']; ?></td>
                            <td><?= $order['order_name']; ?></td>
                            <td><?= date('F j, Y', strtotime($order['date_created'])); ?></td>
                            <td><?= $order['quantity']; ?></td>
                            <td><?= '₱' . number_format($order['price'], 2); ?></td>
                            <td><?= $order['status']; ?></td>
                            <td><?= $order['remarks']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a class="page-link <?= ($i === $currentPage) ? 'active' : ''; ?>" href="admin_orderHistory.php?page=<?= $i ?>&sort_order=<?= $sortOrder ?>&filter_name=<?= $selectedCustomer ?>"><?= $i ?></a>
                <?php endfor; ?>
                </div>
    </div>

    <script src="../admin/files/adminscript.js"></script>
    <script src="../admin/files/rotate.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>AOS.init();</script>
</body>
</html>
