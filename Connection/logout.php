<?php
    session_start();

    //remove all session variables 
    session_unset();

    //destroy
    session_destroy();

    $_SESSION['error_message'] = 'Logout Successfully';
    header('location:../login.php')
?>