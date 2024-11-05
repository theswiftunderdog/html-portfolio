<div class='nav'>

  <div class='logo'>
  <img src="../image/Icon.png" alt="User Image" id="userImage"/>
    <span><?= $user['full_name']?></span>
  </div>
<ul>
    <li ><i class="bi bi-house"></i><a href="customerPortal.php"><p>Dashboard</p></a></li>

    <li ><i class="bi bi-person-circle"></i><a href="profile.php"><p>Profile</p></a></li>

    <li ><i class="bi bi-cart4"></i><a href="placeOrder.php"><p>Place Order</p></a></li>

    <li ><i class="bi bi-basket3"></i><a href="orderDetails.php"><p>Order Details</p></a></li>

    <li class="last"><i class="bi bi-box-arrow-right"></i><a href="../Connection/logout.php">Logout</a></li> <!-- destroy sess-->
<i class="fa fa-audio-description" aria-hidden="true"></i>
</ul>

</div>