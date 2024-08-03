<!-- screen 3 -->
<?php
require('../includes/db2.php');
?>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cafeteria";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT id, name FROM Users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $productSql = "SELECT id, name, price FROM Products";
    $productStmt = $conn->prepare($productSql);
    $productStmt->execute();
    $products = $productStmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>Order Page</title>
    <style>
        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .product {
            width: 100px;
            text-align: center;
            border: 1px solid #ccc;
            padding: 10px;
        }
    </style>
</head>
<body>
    <form method="post" action="">
        <label for="user">Add to user:</label>
        <select name="user" id="user">
            <?php foreach ($users as $user): ?>
                <option value="<?= $user['id']; ?>"><?= htmlspecialchars($user['name']); ?></option>
            <?php endforeach; ?>
        </select>
        
        <div class="product-container">
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <img src="images/<?= $product['name']; ?>.png" alt="<?= htmlspecialchars($product['name']); ?>" width="50">
                    <p><?= htmlspecialchars($product['name']); ?></p>
                    <p><?= htmlspecialchars($product['price']); ?> LE</p>
                </div>
            <?php endforeach; ?>
        </div>
    </form>
</body>
</html>
