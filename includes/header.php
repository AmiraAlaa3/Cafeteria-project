<?php
session_start();
require('../includes/db2.php');

if (isset($_SESSION['user_email'])) {
   $user_email = $_SESSION['user_email'];
   $getUser = "select user_name, pic, user_id FROM users where email = :email";
   $sqlGetUser = $connection->prepare($getUser);
   $sqlGetUser->bindParam(':email', $user_email);
   $sqlGetUser->execute();
   $user = $sqlGetUser->fetch(PDO::FETCH_ASSOC);
   if ($user) {
      $user_name = $user['user_name'];
      $user_image = "uploaded_img/" . $user['pic'];
      $user_id = $user['user_id'];
   } else {
      $user_name = "Guest";
      $user_image = "uploaded_img/admin.png";
   }
} else {
   $user_name = "Guest";
   $user_image = "uploaded_img/admin.png";
}
?>
<nav class='navbar navbar-expand-lg bg-dark p-lg-3 fixed-top' data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Cafeteria</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="all_product.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="all_user.php">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Manual_order.php">Manual Order</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="check.php">Checks</a>
                    </li>
                </ul>
                <ul class="navbar-nav me-auto me-lg-0 mb-2 mb-lg-0">
                <li class="nav-item d-flex align-items-center me-2 mb-lg-0 mb-sm-2">
                     <img src="<?php echo $user_image; ?>" alt="<?php echo $user_name; ?>" width="40" height="40" class="rounded-circle d-inline-block align-text-top">
                     <span class="text-light px-2"><?php echo $user_name; ?></span>
                  </li>
                  <li class="nav-item">
                     <a class="btn  btn-danger" href="../User/logout.php">Logout</a>
                  </li>
                </ul>
            </div>
        </div>
</nav>
