<?php
session_start();
require('../includes/db2.php');

// Fetch Header Order Data
$sqlofHeadOrder = "SELECT order_id, order_date, status, user_name, room_no FROM orders JOIN users ON users.user_id = orders.user_id where orders.status='processing'";
$ResultOfHead = $connection->prepare($sqlofHeadOrder);
$ResultOfHead->execute();
$headOrders = $ResultOfHead->fetchAll(PDO::FETCH_ASSOC);

// Fetch Body Order Data
$sqlofBodyOrder = "SELECT orders.order_id, total_amount, product_name, product_img, quantity, price 
                   FROM products
                   JOIN order_items ON order_items.product_id = products.product_id
                   JOIN orders ON order_items.order_id = orders.order_id";
$ResultOfBody = $connection->prepare($sqlofBodyOrder);
$ResultOfBody->execute();
$bodyOrders = $ResultOfBody->fetchAll(PDO::FETCH_ASSOC);
$message = isset($_GET['message']) ? $_GET['message'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Rye&display=swap" rel="stylesheet">
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- css -->
    <link rel="stylesheet" href="../CSS/style.css">
    <title>Admin: All Orders</title>
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <div class="container mt-4 marginTop">
        <h3 class="display-5 mb-3">All Orders</h3>
        <?php if ($message): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php foreach ($headOrders as $headRow) : ?>
            <div class="order-container mb-4">
                <h2>Order ID: <?php echo htmlspecialchars($headRow["order_id"]); ?></h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th>User Name</th>
                            <th>Room No</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($headRow["order_date"]); ?></td>
                            <td><?php echo htmlspecialchars($headRow["status"]); ?></td>
                            <td><?php echo htmlspecialchars($headRow["user_name"]); ?></td>
                            <td><?php echo htmlspecialchars($headRow["room_no"]); ?></td>
                            <td> <?php if ($headRow["status"] === 'processing') : ?>
                             <a href="order_status.php?order_id=<?php echo $headRow['order_id']; ?>" class="btn btn-success">Out for Delivery</a>
                             <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <div class="row my-3">
                                    <?php
                                    // Filter products for the current order
                                    $orderProducts = array_filter($bodyOrders, function ($row) use ($headRow) {
                                        return $row['order_id'] == $headRow['order_id'];
                                    });

                                    foreach ($orderProducts as $bodyRow) :   ?>
                                        <div class="col-lg-4 col-md-4 mb-5 product-col">
                                            <div class="product-card">
                                                <img src="uploaded_img/<?php echo htmlspecialchars($bodyRow['product_img']); ?>" alt="<?php echo htmlspecialchars($bodyRow['product_name']); ?>">
                                                <div class="price"><?php echo htmlspecialchars($bodyRow['price']); ?></div>
                                                <div class="product-details">
                                                    <h5><?php echo htmlspecialchars($bodyRow['product_name']); ?></h5>
                                                    <p>Quantity: <?php echo htmlspecialchars($bodyRow['quantity']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <p class="fs-4"><strong>Total Price:</strong> <?php echo htmlspecialchars($bodyRow['total_amount']); ?></p>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    </div>
    <footer class="bg-dark text-light p-2 mt-4">
        <p class="text-center m-0">&copy; Cafeteria Shop. All Rights Reserved.</p>
    </footer>
    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>