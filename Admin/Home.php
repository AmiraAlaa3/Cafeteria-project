<?php
session_start();
require('../includes/db2.php');

$productCountQuery = "select COUNT(*) as total_products from products";
$productCountStmt = $connection->prepare($productCountQuery);
$productCountStmt->execute();
$productCount = $productCountStmt->fetch(PDO::FETCH_ASSOC)['total_products'];

$orderCountQuery = "select COUNT(*) as total_orders from orders";
$orderCountStmt = $connection->prepare($orderCountQuery);
$orderCountStmt->execute();
$orderCount = $orderCountStmt->fetch(PDO::FETCH_ASSOC)['total_orders'];


$totalAmountQuery = "select SUM(total_amount) as total_revenue from orders";
$totalAmountStmt = $connection->prepare($totalAmountQuery);
$totalAmountStmt->execute();
$totalAmount = $totalAmountStmt->fetch(PDO::FETCH_ASSOC)['total_revenue'];


$userCountQuery = "select COUNT(*) as total_users from users where role = 'user'";
$userCountStmt = $connection->prepare($userCountQuery);
$userCountStmt->execute();
$userCount = $userCountStmt->fetch(PDO::FETCH_ASSOC)['total_users'];


$productsSoldQuery = "select COUNT(*) as products_sold from products where product_status != 'available'";
$productsSoldStmt = $connection->prepare($productsSoldQuery);
$productsSoldStmt->execute();
$productsSold = $productsSoldStmt->fetch(PDO::FETCH_ASSOC)['products_sold'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Font and Bootstrap CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Rye&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- css -->
    <link rel="stylesheet" href="../CSS/style.css">
    <title>Admin Dashboard</title>
</head>

<body>

    <?php include '../includes/header.php'; ?>
    <!-- main  -->
    <main class="container marginTop">
        <h3 class="display-5 mb-3">Dashboard</h3>

        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="card mb-3 p-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="icon d-flex justify-content-center align-items-center me-5"><i class="fa-solid fa-cubes"></i></div>
                        <div>
                            <h3 class="card-title">Total Products</h3>
                            <h5 class="card-title"><?php echo $productCount; ?></h5>
                            <p class="card-text">Total products available in the store.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card mb-3 p-3">
                    <div class="card-body  d-flex justify-content-between align-items-center">
                        <div class="icon d-flex justify-content-center align-items-center me-5"><i class="fa-solid fa-bag-shopping"></i></div>
                        <div>
                            <h3 class="card-title">Total Orders</h3>
                            <h5 class="card-title"><?php echo $orderCount; ?></h5>
                            <p class="card-text">Total number of orders placed.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card mb-3 p-3">
                    <div class="card-body  d-flex justify-content-between align-items-center">
                        <div class="icon d-flex justify-content-center align-items-center me-5"><i class="fa-solid fa-money-check-dollar"></i></div>
                        <div>
                            <h3 class="card-title">Total Revenue</h3>
                            <h5 class="card-title"><?php echo number_format($totalAmount, 2); ?> EG</h5>
                            <p class="card-text">Total revenue generated from sales.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card mb-3 p-3">
                    <div class="card-body  d-flex justify-content-between align-items-center">
                        <div class="icon d-flex justify-content-center align-items-center me-5"><i class="fa-solid fa-users"></i></div>
                        <div>
                            <h3 class="card-title ">Users</h3>
                            <h5 class="card-title"><?php echo $userCount; ?></h5>
                            <p class="card-text">Number of active users on the platform.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card mb-3 p-3">
                    <div class="card-body  d-flex justify-content-between align-items-center">
                        <div class="icon d-flex justify-content-center align-items-center me-5"><i class="fa-solid fa-battery-quarter"></i></div>
                        <div>
                            <h3 class="card-title">Products Sold</h3>
                            <h5 class="card-title"><?php echo $productsSold; ?></h5>
                            <p class="card-text">Total number of products sold.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-light p-2">
        <p class="text-center m-0">&copy; Cafeteria Shop. All Rights Reserved.</p>
    </footer>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>