<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: login.php');
    exit();
}
$user = $_SESSION['user'];

include('../Connection/Connection.php');

if (isset($_POST['create'])) {
    $orderType = $_POST['order_type'];
    $quantity = $_POST['quantity'];
    $full_name = $user['full_name'];
    $complete_address = $user['complete_address'];
    $dateCreated = date('Y-m-d H:i:s');
    $customer_id = $user['customer_id']; 

    $prices = [
        '500ml Water Bottle' => 10.00,
        'New Slim Gallon' => 150.00,
        'New Round Gallon' => 150.00,
        'Slim Gallon Refill' => 25.00,
        'Round Gallon Refill' => 25.00
    ];
    $price = $quantity * $prices[$orderType];

    try {
        // Start transaction
        $conn->beginTransaction();

        // Insert the order into the orders table
        $query = 'INSERT INTO orders (order_name, quantity, price, full_name, complete_address, date_created, customer_id) 
                  VALUES (:orderName, :quantity, :price, :fullName, :completeAddress, :dateCreated, :customerId)';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':orderName', $orderType);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':fullName', $full_name);
        $stmt->bindParam(':completeAddress', $complete_address);
        $stmt->bindParam(':dateCreated', $dateCreated);
        $stmt->bindParam(':customerId', $customer_id);
        $stmt->execute();

        // Update the inventory table based on the order type
        switch ($orderType) {
            case '500ml Water Bottle':
                $query = 'UPDATE inventory SET quantity = quantity - :quantity WHERE product_id = "WBottle1"';
                break;
            case 'New Slim Gallon':
                $query = 'UPDATE inventory SET quantity = quantity - :quantity WHERE product_id = "GallonA"';
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':quantity', $quantity);
                $stmt->execute();
                $query = 'UPDATE inventory SET quantity = quantity - :quantity WHERE product_name IN ("Sealed A", "Sealed B", "Sealed C") AND status = "In Use"';
                break;
            case 'New Round Gallon':
                $query = 'UPDATE inventory SET quantity = quantity - :quantity WHERE product_id = "GallonB"';
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':quantity', $quantity);
                $stmt->execute();
                $query = 'UPDATE inventory SET quantity = quantity - :quantity WHERE product_name = "Sealed D" AND status = "In Use"';
                break;
            case 'Slim Gallon Refill':
                $query = 'UPDATE inventory SET quantity = quantity - :quantity WHERE product_name IN ("Sealed A", "Sealed B", "Sealed C") AND status = "In Use"';
                break;
            case 'Round Gallon Refill':
                $query = 'UPDATE inventory SET quantity = quantity - :quantity WHERE product_name = "Sealed D" AND status = "In Use"';
                break;
        }

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->execute();

       
        $orderId = $conn->lastInsertId();
        $orderId++;


        $query = "ALTER TABLE orders AUTO_INCREMENT = $orderId";
        $stmt = $conn->prepare($query);
        $stmt->execute();

    
        $conn->commit();

        header('location: orderSuccess.php');
        exit();

    } catch (Exception $e) {
      
    }
}
?>


<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="../CSS/navstyle.css">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"/>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&family=Roboto+Condensed&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins&family=Titillium+Web&display=swap" rel="stylesheet">
<title>Place Order</title>
<link rel="icon" href="../image/Icon.png" type="image/x-icon">
</head>
<body>
<?php include ('../customer/customerSidebar.php')?>
  
<div class="container">

    <div class="waterbottle" data-aos="fade-down" data-aos-duration="1000" data-aos-delay="500">
      <img src="../image/order-items/500ml.png" alt="500ml Bottle" width="200px" height="200px" >
      <p>500ml Water Bottle =<br> ₱10.00</p>
    </div>
    <div class="waterbottle" data-aos="fade-down" data-aos-duration="1000" data-aos-delay="600">
      <img src="../image/order-items/slim-gallon.png" alt="500ml Bottle" width="200px" height="200px" >
      <p>Slim Gallon = ₱150.00 <br>
      (<b><i>Water included upon purchase</i></b>)
      </p>
    </div>
    <div class="waterbottle" data-aos="fade-down" data-aos-duration="1000" data-aos-delay="600">
      <img src="../image/order-items/round-gallon.png" alt="500ml Bottle" width="200px" height="200px" >
      <p>Round Gallon = ₱150.00 <br>
        (<b><i>Water included upon purchase</i></b>)
      </p>
    </div>
    <div class="waterbottle" data-aos="fade-down" data-aos-duration="1000" data-aos-delay="500">
      <img src="../image/order-items/gallon-refill.png" alt="500ml Bottle" width="200px" height="200px" >
      <p>Gallon Refill = ₱25.00</p>
    </div>
  
</div>

<div class="container">

 
<div class="contact-form" data-aos="zoom-in" data-aos-duration="1000">
    <form action="placeOrder.php" method="POST">  
      <div class="form-group">
        <h3>Place Your Order</h3>
      </div>

      <div class="quansel">
    
       <select name="order_type" class="orderType">
        <option value>*Select Order Type*</option>
         <option value="500ml Water Bottle">500ml Water Bottle</option>
         <option value="New Slim Gallon">New Slim Gallon </option>
         <option value="New Round Gallon">New Round Gallon</option>
         <option value="Slim Gallon Refill">Slim Gallon Refill</option>
         <option value="Round Gallon Refill">Round Gallon Refill</option>
       </select>
      </div> 

      <div class="orderInputsContainer">
      <div class="form-border">
        <input placeholder="Quantity" name="quantity"  type="number">
      </div>
      </div>

      <div id="price"></div>

      <div class="orderbutton">
        <button name="create">Place order</button>
      </div>

    </form>
  </div>

</div>

<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../customer/files/totalprice.js"></script>

</body>
</html>

</html>