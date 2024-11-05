<?php

$servername = 'localhost';
$email = 'root';
$password = 'root';


try{
    $conn = new PDO ("mysql:host=$servername;dbname=tentian", $email, $password);
 
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
}   catch (\Exception $e) {
    $error_message = $e->getMessage();
}

?>