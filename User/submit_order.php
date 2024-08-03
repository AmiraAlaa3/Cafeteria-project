<?php
require('../includes/db2.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['user_id'], $data['products'], $data['notes'], $data['room'], $data['total'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required data fields']);
        exit;
    }

    $userId = $data['user_id'];
    $products = $data['products'];
    $notes = $data['notes'];
    $room = $data['room'];
    $totalPrice = $data['total'];
    $orderDate = date('Y-m-d H:i:s');

    try {
        $connection->beginTransaction();
        $orderQuery = "INSERT INTO orders (user_id, total_amount, notes, order_date) VALUES (:userId, :total, :notes, :orderDate)";
        $orderStmt = $connection->prepare($orderQuery);
        $orderStmt->bindParam(':userId', $userId);
        $orderStmt->bindParam(':total', $totalPrice);
        $orderStmt->bindParam(':notes', $notes);
        $orderStmt->bindParam(':orderDate', $orderDate);
        $orderStmt->execute();

        $orderNumber = $connection->lastInsertId();

        foreach ($products as $product) {
            $query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:orderNumber, :productId, :quantity, :price)";
            $stmt = $connection->prepare($query);
            $stmt->bindParam(':orderNumber', $orderNumber);
            $stmt->bindParam(':productId', $product['id']);
            $stmt->bindParam(':quantity', $product['quantity']);
            $stmt->bindParam(':price', $product['price']);

            if (!$stmt->execute()) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to insert product']);
                exit;
            }
        }

        $connection->commit();
        
        echo json_encode(['status' => 'success', 'message' => 'Order placed successfully!']);
    } catch (Exception $e) {
        $connection->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Transaction failed: ' . $e->getMessage()]);
    }
}
?>
