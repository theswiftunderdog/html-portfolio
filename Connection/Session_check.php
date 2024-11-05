<?php
// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('location: login.php');
    exit;
}
