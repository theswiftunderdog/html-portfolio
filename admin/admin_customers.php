<?php
    session_start();
    if (!isset($_SESSION['user'])) {
        header('location: login.php');
        exit;
    }

    $user = $_SESSION['user'];

    $servername = "localhost";
    $username = 'root';
    $password = '';
    $dbname = "tentian";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $recordsPerPage = 5;

    $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

    $offset = ($currentPage - 1) * $recordsPerPage;

    $sql = "SELECT customer_id, full_Name, contact_number,complete_address FROM customer LIMIT $offset, $recordsPerPage";
    $result = $conn->query($sql); 
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
    <title>Customer Details</title>
    <link rel="icon" href="../image/Icon.png" type="image/x-icon">
</head>

<body>
    <?php include ('../admin/adminsidebar.php')?>

    <div class="main-content">
    <div class="admC-header"><h1>Customer's Account Details</h1></div>
    <div class="admC-table" data-aos="fade-up">    
        <table>
            <tr>
                <th>Customer ID</th>
                <th>Full Name</th>
                <th>Contact Information</th>
                <th>Complete Address</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["customer_id"] . "</td>";
                    echo "<td>" . $row["full_Name"] . "</td>";
                    echo "<td>" . $row["contact_number"] . "</td>";      
                    echo "<td>" . $row["complete_address"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No customer records found</td></tr>";
            }
            ?>
        </table>
    </div>
        <?php
        $totalRecords = $conn->query("SELECT COUNT(*) AS total FROM customer")->fetch_assoc()['total'];

        $totalPages = ceil($totalRecords / $recordsPerPage);
        ?>

        <div class="pagination">
            <?php
            for ($i = 1; $i <= $totalPages; $i++) {
                $activeClass = ($i === $currentPage) ? "active" : "";
                echo "<a class='$activeClass' href='show_customer.php?page=$i'>$i</a>";
            }
            ?>
        </div>
    </div>
</body>
<script src="../admin/files/adminscript.js"></script>
  <script src="../admin/files/rotate.js"></script>
  <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>
</html>
