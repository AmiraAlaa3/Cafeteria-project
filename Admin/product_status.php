<?php
session_start();
require('../includes/db2.php');

if (isset($_GET['product_id'])) {
    $productId = intval($_GET['product_id']);
    $query = "select product_status from products where product_id = :productId";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':productId', $productId);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $newStatus = $product['product_status'] === 'available' ? 'unavailable' : 'available';
        $updateQuery = "update products set product_status = :newStatus WHERE product_id = :productId";
        $updateStmt = $connection->prepare($updateQuery);
        $updateStmt->bindParam(':newStatus', $newStatus);
        $updateStmt->bindParam(':productId', $productId);
        $updateStmt->execute();
        header("Location: all_product.php?message=Product status updated successfully");
        exit();
    } else {
        header("Location: all_product.php?message=Product not found");
        exit();
    }
} else {
    header("Location: all_product.php?message=No product ID provided");
    exit();
}
