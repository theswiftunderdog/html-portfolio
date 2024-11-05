<?php
    //Start the session.
    session_start();
    if(!isset($_SESSION['user'])) header('location: login.php');

    $user = ($_SESSION['user']);
    
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


</head>

<!-- ITO NA YUNG PANG CONTENT -->
<body>
<?php include ('../admin/adminsidebar.php')?>


<div class="main-content">
<div class="form-group">
  <div class="header-font">
    <h1>Add Sales</h1>
  </div>
    <form id="productForm" method="POST" action="Funcsales.php" enctype="multipart/form-data">

    <div class="form-row">
  <label for="productId">Control No:</label>
  <div class="input-container">
    <input type="number" id="controlNo" name="controlNo" required min="000000001" max="999999999">
  </div>
</div>


<div class="form-row">
  <label for="productId">Order ID</label>   
  <div class="input-container">
    <input type="number" id="orderId" name="orderId" required min="000000001" max="999999999">
  </div>
</div>

      <div class="form-row">
        <label for="productName">Order Name:</label>
        <div class="input-container">
          <input type="text" id="orderName" name="orderName" required>
        </div>
      </div>

      <div class="form-row">
        <label for="quantity">Quantity:</label>
        <div class="input-container">
          <input type="number" id="quantity" name="quantity" required>
        </div>
      </div>

      <div class="form-row">
        <label for="description">Date Time:</label>
        <div class="input-container">
          <input type="datetime-local" id="datetime" name="datetime" required></textarea>
        </div>
      </div>

      <div class="form-row">
        <label for="quantity">Status:</label>
        <div class="input-container">
          <input type="text" id="status" name="status" required>
        </div>
      </div>


      <div class="form-row">    
        <label for="description">Description:</label>
        <div class="input-container">
          <textarea id="description" name="description" required></textarea>
        </div>
      </div>


      <div>
        <button type="submit">Add Sales</button>
      </div>
    </form>
    </div>
  </div>

</body>

<script src="../admin/files/adminscript.js"></script>
  <script src="../admin/files/rotate.js"></script>
  



</html>
