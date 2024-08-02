<?php
// Start a session
session_start();

// Include the database connection file
require('../includes/db.php');

// Define the number of items per page for pagination
$itemsPerPage = 3;

// Get the current page number from the URL or default to 1 if not set
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Calculate the offset for the SQL query
$offset = ($currentPage - 1) * $itemsPerPage;

// Get the start and end dates from POST or GET request
$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : (isset($_GET['startDate']) ? $_GET['startDate'] : '');
$endDate = isset($_POST['endDate']) ? $_POST['endDate'] : (isset($_GET['endDate']) ? $_GET['endDate'] : '');

// Construct the SQL query to count total items for pagination
$totalQuery = "SELECT COUNT(*) FROM orders";
if ($startDate && $endDate) {
    $totalQuery .= " WHERE order_date BETWEEN :startDate AND :endDate";
}
$totalStmt = $connection->prepare($totalQuery);
if ($startDate && $endDate) {
    $totalStmt->bindParam(':startDate', $startDate);
    $totalStmt->bindParam(':endDate', $endDate);
}
$totalStmt->execute();
$totalItems = $totalStmt->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);

// Construct the SQL query to fetch orders based on date range and pagination
$query = "SELECT * FROM orders";
if ($startDate && $endDate) {
    $query .= " WHERE order_date BETWEEN :startDate AND :endDate";
}
$query .= " LIMIT :limit OFFSET :offset";
$sqlQuery = $connection->prepare($query);
if ($startDate && $endDate) {
    $sqlQuery->bindParam(':startDate', $startDate);
    $sqlQuery->bindParam(':endDate', $endDate);
}
$sqlQuery->bindParam(':limit', $itemsPerPage, PDO::PARAM_INT);
$sqlQuery->bindParam(':offset', $offset, PDO::PARAM_INT);
$sqlQuery->execute();
$orders = $sqlQuery->fetchAll(PDO::FETCH_ASSOC);

// Get any message from the URL parameters
$message = isset($_GET['message']) ? $_GET['message'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <!-- Include the header -->
    <?php include '../includes/header.php'; ?>

    <!-- Main content -->
    <main class="container mt-5 marginTop">
        <div class="justify-content-between align-items-center mb-3">
            <br>
            <h3 class="display-5 mb-0">My Orders</h3>
            <br>
            <!-- Date selection form -->
            <form method="POST" action="orders.php" class="d-flex align-items-center mb-3">
                <label class="me-1">Start Date:</label>
                <input type="date" id="startDate" name="startDate" class="placeholder-label me-5" value="<?php echo htmlspecialchars($startDate); ?>">
                <br><br>
                
                <label class="ms-5 me-1">End Date:</label>
                <input type="date" id="endDate" name="endDate" class="placeholder-label me-5" value="<?php echo htmlspecialchars($endDate); ?>">
                <br><br>
                
                <button type="submit" class="btn btn-primary">View</button>
            </form>
        </div>
        
        <!-- Table to display orders -->
        <div class="table-responsive-lg">
            <table class="table my-4">
                <thead>
                    <tr class="table-dark">
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through orders and display each order in a table row -->
                    <?php foreach ($orders as $order) { ?>
                        <tr>
                            <td><?php echo $order['order_date']; ?></td>
                            <td><?php echo $order['status']; ?></td>
                            <td><?php echo $order['total_amount']; ?></td>
                            <td>
                                <?php if ($order['status'] == 'processing') { ?>
                                    <a class='btn btn-sm btn-danger' href="cancel_order.php?id=<?php echo $order['order_id']; ?>">Cancel</a>
                                <?php } ?>
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
                <?php if ($currentPage > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>&startDate=<?php echo urlencode($startDate); ?>&endDate=<?php echo urlencode($endDate); ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Page number links -->
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo $i == $currentPage ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&startDate=<?php echo urlencode($startDate); ?>&endDate=<?php echo urlencode($endDate); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Next page link -->
                <?php if ($currentPage < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>&startDate=<?php echo urlencode($startDate); ?>&endDate=<?php echo urlencode($endDate); ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </main>

    <!-- Footer -->
    <footer class="footer bg-dark text-light p-2">
        <p class="text-center m-0">&copy; Cafeteria Shop. All Rights Reserved.</p>
    </footer>

    <!-- Load Bootstrap JS bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>


