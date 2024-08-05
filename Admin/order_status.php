<?php
require('../includes/db2.php');

if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);
     
    $sql = "UPDATE orders SET status = 'out for delivery' WHERE order_id = :order_id";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: all_order.php?message=order out for delivery');
    exit;
} else {
    
    header('Location: all_order.php?message=order not found.');
    exit;
}
?>
