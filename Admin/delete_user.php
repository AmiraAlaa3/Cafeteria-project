<?php
session_start();
require('../includes/db2.php');

if (!isset($_GET['user_id'])) {
    die("User ID is required.");
}

$user_id = intval($_GET['user_id']);


$delete_query = "DELETE FROM users WHERE user_id = :user_id";
$delete_stmt = $connection->prepare($delete_query);
$delete_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$delete_stmt->execute();

if ($delete_stmt->rowCount() > 0) {
    $message = "User deleted successfully.";
} else {
    $message = "Failed to delete User.";
}

header("Location: all_user.php?message=$message");
exit();
?>