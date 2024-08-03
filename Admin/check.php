<?php
session_start();
require('../includes/db2.php');

$itemsPerPage = 5;
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : (isset($_GET['startDate']) ? $_GET['startDate'] : '');
$endDate = isset($_POST['endDate']) ? $_POST['endDate'] : (isset($_GET['endDate']) ? $_GET['endDate'] : '');
$userId = isset($_POST['user-acc']) ? $_POST['user-acc'] : (isset($_GET['userId']) ? $_GET['userId'] : '');

// Construct the SQL query to count total items for pagination
$totalQuery = "SELECT COUNT(*) FROM orders";
$conditions = [];

if ($startDate && $endDate) {
    $conditions[] = "order_date BETWEEN :startDate AND :endDate";
}
if ($userId && $userId !== 'all') {
    $conditions[] = "user_id = :userId";
}

if (!empty($conditions)) {
    $totalQuery .= " WHERE " . implode(' AND ', $conditions);
}

$totalStmt = $connection->prepare($totalQuery);
if ($startDate && $endDate) {
    $totalStmt->bindParam(':startDate', $startDate);
    $totalStmt->bindParam(':endDate', $endDate);
}
if ($userId && $userId !== 'all') {
    $totalStmt->bindParam(':userId', $userId);
}
$totalStmt->execute();
$totalItems = $totalStmt->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);

// Construct the SQL query to fetch orders based on date range and pagination
$query = "SELECT * FROM orders";
if (!empty($conditions)) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}
$query .= " LIMIT :limit OFFSET :offset";
$sqlQuery = $connection->prepare($query);
if ($startDate && $endDate) {
    $sqlQuery->bindParam(':startDate', $startDate);
    $sqlQuery->bindParam(':endDate', $endDate);
}
if ($userId && $userId !== 'all') {
    $sqlQuery->bindParam(':userId', $userId);
}
$sqlQuery->bindParam(':limit', $itemsPerPage, PDO::PARAM_INT);
$sqlQuery->bindParam(':offset', $offset, PDO::PARAM_INT);
$sqlQuery->execute();
$orders = $sqlQuery->fetchAll(PDO::FETCH_ASSOC);

// Fetch order items with product details
$orderItems = [];
if (!empty($orders)) {
    $orderIds = array_column($orders, 'order_id');
    $orderIdsPlaceholders = implode(',', array_fill(0, count($orderIds), '?'));

    // Updated SQL query to join with products table
    $itemsQuery = "SELECT oi.order_id, oi.quantity, p.product_name, p.product_img, p.product_price
                   FROM order_items oi
                   JOIN products p ON oi.product_id = p.product_id
                   WHERE oi.order_id IN ($orderIdsPlaceholders)";
    $itemsStmt = $connection->prepare($itemsQuery);
    $itemsStmt->execute($orderIds);
    $orderItemsData = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($orderItemsData as $item) {
        $orderItems[$item['order_id']][] = $item;
    }
}

// Fetch users
$sql = "SELECT * FROM users";
$stmt = $connection->prepare($sql);
$stmt->execute();
$users_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get any message from the URL parameters
$message = isset($_GET['message']) ? $_GET['message'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Load fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Rye&display=swap" rel="stylesheet">
    <!-- Load Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Load custom CSS -->
    <link rel="stylesheet" href="../CSS/style.css">
    <title>My Orders</title>
</head>

<body>
    <!-- Navbar Start -->
    <?php include '../includes/header.php'; ?>
    <!-- Navbar End -->

    <!-- Main content -->
    <main class="container my-5 marginTop">
        <div class="mb-3">
        <h3 class="display-5 mb-5">Orders</h3>
      
            <!-- Date selection form -->
            <form method="POST" class="d-flex justify-content-start align-items-start mb-3 flex-wrap flex-column">
                <select class="form-select mb-3" name="user-acc" aria-label="Default select example" style="width:70%; height:45px; padding-left:8px;font-size:16px;">
                    <option value="all" <?= $userId === 'all' ? 'selected' : ''; ?>>All Users</option>
                    <?php foreach ($users_data as $user) : ?>
                        <option value="<?= $user['user_id']; ?>" <?= $user['user_id'] == $userId ? 'selected' : ''; ?>><?= htmlspecialchars($user['user_name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="my-5 w-100">
                    <label class="me-1">Start Date:</label>
                    <input type="date" style="width:30%;" id="startDate" name="startDate" class="placeholder-label me-5" value="<?php echo htmlspecialchars($startDate); ?>">
                    <label class="ms-5 me-1">End Date:</label>
                    <input type="date" id="endDate" style="width:30%;" name="endDate" class="placeholder-label me-5" value="<?php echo htmlspecialchars($endDate); ?>">
                </div>

                <button type="submit" class="btn btn-primary my-4">View</button>
            </form>
        </div>

        <!-- Table to display orders -->
        <div class="table-responsive-lg">
            <table class="table my-4">
                <thead>
                    <tr class="table-dark table-color">
                        <th>Order Date</th>
                        <th>Amount</th>
                        <th>Items</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through orders and display each order in a table row -->
                    <?php foreach ($orders as $order) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                            <td><?php echo htmlspecialchars($order['total_amount']); ?></td>
                            <td>
                                <ul class="list-unstyled">
                                    <?php if (!empty($orderItems[$order['order_id']])): ?>
                                        <?php foreach ($orderItems[$order['order_id']] as $item): ?>
                                            <li class="mb-2 d-flex align-items-center">
                                                <img src="uploaded_img/<?php echo htmlspecialchars($item['product_img']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                                <span><?php echo htmlspecialchars($item['product_name']); ?>
                                                - Price: <?php echo htmlspecialchars($item['product_price']); ?> EG
                                                 - Quantity: <?php echo htmlspecialchars($item['quantity']); ?>
                                                </span>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li>No items found for this order.</li>
                                    <?php endif; ?>
                                </ul>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
     
        <!-- Pagination navigation -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <!-- Previous page link -->
                <?php if ($currentPage > 1) : ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $currentPage - 1 ?>&startDate=<?= $startDate ?>&endDate=<?= $endDate ?>&userId=<?= $userId ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Page numbers -->
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&startDate=<?= $startDate ?>&endDate=<?= $endDate ?>&userId=<?= $userId ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Next page link -->
                <?php if ($currentPage < $totalPages) : ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $currentPage + 1 ?>&startDate=<?= $startDate ?>&endDate=<?= $endDate ?>&userId=<?= $userId ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

    </main>

    <footer class="footer bg-dark text-light p-2 mt-4">
        <p class="text-center m-0">&copy; Cafeteria Shop. All Rights Reserved.</p>
    </footer>

    <!-- Load Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js" integrity="sha512-3MO0bG13j5C4nAbyO3V8lCOXyOnltwzX7i0k79AhUyq0EYGCawNZT7iBkQnOUMRf7eZj6kZqpbUVsbNKHQJMZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>
