<?php
// Include your database connection file
include '../includes/db2.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $_POST['Category_Name'];

    // Insert the new category into the database using PDO
    $query = "INSERT INTO categories (category_name) VALUES (:category_name)";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':category_name', $category_name);

    if ($stmt->execute()) {
        $message = "Category added successfully!";
        // Redirect back to add product page with success message
        header("Location: add_product.php?message=" . urlencode($message));
        exit();
    } else {
        $message = "Error: " . $stmt->errorInfo()[2];
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
    <title>Admin: Add Category</title>
</head>

<body>
    <?php include '../includes/header.php';?>
    <!-- main  -->
    <main class="container marginTop">
        <h3 class="display-5 mb-5">Add Category</h3>
        <?php if ($message): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <form class="container" action="" method="post">
            <div class="mb-3">
                <label for="category_name" class="form-label">Category Name</label>
                <input class="form-control" name="Category_Name" id="category_name" type="text" placeholder="Enter category name" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Category</button>
        </form>
    </main>
    <footer class="footer bg-dark text-light p-2">
        <p class="text-center m-0">&copy; Cafeteria Shop. All Rights Reserved.</p>
    </footer>
    <!-- bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js" integrity="sha512-tY9XCrm5Jlnt90T0DdQJ0GOMyk2I5kcfMDSPPQcQu1nTjFszAIt4nt2QcvE8QunO8yJ3CNlf4q3XmtBQy2E3Qg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>
