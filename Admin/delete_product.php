<?php
session_start();
require('../includes/db.php');

if (!isset($_GET['product_id'])) {
    die("Product ID is required.");
}

$product_id = intval($_GET['product_id']);


$delete_query = "DELETE FROM products WHERE product_id = :product_id";
$delete_stmt = $connection->prepare($delete_query);
$delete_stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
$delete_stmt->execute();

if ($delete_stmt->rowCount() > 0) {
    $message = "Product deleted successfully.";
} else {
    $message = "Failed to delete product.";
}

header("Location: all_product.php?message=$message");
exit();
?>
