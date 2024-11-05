<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: login.php');
    exit;
}

$user = $_SESSION['user'];

include('../Connection/Connection.php');


if (isset($error_message)) {
    die("Connection failed: " . $error_message);
}


$totalSalesQuery = "SELECT SUM(price) AS total_sales FROM sales WHERE status = 'paid'";
$totalSalesResult = $conn->query($totalSalesQuery);
$totalSalesRow = $totalSalesResult->fetch(PDO::FETCH_ASSOC);
$totalSales = $totalSalesRow['total_sales'];


$totalExpensesQuery = "SELECT SUM(price) AS total_expenses FROM inventory";
$totalExpensesResult = $conn->query($totalExpensesQuery);
$totalExpensesRow = $totalExpensesResult->fetch(PDO::FETCH_ASSOC);
$totalExpenses = $totalExpensesRow['total_expenses'];
$totalIncome = $totalSales - $totalExpenses;
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
    <title>Total Sales</title>
    <link rel="icon" href="../image/Icon.png" type="image/x-icon">
</head>
<body>
    <?php include ('../admin/adminsidebar.php') ?>
    
    <div class="main-content">
        <div class="admO-header">
            <h1>Total sales</h1>
        </div>     
        <canvas id="salesChart" style="width: 413px;height: 380px;margin-left:431px !important;display: block;box-sizing: border-box;margin-top: 4px;"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
       
        var salesData = {
            labels: ['Sales', 'Expenses'],
            datasets: [{
                label: 'Total',
                data: [<?php echo $totalSales; ?>, <?php echo $totalExpenses; ?>],
                backgroundColor: [
                    'blue',
                    'red'
                ]
            }]
        };

        var options = {
            responsive: false, 
        
        };


        var salesChartCanvas = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(salesChartCanvas, {
            type: 'pie',
            data: salesData,
            options: options
        });
    </script>

     
<table class="table">
            <thead>
                <tr>
                    <th>Total Sales</th>
                    <th>Total Expenses</th>
                    <th>Total Income</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td>₱<?php echo $totalSales; ?></td>
                <td>₱<?php echo $totalExpenses; ?></td>
                <td>₱<?php echo $totalIncome; ?></td>
                </tr>
            </tbody>
        </table>

    <script src="../admin/files/adminscript.js"></script>
    <script src="../admin/files/rotate.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>AOS.init();</script>
</body>
</html>
