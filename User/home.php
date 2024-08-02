<!-- screen 2 -->
<?php
session_start();
require('../includes/db2.php');

if (isset($_SESSION['user_email'])) {
   $user_email = $_SESSION['user_email'];
   $getUser = "SELECT user_name, pic, user_id FROM users WHERE email = :email";
   $sqlGetUser = $connection->prepare($getUser);
   $sqlGetUser->bindParam(':email', $user_email);
   $sqlGetUser->execute();
   $user = $sqlGetUser->fetch(PDO::FETCH_ASSOC);
   if ($user) {
      $user_name = $user['user_name'];
      $user_image = "../Admin/uploaded_img/" . $user['pic'];
      $user_id = 2; // $user['user_id'];
   } else {
      $user_name = "Guest";
      $user_image = "../Admin/uploaded_img/admin.png";
   }
} else {
   $user_name = "Guest";
   $user_image = "../Admin/uploaded_img/admin.png";
}
$user_id = 3;
// Fetch all orders for the current user
$sqlOrder = "SELECT orders.order_id, products.product_name, products.product_img, order_items.quantity, order_items.price 
             FROM orders
             JOIN order_items ON order_items.order_id = orders.order_id
             JOIN products ON order_items.product_id = products.product_id
             WHERE orders.user_id = :user_id";

$ResultOfOrder = $connection->prepare($sqlOrder);
$ResultOfOrder->bindParam(':user_id', $user_id);
$ResultOfOrder->execute();
$lastOrders = $ResultOfOrder->fetchAll(PDO::FETCH_ASSOC);

// Fetch all products (if needed for some other purpose)
$query = "SELECT * FROM products";
$sqlQuery = $connection->prepare($query);
$sqlQuery->execute();
$products = $sqlQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- Favicon -->
   <link href="img/favicon.ico" rel="icon">
   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <!-- font -->
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Rye&display=swap" rel="stylesheet">
   <!-- bootstrap -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <!-- css -->
   <link rel="stylesheet" href="../CSS/style2.css">
   <title>Home</title>
</head>

<body>
   <!-- Navbar Start -->
   <div class="container-fluid p-0 nav-bar">
      <nav class="navbar navbar-expand-lg bg-none navbar-dark p-3">
         <div class="container-fluid">
            <a href="index.html" class="navbar-brand px-lg-4 m-0">
               <h1 class="m-0 display-5 text-white">Cafeteria</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
               <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li class="nav-item">
                     <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="order_history.php">My Order</a>
                  </li>
               </ul>
               <ul class="navbar-nav me-auto me-lg-0 mb-2 mb-lg-0">
                  <li class="nav-item d-flex align-items-center me-2 mb-lg-0 mb-sm-2">
                     <img src="<?php echo $user_image; ?>" alt="<?php echo $user_name; ?>" width="40" height="40" class="rounded-circle d-inline-block align-text-top">
                     <span class="text-light px-2"><?php echo $user_name; ?></span>
                  </li>
                  <li class="nav-item">
                     <a class="btn  btn-danger" href="#">Logout</a>
                  </li>
                  <li class="nav-item">
                     <a class="ms-4 cart" id="show_cart" href="#"><i class="fa-solid fa-cart-shopping"></i></a>
                  </li>
               </ul>
            </div>
         </div>
      </nav>
   </div>
   <!-- Navbar End -->

   <!-- Carousel Start -->
   <div class="container-fluid p-0 mb-5">
      <div id="carouselExampleCaptions" class="carousel slide overlay-bottom" data-bs-ride="carousel">
         <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
         </div>
         <div class="carousel-inner">
            <div class="carousel-item active"> <!-- data-bs-interval="1000" -->
               <img src="../images/carousel-1.jpg" class="d-block w-100" alt="">
               <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                  <h2 class="text-primary font-weight-medium m-0">We Have Been Serving</h2>
                  <h1 class="display-1 text-white m-0">COFFEE</h1>
                  <h2 class="text-white m-0">* SINCE 1950 *</h2>
               </div>
            </div>

            <div class="carousel-item">
               <img src="../images/carousel-2.jpg" class="d-block w-100" alt="" />
               <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                  <h2 class="text-primary font-weight-medium m-0">We Have Been Serving</h2>
                  <h1 class="display-1 text-white m-0">COFFEE</h1>
                  <h2 class="text-white m-0">* SINCE 1950 *</h2>
               </div>
            </div>
         </div>

         <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
         </button>

         <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
         </button>
      </div>
   </div>
   <!-- Carousel End -->
   <!-- last order -->
   <div class="container-fluid pt-5">
      <div class="container">
         <div class="section-title">
            <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Last your orders</h4>
            <h4 class="display-4">Order Summary</h4>
         </div>
         <!-- Row for orders -->

         <?php if (!empty($lastOrders)) : ?>
            <div class="row my-3">
               <?php foreach ($lastOrders as $order) : ?>
                  <div class="col-lg-2 col-md-4 mb-5 product_card">
                     <div class="row align-items-center">
                        <div class="col-12 Product_img">
                           <img class="w-100 rounded-circle mb-5 mb-sm-0 p-2" src="../Admin/uploaded_img/<?php echo htmlspecialchars($order['product_img']); ?>" alt="<?php echo htmlspecialchars($order['product_name']); ?>" height="150">
                           <h5 class="menu-price"><?php echo ($order['price']); ?></h5>
                        </div>
                        <div class="col-12 mt-3 text-center">
                           <h5 class="text-center mb-4 product_name"><?php echo ($order['product_name']); ?></h5>
                        </div>
                     </div>
                  </div>

               <?php endforeach; ?>
            </div>
         <?php else : ?>
            <p>No orders found for this user.</p>
         <?php endif; ?>

      </div>
   </div>
   <!-- menu start -->
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
                        <h5 class="menu-price"><?php echo ($product['product_price']); ?></h5>
                     </div>
                     <div class="col-12 mt-3 text-center">
                        <h5 class="text-center mb-4 product_name"><?php echo ($product['product_name']); ?></h5>
                        <button class="addtocart" data-product-id="<?php echo ($product['product_id']); ?>"><i class="fas fa-cart-plus"></i> Add To Cart</button>
                     </div>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
      </div>
   </div>
   <!-- menu end -->

   <!-- cart -->
   <div class="cart-section">
      <div class="d-flex justify-content-between align-items-center my-4 cart-header">
         <h3>SHOPPING CART</h3>
         <div class="closeCart">
            <i class="fa-solid fa-x" id="closeCart"></i>
         </div>
      </div>
      <div class="cart_products mb-3 h-100">
         <!-- add cart products in js -->
      </div>
      <div class="cart-info">
         <div class="section-notes mb-3">
            <h6 class="mb-1">Special Notes</h6>
            <textarea name="notes" id="notes" cols="30" rows="2" class="form-control"></textarea>
         </div>
         <div class="select-room d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-1 me-5">Room</h6>
            <select name="room" id="room" class="form-control">
               <option value="1">1</option>
               <option value="2">2</option>
               <option value="3">3</option>
               <option value="2">4</option>
               <option value="3">5</option>
            </select>
         </div>
      </div>
      <div class="cart_total d-flex justify-content-between align-items-center mb-3">
         <h5 class="fs-4">Total:</h5>
         <span id="total_price">0</span>
      </div>
      <div class="btn_control d-flex justify-content-end">
         <button class="checkout">Confirm</button>
      </div>
   </div>



   <!-- Footer Start -->
   <div class="container-fluid footer text-white mt-5 pt-5 px-0 position-relative overlay-top">
      <div class="row mx-0 pt-5 px-sm-3 px-lg-5 mt-4">
         <div class="col-lg-3 col-md-6 mb-5">
            <h4 class="text-white text-uppercase mb-4" style="letter-spacing: 3px;">Get In Touch</h4>
            <p><i class="fa fa-map-marker-alt me-2"></i>123 Street, New York, USA</p>
            <p><i class="fa fa-phone-alt me-2"></i>+20 01005729533</p>
            <p class="m-0"><i class="fa fa-envelope me-2"></i>ameraalaa641@gmail.com</p>
         </div>
         <div class="col-lg-3 col-md-6 mb-5">
            <h4 class="text-white text-uppercase mb-4" style="letter-spacing: 3px;">Follow Us</h4>
            <p>Amet elitr vero magna sed ipsum sit kasd sea elitr lorem rebum</p>
            <div class="d-flex justify-content-start">
               <a class="btn btn-lg btn-outline-light btn-lg-square me-2" href="#"><i class="fab fa-twitter"></i></a>
               <a class="btn btn-lg btn-outline-light btn-lg-square me-2" href="#"><i class="fab fa-facebook-f"></i></a>
               <a class="btn btn-lg btn-outline-light btn-lg-square me-2" href="#"><i class="fab fa-linkedin-in"></i></a>
               <a class="btn btn-lg btn-outline-light btn-lg-square" href="#"><i class="fab fa-instagram"></i></a>
            </div>
         </div>
         <div class="col-lg-3 col-md-6 mb-5">
            <h4 class="text-white text-uppercase mb-4" style="letter-spacing: 3px;">Open Hours</h4>
            <div>
               <h6 class="text-white text-uppercase">Monday - Friday</h6>
               <p>8.00 AM - 8.00 PM</p>
               <h6 class="text-white text-uppercase">Saturday - Sunday</h6>
               <p>2.00 PM - 8.00 PM</p>
            </div>
         </div>
         <div class="col-lg-3 col-md-6 mb-5">
            <h4 class="text-white text-uppercase mb-4" style="letter-spacing: 3px;">Newsletter</h4>
            <p>Amet elitr vero magna sed ipsum sit kasd sea elitr lorem rebum</p>
            <div class="w-100">
               <div class="input-group">
                  <input type="text" class="form-control border-light" placeholder="Your Email">
                  <div class="input-group-append">
                     <button class="send_email font-weight-bold px-3">Sign Up</button>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="container-fluid text-center text-white border-top mt-4 py-4 px-sm-3 px-md-5" style="border-color: rgba(256, 256, 256, .1) !important;">
         <p class="mb-2 text-white">Copyright &copy; All Rights Reserved.</p>
         <p class="m-0 text-white">Designed by <a class="font-weight-bold team" href="">ANMHM</a></p>
      </div>
   </div>
   <!-- Footer End -->
   <!-- Back to Top -->

   <button id="scrollBtn"><i class="fa fa-angle-up" id="btn-up"></i></button>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js" integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script src="../JS/main.js"></script>
</body>

</html>