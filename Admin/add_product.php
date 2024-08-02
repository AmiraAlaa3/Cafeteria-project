<?php
// Include your database connection file
include '../includes/db.php';

$message = isset($_GET['message']) ? $_GET['message'] : '';
// Fetch categories from the database
$categories = [];
$query = "SELECT category_id, category_name FROM categories";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
}
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
    <title>Admin: Add Products</title>
</head>

<body>
    <?php include '../includes/header.php';?>
    <!-- main  -->
    <main class="container marginTop">
        <h3 class="display-5 mb-5">Add Products</h3>
        <?php if ($message): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <form class="container" action="process_add.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Product</label>
                <input class="form-control" name="Name" id="name" type="text" placeholder="Enter name of product">
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input class="form-control" name="Price" id="price" type="number" step="0.01" placeholder="Enter price">
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" name="Category" id="category">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <a href="add_category.php">Add Category</a>
            </div>
            <div class="mb-3">
                <label for="product_picture" class="form-label">Product Picture</label>
                <input class="form-control" type="file" name="Product_Picture" id="product_picture">
            </div>
            <button type="submit" name="add_product" class="btn btn-primary">Save</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </form>
       
    </main>
    <footer class="footer bg-dark text-light p-2">
        <p class="text-center m-0">&copy; Cafeteria Shop. All Rights Reserved.</p>
    </footer>

</body>
</html>