<?php
// Start a session
session_start();
// Include the database connection file
require('../includes/db2.php');

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}

$user_email = $_SESSION['user_email'];

// Get the user details
$getUser = "SELECT user_id FROM users WHERE email = :email";
$sqlGetUser = $connection->prepare($getUser);
$sqlGetUser->bindParam(':email', $user_email);
$sqlGetUser->execute();
$user = $sqlGetUser->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: login.php');
    exit();
}

$user_id = $user['user_id'];

// Check if the order ID is provided
if (!isset($_GET['id'])) {
    header('Location: order_history.php?message=No+order+ID+provided');
    exit();
}

$order_id = $_GET['id'];

// Verify that the order belongs to the logged-in user and is still processing
$checkOrder = "SELECT * FROM orders WHERE order_id = :order_id AND user_id = :user_id AND status = 'processing'";
$sqlCheckOrder = $connection->prepare($checkOrder);
$sqlCheckOrder->bindParam(':order_id', $order_id);
$sqlCheckOrder->bindParam(':user_id', $user_id);
$sqlCheckOrder->execute();
$order = $sqlCheckOrder->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header('Location: orders.php?message=Order+not+found+or+cannot+be+canceled');
    exit();
}

// Delete the order from the database
$deleteOrder = "DELETE FROM orders WHERE order_id = :order_id";
$sqlDeleteOrder = $connection->prepare($deleteOrder);
$sqlDeleteOrder->bindParam(':order_id', $order_id);

if ($sqlDeleteOrder->execute()) {
    header('Location: order_history.php?message=Order+successfully+deleted');
} else {
    header('Location: order_history.php?message=Failed+to+delete+order');
}

exit();