<?php
    // Start the session.
    session_start();
    if (!isset($_SESSION['user'])) {
        header('location: login.php');
        exit;
    }

    include('../Connection/Connection.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controlNo = $_POST['controlNo'];
        $orderId = $_POST['orderId'];
        $orderName = $_POST['orderName'];
        $quantity = $_POST['quantity'];
        $dateTime = $_POST['datetime'];
        $status = $_POST['status'];
        $description = $_POST['description'];

        $sql = "INSERT INTO sales (controlno, order_id, order_name, qty, datetime, status, description) VALUES (:controlNo, :orderId, :orderName, :quantity, :dateTime, :status, :description)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':controlNo', $controlNo);
        $stmt->bindParam(':orderId', $orderId);
        $stmt->bindParam(':orderName', $orderName);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':dateTime', $dateTime);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        header('location: add_sales.php');
        exit;
    }
?>