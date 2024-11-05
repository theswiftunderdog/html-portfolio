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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['status']) && is_array($_POST['status'])) {
        $status = $_POST['status'];

        foreach ($status as $productId => $productStatus) {
            // Update the records in the inventory table
            $sql = "UPDATE inventory SET status = '$productStatus' WHERE product_id = '$productId'";
            $conn->query($sql);
        }
    }
}

// Fetch product names for dropdown filter
$productNamesQuery = "SELECT DISTINCT product_name FROM inventory";
$productNamesResult = $conn->query($productNamesQuery);
$productNames = [];
while ($row = $productNamesResult->fetch_assoc()) {
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['status']) && is_array($_POST['status'])) {
        $status = $_POST['status'];

        foreach ($status as $productId => $productStatus) {
            // Update the records in the inventory table
            $sql = "UPDATE inventory SET status = '$productStatus' WHERE product_id = '$productId'";
            $conn->query($sql);
        }
    }
}

// Get the selected product filter, if any
$productFilter = isset($_POST['product-filter']) ? $_POST['product-filter'] : '';

// Get the selected category filter, if any
$categoryFilter = isset($_POST['category-filter']) ? $_POST['category-filter'] : '';

// Get the selected status filter, if any
$statusFilter = isset($_POST['status-filter']) ? $_POST['status-filter'] : '';

// Pagination
$limit = 20; // Number of records to show per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Get the current page from the URL
$start = ($page - 1) * $limit; // Calculate the starting index for the query

// Modify the SQL query to include the product, category, and status filters, if selected
$sql = "SELECT * FROM inventory";
if (!empty($productFilter) && !empty($categoryFilter) && !empty($statusFilter)) {
    $sql .= " WHERE product_name = '$productFilter' AND category = '$categoryFilter' AND status = '$statusFilter'";
} elseif (!empty($productFilter) && !empty($categoryFilter)) {
    $sql .= " WHERE product_name = '$productFilter' AND category = '$categoryFilter'";
} elseif (!empty($productFilter) && !empty($statusFilter)) {
    $sql .= " WHERE product_name = '$productFilter' AND status = '$statusFilter'";
} elseif (!empty($categoryFilter) && !empty($statusFilter)) {
    $sql .= " WHERE category = '$categoryFilter' AND status = '$statusFilter'";
} elseif (!empty($productFilter)) {
    $sql .= " WHERE product_name = '$productFilter'";
} elseif (!empty($categoryFilter)) {
    $sql .= " WHERE category = '$categoryFilter'";
} elseif (!empty($statusFilter)) {
    $sql .= " WHERE status = '$statusFilter'";
}
$sql .= " LIMIT $start, $limit";

$result = $conn->query($sql);
// Count total number of records in the table
$total = $conn->query("SELECT COUNT(*) AS count FROM inventory")->fetch_assoc()['count'];
$pages = ceil($total / $limit); // Calculate the total number of pages
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../admin/files/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&family=Roboto+Condensed&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Titillium+Web&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"/>
    <style>
        /* Add this style to your existing styles or in a separate style tag */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.9);
        }

        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #ffffff;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <title>Update Inventory</title>
    <link rel="icon" href="../image/Icon.png" type="image/x-icon">
</head>
<body>
    <?php include('../admin/adminsidebar.php') ?>

    <div class="main-content">
        <div class="updateI">
            <h1>Update Inventory</h1>
        </div>
        <div class="updateI-table" data-aos="fade-up">
            <form method="POST">
                <div class="filter">
                    <label for="product-filter">Product Filter:</label>
                    <select name="product-filter" id="product-filter">
                        <option value="">All Products</option>
                        <?php foreach ($productNames as $productName) : ?>
                            <option value="<?php echo $productName; ?>" <?php echo ($productName === $productFilter) ? 'selected' : ''; ?>><?php echo $productName; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="category-filter">Category Filter:</label>
                    <select name="category-filter" id="category-filter">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo $category; ?>" <?php echo ($category === $categoryFilter) ? 'selected' : ''; ?>><?php echo $category; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="status-filter">Status Filter:</label>
                    <select name="status-filter" id="status-filter">
                        <option value="">All Statuses</option>
                        <?php foreach ($statuses as $status) : ?>
                            <option value="<?php echo $status; ?>" <?php echo ($status === $statusFilter) ? 'selected' : ''; ?>><?php echo $status; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" name="apply" class="apply-filter">Apply Filter</button>
                </div>
                <table>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Image</th>
                        <th>Datetime</th>
                    </tr>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["product_id"] . "</td>";
                            echo "<td>" . $row["product_name"] . "</td>";
                            echo "<td>₱" . number_format($row["price"], 2) . "</td>";
                            echo "<td>" . $row["quantity"] . "</td>";
                            echo "<td>" . $row["description"] . "</td>";
                            echo "<td>" . $row["category"] . "</td>";
                            echo "<td>" . $row["supplier"] . "</td>";
                            echo "<td>";
                            echo "<select name='status[{$row['product_id']}]'>";
                            echo "<option value='In Use'" . ($row['status'] === 'In Use' ? ' selected' : '') . ">In Use</option>";
                            echo "<option value='Stock'" . ($row['status'] === 'Stock' ? ' selected' : '') . ">Stock</option>";
                            echo "<option value='Broken'" . ($row['status'] === 'Broken' ? ' selected' : '') . ">Broken</option>";
                            echo "<option value='Empty'" . ($row['status'] === 'Empty' ? ' selected' : '') . ">Empty</option>";
                            echo "</select>";
                            echo "</td>";
                            echo "<td><button type='button' class='preview-image' data-image-src='data:image/jpeg;base64," . base64_encode($row['image']) . "'>Preview</button></td>"; 
                            echo "<td>" . date("F j, Y", strtotime($row["datetime"])) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='10'>No inventory records found</td></tr>";
                    }
                    ?>
                </table>
                <button type="submit" name="submit" class="btn btn-primary">Update</button>
            </form>
        </div>

        <!-- Pagination links -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $pages; $i++) : ?>
                <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    </div>

    <!-- Bootstrap modal for image preview -->
    <div id="imagePreviewModal" class="modal">
        <span class="close">&times;</span>
        <img id="previewImage" class="modal-content">
    </div>

    <script src="../admin/files/adminscript.js"></script>
    <script src="../admin/files/rotate.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        $(document).ready(function () {
            $('.preview-image').click(function () {
                var imageUrl = $(this).data('image-src');
                if (imageUrl) {
                    // Set the source of the preview image in the modal
                    $('#previewImage').attr('src', imageUrl);
                    // Display the modal
                    $('#imagePreviewModal').css('display', 'block');
                } else {
                    alert('Image source not found.');
                }
            });

            // Get the close button in the modal
            var closeButton = $('.close');

            // Attach a click event to the close button to hide the modal
            closeButton.click(function () {
                $('#imagePreviewModal').css('display', 'none');
            });

            // Attach a click event to the modal itself to hide it if clicked outside the image
            $('#imagePreviewModal').click(function (event) {
                if (event.target === this) {
                    $(this).css('display', 'none');
                }
            });
        });
    </script>
    <script>
        AOS.init();
    </script>
</body>
</html>
