<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: login.php');
    exit; 
}

$user = $_SESSION['user'];

include('../Connection/Connection.php');

function generateNextCustomerId($conn) {
    $maxCustomerIdQuery = $conn->query("SELECT MAX(customer_id) AS max_customer_id FROM Customer");
    $maxCustomerIdResult = $maxCustomerIdQuery->fetch(PDO::FETCH_ASSOC);
    $maxCustomerId = $maxCustomerIdResult['max_customer_id'];

    $currentNumber = intval(substr($maxCustomerId, 8)); // Assuming customer_id is in format 'Customer1', 'Customer2', etc.

    $nextNumber = $currentNumber + 1;
    return 'Customer' . $nextNumber;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerId = generateNextCustomerId($conn);
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hashing the password
    $contactNumber = $_POST['contactNumber'];
    $completeAddress = $_POST['completeAddress'];

    $stmt = $conn->prepare("INSERT INTO Customer (customer_id, full_name, email, password, contact_number, complete_address) 
        VALUES (:customer_id, :full_name, :email, :password, :contact_number, :complete_address)");

    $stmt->bindParam(':customer_id', $customerId);
    $stmt->bindParam(':full_name', $fullName);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':contact_number', $contactNumber);
    $stmt->bindParam(':complete_address', $completeAddress);
    $stmt->execute();
}
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
    <title>Add Customer</title>
    <link rel="icon" href="../TentianSys/image/Icon.png" type="image/x-icon">    
</head>
<body>
    <?php include ('../admin/adminsidebar.php')?>

    <div class="main-content">
        <div class="form-group" data-aos="zoom-in" data-aos-duration="1000">
            <div class="header-font">
                <h1>Add Customer</h1>
            </div>
            <form id="customerForm" method="POST" action="#">
                <div class="form-row">
                    <label for="customerId">Customer ID:</label>
                    <div class="input-container">
                        <input type="text" id="customerId" name="customerId" value="<?php echo generateNextCustomerId($conn); ?>" disabled>
                    </div>
                </div>
                <div class="form-row">
                    <label for="fullName">Full Name:</label>
                    <div class="input-container">
                        <input type="text" id="fullName" name="fullName" required>
                    </div>
                </div>
                <div class="form-row">
                    <label for="email">Email:</label>
                    <div class="input-container">
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>
                <div class="form-row">
                    <label for="password">Password:</label>
                    <div class="input-container">
                        <input type="password" id="password" name="password" required>
                    </div>
                </div>
                <div class="form-row">
                    <label for="contactNumber">Contact Number:</label>
                    <div class="input-container">
                        <input type="text" id="contactNumber" name="contactNumber" required>
                    </div>
                </div>
                <div class="form-row">
                    <label for="completeAddress">Complete Address:</label>
                    <div class="input-container">
                        <textarea id="completeAddress" name="completeAddress" required></textarea>
                    </div>
                </div>
                <div>
                    <button type="submit" name="add" id="addCustomerButton">Add Customer</button>
                </div>
            </form>
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
