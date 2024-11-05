<?php
    // Start the session.
    session_start();
    if (!isset($_SESSION['user'])) {
        header('location: login.php');
        exit;
    }

    $user = $_SESSION['user'];

    // Include the file that handles database connection and queries.
    include('../Connection/Connection.php');

    // Retrieve the user's account information from the database based on the user's email address.
    $sql = "SELECT email, password, contact_number, complete_address FROM customer WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':email', $user['email']);
    $stmt->execute();
    $accountInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    // If no account information is found, handle it accordingly.
    if (!$accountInfo) {
        header('location: error.php');
        exit;
    }

    // Check if the form was submitted.
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve the updated values from the form.
        $updatedEmail = $_POST['email'];
        $updatedPassword = $_POST['password'];
        $updatedContactNumber = $_POST['contact_number'];
        $updatedDeliveryAddress = $_POST['delivery_address'];

        // Update the user's account information in the database.
        $updateSql = "UPDATE customer SET email = :email, password = :password, contact_number = :contact_number, complete_address = :complete_address WHERE email = :user_email";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bindValue(':email', $updatedEmail);
        $updateStmt->bindValue(':password', $updatedPassword);
        $updateStmt->bindValue(':contact_number', $updatedContactNumber);
        $updateStmt->bindValue(':complete_address', $updatedDeliveryAddress);
        $updateStmt->bindValue(':user_email', $user['email']);
        $updateStmt->execute();

        // Optionally, you can redirect the user to a success page or display a success message.
        header('location: profile.php');
        exit;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/navstyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&family=Roboto+Condensed&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Titillium+Web&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"/>
    <title>Profile</title>
    <link rel="icon" href="../image/Icon.png" type="image/x-icon">
</head>
<body>
    <?php include('../customer/customerSidebar.php') ?>

    
    <div class="account-header">
        <div class="user-image">
            <img src="../image/icon-placeholder.png" alt="User Image" width="150px" height="150px" data-aos="fade-right" data-aos-duration="500">
            <h2 data-aos="fade-left" data-aos-duration="500"><?= $user['full_name'] ?></h2>
        </div>
    </div>

    <div class="account-body">
        <form method="POST" action="">
            <div class="account-forms" data-aos="fade-up" data-aos-delay="500">
                <label for="email">E-Mail Address</label>
                <input id="email" name="email" type="text" value="<?= htmlspecialchars($accountInfo['email']) ?>">
            </div>
            <div class="account-forms" data-aos="fade-up" data-aos-delay="600">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" value="<?= htmlspecialchars($accountInfo['password']) ?>">
            </div>
            <div class="account-forms" data-aos="fade-up" data-aos-delay="700">
                <label for="contact_number">Contact Number</label>
                <input id="contact_number" name="contact_number" type="text" value="<?= htmlspecialchars($accountInfo['contact_number']) ?>">
            </div>
            <div class="account-forms" data-aos="fade-up" data-aos-delay="800">
                <label for="delivery_address">Delivery Address</label>
                <input id="delivery_address" name="delivery_address" type="text" value="<?= htmlspecialchars($accountInfo['complete_address']) ?>">
            </div>

            <div class="account-button" data-aos="fade-up" data-aos-delay="900">
                <button type="submit" class="button-design">Cancel</button>
                <button type="submit" class="button-design" name="apply_changes">Apply Changes</button>
            </div>
        </form>
    </div>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>
