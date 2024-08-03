<?php
require('../includes/db2.php');

// Fetch users
$sql = "SELECT * FROM users";
$stmt = $connection->prepare($sql);
$stmt->execute();
$users_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch products
$query = "SELECT * FROM products";
$sqlQuery = $connection->prepare($query);
$sqlQuery->execute();
$products = $sqlQuery->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_POST['user']);
    $notes = htmlspecialchars($_POST['notes']);
    $room_no = htmlspecialchars($_POST['room_no']);
    $orderDate = date('Y-m-d H:i:s');
    $cartItems = json_decode($_POST['cart'], true); // Get cart data from hidden input

    $totalPrice = array_reduce($cartItems, function ($carry, $item) {
        return $carry + $item['price'] * $item['quantity'];
    }, 0);

    // Insert order
    $orderQuery = "INSERT INTO orders (user_id, total_amount, notes, order_date) VALUES (:userId, :total, :notes, :orderDate)";
    $orderStmt = $connection->prepare($orderQuery);
    $orderStmt->bindParam(':userId', $userId);
    $orderStmt->bindParam(':total', $totalPrice);
    $orderStmt->bindParam(':notes', $notes);
    $orderStmt->bindParam(':orderDate', $orderDate);
    $orderStmt->execute();

    $orderId = $connection->lastInsertId(); // Get the last inserted order ID

    // Insert order items
    foreach ($cartItems as $product) {
        $query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:orderId, :productId, :quantity, :price)";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':orderId', $orderId);
        $stmt->bindParam(':productId', $product['id']);
        $stmt->bindParam(':quantity', $product['quantity']);
        $stmt->bindParam(':price', $product['price']);
        $stmt->execute();
    }

    // Redirect or display a success message
    header('Location: home.php?message=Order placed successfully');
    exit();
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
    <title>Manual</title>
    <style>
        .product_card {
            cursor: pointer;
        }

        .cart-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .cart-item input {
            width: 60px;
        }
    </style>
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <form method="post" action="" class="container mt-5 marginTop" id="orderForm">
    <div class="mb-3  pt-5">
                    <label for="user" class="form-label">Select User:</label>
                    <select name="user" id="user" class="form-select" required>
                        <?php foreach ($users_data as $user) : ?>
                            <option value="<?= $user['user_id']; ?>"><?= htmlspecialchars($user['user_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
       <div class="container-fluid pt-5">
            <div class="container">
                <div class="section-title">
                    <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Menu & Pricing</h4>
                    <h4 class="display-4">Competitive Pricing</h4>
                </div>
                <!-- Row for Products -->
                <div class="row my-3">
                    <?php foreach ($products as $product) : ?>
                        <div class="col-lg-2 col-md-4 mb-5 product_card">
                            <div class="row align-items-center" data-product-id="<?php echo ($product['product_id']); ?>">
                                <div class="col-12 Product_img">
                                    <img class="w-100 rounded-circle mb-5 mb-sm-0 p-2" src="../Admin/uploaded_img/<?php echo $product['product_img']; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" height="150">
                                </div>
                                <div class="col-12 mt-3 text-center">
                                    <h5 class="menu-price">Price <?php echo ($product['product_price']); ?> EG</h5>
                                    <h5 class="text-center mb-4 product_name"><?php echo ($product['product_name']); ?></h5>
                                    <button class="addtocart btn btn-primary" data-product-id="<?php echo ($product['product_id']); ?>"><i class="fas fa-cart-plus"></i> Add To Cart</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>


        <div class="mb-3">
            <label for="room_no" class="form-label">Room Number:</label>
            <input type="text" class="form-control" id="room_no" name="room_no" required>
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Additional Notes:</label>
            <textarea class="form-control" id="notes" name="notes"></textarea>
        </div>


        <div class="mb-3">
            <h4>Cart</h4>
            <div id="cart">
                <!-- Cart items will be added dynamically here -->
            </div>
        </div>

        <input type="hidden" name="cart" id="cartData">
        <button type="submit" class="btn btn-primary">Place Order</button>
    </form>

    <footer class="footer bg-dark text-light p-2 mt-4">
        <p class="text-center m-0">&copy; Cafeteria Shop. All Rights Reserved.</p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let cart = [];

            // Add to cart functionality
            document.querySelectorAll('.addtocart').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    const product = document.querySelector(`div[data-product-id="${productId}"]`);
                    const productName = product.querySelector('.product_name').textContent;
                    const productPrice = parseFloat(product.querySelector('.menu-price').textContent.replace('Price ', '').replace(' EG', ''));

                    // Check if the product is already in the cart
                    const existingItem = cart.find(item => item.id === productId);
                    if (existingItem) {
                        existingItem.quantity++;
                    } else {
                        cart.push({
                            id: productId,
                            name: productName,
                            price: productPrice,
                            quantity: 1
                        });
                    }

                    updateCart();
                });
            });

            function updateCart() {
                const cartContainer = document.getElementById('cart');
                cartContainer.innerHTML = '';

                let total = 0;

                cart.forEach(item => {
                    const cartItem = document.createElement('div');
                    cartItem.classList.add('cart-item');
                    cartItem.innerHTML = `
                        <span>${item.name}</span>
                        <input type="number" value="${item.quantity}" min="1" data-product-id="${item.id}">
                        <span>${item.price} EG</span>
                        <button class="btn btn-danger btn-sm" data-product-id="${item.id}">Remove</button>
                    `;
                    cartContainer.appendChild(cartItem);

                    total += item.price * item.quantity;
                });

                // Display total amount
                const totalElem = document.createElement('div');
                totalElem.classList.add('mb-3');
                totalElem.innerHTML = `<h5>Total: ${total} EG</h5>`;
                cartContainer.appendChild(totalElem);

                // Attach event listeners to quantity inputs and remove buttons
                cartContainer.querySelectorAll('input').forEach(input => {
                    input.addEventListener('change', function() {
                        const productId = this.getAttribute('data-product-id');
                        const quantity = parseInt(this.value, 10);
                        const item = cart.find(item => item.id === productId);
                        if (item) {
                            item.quantity = quantity;
                            updateCart();
                        }
                    });
                });

                cartContainer.querySelectorAll('button').forEach(button => {
                    button.addEventListener('click', function() {
                        const productId = this.getAttribute('data-product-id');
                        cart = cart.filter(item => item.id !== productId);
                        updateCart();
                    });
                });

                // Update hidden input with cart data
                document.getElementById('cartData').value = JSON.stringify(cart);
            }
        });
    </script>
</body>

</html>