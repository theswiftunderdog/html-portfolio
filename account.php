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
<link rel="stylesheet" href="CSS/navstyle.css">


<div class='nav'>

  <div class='logo'>
    <img src="image/Icon.png" alt="User Image" id="userImage"/>
    <span><?= $user['full_name']?></span>
  </div>
<ul>
    <li ><i class="bi bi-house"></i><a href="user-nav.php"><p>Dashboard</p></a></li>
    <li ><i class="bi bi-person-circle"></i><a href="account.php"><p>Account Information</p></a></li>
    <li ><i class="bi bi-cart4"></i><a href="orderP.php"><p>Place Order</p></a></li>
    <li ><i class="bi bi-basket3"></i><a href="orderD.php"><p>Order Details</p></a></li>
    <li class="last"><i class="bi bi-box-arrow-right"></i><a href="Connection/logout.php">Logout</a></li> <!-- destroy sess-->
<i class="fa fa-audio-description" aria-hidden="true"></i>
</ul>

</div>

</head>
<body>
  
<div class='bod'>
    <h3>This is the Account Information page.</h3>    
</div>

</body>
</html>
