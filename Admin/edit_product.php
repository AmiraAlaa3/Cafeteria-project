<?php
require('../includes/db.php');
if (!isset($_GET["product_id"])) {
    die("Product ID is required.");
}

$product_id = intval($_GET['product_id']);
$query = "SELECT * FROM products WHERE product_id = :product_id";
$sqlQuery = $connection->prepare($query);
$sqlQuery->bindParam(':product_id', $product_id, PDO::PARAM_INT);
$sqlQuery->execute();

$product = $sqlQuery->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['product_name'];
    $price = $_POST['product_price'];
    $imagePath = $product['product_img']; 

    if (isset($_FILES['product_img']) && $_FILES['product_img']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['product_img']['tmp_name'];
        $fileName = $_FILES['product_img']['name'];
        $fileSize = $_FILES['product_img']['size'];
        $fileType = $_FILES['product_img']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Sanitize the filename
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

        $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = 'uploaded_img/';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $imagePath = $newFileName;
            } else {
                die('Error: There was an error moving the file to the upload directory. Please ensure the upload directory is writable by the web server.');
            }
        } else {
            die('Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions));
        }
    }

    $updateQuery = "update products set product_name = :name, product_price = :price, product_img = :imagePath where product_id = :product_id";
    $updateStmt = $connection->prepare($updateQuery);
    $updateStmt->bindParam(':name', $name, PDO::PARAM_STR);
    $updateStmt->bindParam(':price', $price, PDO::PARAM_STR);
    $updateStmt->bindParam(':imagePath', $imagePath, PDO::PARAM_STR); 
    $updateStmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

    try {
        $updateStmt->execute();
    } catch (PDOException $e) {
        die('Error: ' . $e->getMessage());
    }

    header('Location: all_product.php?message=Product updated successfully');
    exit;
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
    <title>Admin: Edit Product</title>
</head>

<body>
    <?php include '../includes/header.php';?>
    <div class="container marginTop">
        <h3 class="display-5 mb-3">Edit Product</h3>
        <form action="edit_product.php?product_id=<?php echo $product_id; ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name"
                 value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="product_price" class="form-label">Price</label>
                <input type="text" class="form-control" id="product_price" name="product_price" value="<?php echo htmlspecialchars($product['product_price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="current_product_img" class="form-label">Current Product Image</label>
                <img src="uploaded_img/<?php echo htmlspecialchars($product['product_img']); ?>" alt="Product Image" class="img-thumbnail" style="width: 120px;">
            </div>
            <div class="mb-3">
                <label for="product_img" class="form-label">Upload New Product Image</label>
                <input type="file" class="form-control" id="product_img" name="product_img">
            </div>
            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>
</body>
</html>
