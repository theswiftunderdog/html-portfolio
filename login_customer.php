<?php
session_start();

if(isset($_SESSION['users'])) {
    header('Location: customer/customerPortal.php');
    exit();
}

$error_message = '';

if($_POST) {
    include('Connection/Connection.php');

    $email = $_POST['email'];
    $password = $_POST['password'];


    // Check if Customer
    $query = 'SELECT * FROM users WHERE email=:email AND password=:password';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetchAll()[0];
        $_SESSION['users'] = $user;
        
        header('Location: customer/customerPortal.php');
        exit();
    }


    $error_message = 'Please make sure that email and password are correct.';
}
?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login</title>
    <link rel="icon" href="../TentianSys/image/Icon.png" type="image/x-icon">
</head>
<body>

<body id="loginbody">
    <?php if(!empty($error_message)) { ?>
        <div id="errorMessage">
            <strong>ERROR:</strong> <p><?= $error_message ?></p>
        </div>
    <?php } ?>
<div class="container">
    <div class="loginheader">
         <p>Customer Login Page</p>
    </div>

    <div class="loginbody" >
    <form action="login_customer.php" method="POST">
        <div class="loginInputsContainer">
            <label for="">EMAIL</label>
            <input placeholder="-insert email address here-" name="email" type="text">
        </div>
        <div class="loginInputsContainer">
            <label for="">PASSWORD</label>
            <input placeholder="-insert password here-" name="password" type="password">
        </div>
        <div class="loginbutton">
            <button>Login</button>
        </div>
        <div id="portalreturn">
            <a href="../TentianSys/login.php">Return to Portal Selection Page</a>
        </div>
    </form>
    </div>
</div>  


</body>
</html>