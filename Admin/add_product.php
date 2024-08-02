<?php
// Include your database connection file
include '../includes/db_connect.php';

$message = isset($_GET['message']) ? $_GET['message'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['Name'];
    $price = $_POST['Price'];
    $category_id = $_POST['Category'];
    $image = $_FILES['Product_Picture'];

    // Handle the file upload
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($image["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($image["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $uploadOk = 0;
    }

    // Check file size
    if ($image["size"] > 500000) {
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $message = "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($image["tmp_name"], $target_file)) {
            $product_img = basename($image["name"]);

            // Insert the product data into the database using PDO
            $query = "INSERT INTO products (product_name, product_price, category_id, product_img) VALUES (:product_name, :product_price, :category_id, :product_img)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':product_name', $name);
            $stmt->bindParam(':product_price', $price);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':product_img', $product_img);

            if ($stmt->execute()) {
                $message = "Product added successfully!";
            } else {
                $message = "Error: " . $stmt->errorInfo()[2];
            }
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    }
}

// Fetch categories from the database using PDO
$categories = [];
$query = "SELECT category_id, category_name FROM categories";
$stmt = $conn->query($query);

if ($stmt) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
        <form class="container" action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Product</label>
                <input class="form-control" name="Name" id="name" type="text" placeholder="Enter name of product" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input class="form-control" name="Price" id="price" type="number" step="0.01" placeholder="Enter price" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" name="Category" id="category" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <a href="add_category.php">Add Category</a>
            </div>
            <div class="mb-3">
                <label for="product_picture" class="form-label">Product Picture</label>
                <input class="form-control" type="file" name="Product_Picture" id="product_picture" required>
            </div>
            <button type="submit" name="add_product" class="btn btn-primary">Save</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </form>
    </main>
    <footer class="footer bg-dark text-light p-2">
        <p class="text-center m-0">&copy; Cafeteria Shop. All Rights Reserved.</p>
    </footer>
    <!-- bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js" integrity="sha512-tY9XCrm5Jlnt90T0DdQJ0GOMyk2I5kcfMDSPPQcQu1nTjFszAIt4nt2QcvE8QunO8yJ3CNlf4q3XmtBQy2E3Qg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>