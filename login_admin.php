<?php
session_start();

if(isset($_SESSION['user'])) {
    header('Location: admin/adminportal.php');
    exit();
}

$error_message = '';

if($_POST) {
    include('Connection/Connection.php');

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = 'SELECT * FROM customer WHERE email=:email AND password=:password';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetchAll()[0];
        $_SESSION['user'] = $user;
        
        header('Location: admin/adminportal.php');
        exit();
    }

    $query = 'SELECT * FROM admin WHERE email=:email AND password=:password';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetchAll()[0];
        $_SESSION['user'] = $user;
        
        header('Location: supAdmin/index.php');
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
    <link rel="stylesheet" href="adminlogin.css">
    <title>Admin Login</title>
</head>
<body>

<body id="loginbody">
    <?php if(!empty($error_message)) { ?>
        <div id="errorMessage">
            <strong>ERROR:</strong> <p><?= $error_message ?></p>
        </div>
    <?php } ?>
    
<div class="container">
    

    <div class="loginbody">
    <form action="login_admin.php" method="POST">
        <div class="cat">            
            <p> Admin Login Page</p>

            <label for="">EMAIL</label>
            <input placeholder="-insert email address here-" name="email" type="text">
        </div>
        <div class="cat" >
            <label for="">PASSWORD</label>
            <input placeholder="-insert password here-" name="password" type="password">
        </div>
        <div class="cat">
            <button>Login</button>
        </div>
        <div class="cat">
            <a href="../TentianSys/login.php">Return to Portal Selection Page</a>
            </div>
        </form>
    </div>
</div>   


</body>
</html>