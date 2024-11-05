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

$recordsPerPage = 10;

$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

$offset = ($currentPage - 1) * $recordsPerPage;

// Fetch distinct product names for dropdown filter
$productNameQuery = "SELECT DISTINCT product_name FROM inventory";
$productNameResult = $conn->query($productNameQuery);
$productNames = [];
while ($row = $productNameResult->fetch_assoc()) {
    $productNames[] = $row['product_name'];
}

// Fetch distinct categories for dropdown filter
$categoryQuery = "SELECT DISTINCT category FROM inventory";
$categoryResult = $conn->query($categoryQuery);
$categories = [];
while ($row = $categoryResult->fetch_assoc()) {
    $categories[] = $row['category'];
}

// Fetch distinct statuses for dropdown filter
$statusQuery = "SELECT DISTINCT status FROM inventory";
$statusResult = $conn->query($statusQuery);
$statuses = [];
while ($row = $statusResult->fetch_assoc()) {
    $statuses[] = $row['status'];
}

// Get the selected product name filter, if any
$productNameFilter = isset($_POST['product-name-filter']) ? $_POST['product-name-filter'] : '';

// Get the selected category filter, if any
$categoryFilter = isset($_POST['category-filter']) ? $_POST['category-filter'] : '';

// Get the selected status filter, if any
$statusFilter = isset($_POST['status-filter']) ? $_POST['status-filter'] : '';

// Modify the SQL query to include the filters, if selected
$sql = "SELECT product_id, product_name, price, description, category, supplier, status, datetime FROM inventory";
if (!empty($productNameFilter) || !empty($categoryFilter) || !empty($statusFilter)) {
    $sql .= " WHERE";
    $conditions = [];
    if (!empty($productNameFilter)) {
        $conditions[] = "product_name = '$productNameFilter'";
    }
    if (!empty($categoryFilter)) {
        $conditions[] = "category = '$categoryFilter'";
    }
    if (!empty($statusFilter)) {
        $conditions[] = "status = '$statusFilter'";
    }
    $sql .= " " . implode(" AND ", $conditions);
}
$sql .= " LIMIT $offset, $recordsPerPage";

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
    <title>Show Inventory</title>
    <link rel="icon" href="../image/Icon.png" type="image/x-icon">
</head>

<body>
    <?php include ('../admin/adminsidebar.php')?>

    <div class="main-content">
        <div class="showI"><h1>Show Inventory</h1></div>
        <div class="showI-table" data-aos="fade-up">
            <form method="POST">
                <div class="filter">
                    <label for="product-name-filter">Product Name:</label>
                    <select name="product-name-filter" id="product-name-filter">
                        <option value="">All Products</option>
                        <?php foreach ($productNames as $productName) : ?>
                            <option value="<?php echo $productName; ?>" <?php echo ($productName === $productNameFilter) ? 'selected' : ''; ?>><?php echo $productName; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="category-filter">Category:</label>
                    <select name="category-filter" id="category-filter">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo $category; ?>" <?php echo ($category === $categoryFilter) ? 'selected' : ''; ?>><?php echo $category; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="status-filter">Status:</label>
                    <select name="status-filter" id="status-filter">
                        <option value="">All Statuses</option>
                        <?php foreach ($statuses as $status) : ?>
                            <option value="<?php echo $status; ?>" <?php echo ($status === $statusFilter) ? 'selected' : ''; ?>><?php echo $status; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" name="apply" class="apply-filter">Apply Filter</button>
                </div>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["product_id"] . "</td>";
                            echo "<td>" . $row["product_name"] . "</td>";
                            echo "<td>₱" . number_format($row["price"], 2) . "</td>";
                            echo "<td>" . $row["description"] . "</td>";
                            echo "<td>" . $row["category"] . "</td>";
                            echo "<td>" . $row["supplier"] . "</td>";
                            echo "<td>" . $row["status"] . "</td>";
                            echo "<td>" . date("F j, Y", strtotime($row["datetime"])) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No inventory records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php
        // Retrieve the total number of records
        $totalRecords = $conn->query("SELECT COUNT(*) AS total FROM inventory")->fetch_assoc()['total'];

        // Calculate the total number of pages
        $totalPages = ceil($totalRecords / $recordsPerPage);
        ?>

        <div class="pagination">
            <?php
            for ($i = 1; $i <= $totalPages; $i++) {
                $activeClass = ($i === $currentPage) ? "active" : "";
                echo "<a class='page-link $activeClass' href='show_inventory.php?page=$i'>$i</a>";
            }
            ?>
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
