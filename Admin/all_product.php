<?php
session_start();
require('../includes/db.php');

$itemsPerPage = 3; 
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($currentPage - 1) * $itemsPerPage;


$totalQuery = "select count(*) from products";
$totalStmt = $connection->prepare($totalQuery);
$totalStmt->execute();
$totalItems = $totalStmt->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);

$query = "select * from products limit :limit OFFSET :offset";
$sqlQuery = $connection->prepare($query);
$sqlQuery->bindParam(':limit', $itemsPerPage, PDO::PARAM_INT);
$sqlQuery->bindParam(':offset', $offset, PDO::PARAM_INT);
$sqlQuery->execute();
$products = $sqlQuery->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Admin: All Product</title>
</head>

<body>
    <?php
    include '../includes/header.php';
    ?>
    <main class="container mt-5 marginTop">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="display-5 mb-0">All Products</h3>
            <a href="add_product.php" class="btn btn-primary">Add Product</a>
        </div>
        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        <div class="table-responsive-lg">
        <table class="table my-4">
            <thead>
                <tr class="table-dark">
                    <th>Id</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product) { ?>
                    <tr>
                        <td><?php echo $product['product_id']; ?></td>
                        <td><?php echo $product['product_name']; ?></td>
                        <td><?php echo $product['product_price']; ?></td>
                        <td>
                            <img src="uploaded_img/<?php echo $product['product_img']; ?>" alt="Product Image" class="img-thumbnail" style="width: 80px;">
                        </td>
                        <td>
                            <a class='btn btn-sm btn-primary ml-2' href="edit_product.php?product_id=<?php echo $product['product_id']; ?>">Edit</a>
                            <a class='btn btn-sm btn-danger' href="delete_product.php?product_id=<?php echo $product['product_id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
      

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php if ($currentPage > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo $i == $currentPage ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </main>



    <footer class="footer bg-dark text-light p-2">
        <p class="text-center m-0">&copy; Cafeteria Shop. All Rights Reserved.</p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>