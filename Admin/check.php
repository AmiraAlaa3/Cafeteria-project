<?php
// Start a session
session_start();
// Include the database connection file
require('../includes/db2.php');
 
//user login
if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email'];
    $getUser = "SELECT user_name, pic, user_id FROM users WHERE email = :email";
    $sqlGetUser = $connection->prepare($getUser);
    $sqlGetUser->bindParam(':email', $user_email);
    $sqlGetUser->execute();
    $user = $sqlGetUser->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $user_name = $user['user_name'];
        $user_image = !empty($user['pic']) ? "../Admin/uploaded_img/" . $user['pic'] : "../Admin/uploaded_img/default.png";
        $user_id = $user['user_id'];
    } else {
        $user_name = "Guest";
        $user_image = "../Admin/uploaded_img/default.png";
    }
} else {
    $user_name = "Guest";
    $user_image = "../Admin/uploaded_img/default.png";
} 

// Define the number of items per page for pagination
$itemsPerPage = 3;

// Get the current page number from the URL or default to 1 if not set
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Calculate the offset for the SQL query
$offset = ($currentPage - 1) * $itemsPerPage;

// Get the start and end dates from POST or GET request
$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : (isset($_GET['startDate']) ? $_GET['startDate'] : '');
$endDate = isset($_POST['endDate']) ? $_POST['endDate'] : (isset($_GET['endDate']) ? $_GET['endDate'] : '');
$username = isset($_POST['username']) ? $_POST['username'] : (isset($_GET['username']) ? $_GET['username'] : '');

$userId = null;
if ($username) {
    $getUserId = "SELECT user_id FROM users WHERE user_name = :username";
    $sqlGetUserId = $connection->prepare($getUserId);
    $sqlGetUserId->bindParam(':username', $username);
    $sqlGetUserId->execute();
    $userResult = $sqlGetUserId->fetch(PDO::FETCH_ASSOC);
    $userId = $userResult ? $userResult['user_id'] : null;
}


// Construct the SQL query to count total items for pagination
$totalQuery = "SELECT COUNT(*) FROM orders";
if ($startDate && $endDate) {
    $totalQuery .= " WHERE order_date BETWEEN :startDate AND :endDate";
    if ($userId) {
        $totalQuery .= " AND user_id = :userId";
    }
} elseif ($userId) {
    $totalQuery .= " WHERE user_id = :userId";
}
$totalStmt = $connection->prepare($totalQuery);
if ($startDate && $endDate) {
    $totalStmt->bindParam(':startDate', $startDate);
    $totalStmt->bindParam(':endDate', $endDate);
}
if ($userId) {
    $totalStmt->bindParam(':userId', $userId);
}
$totalStmt->execute();
$totalItems = $totalStmt->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);

// Construct the SQL query to fetch orders based on date range and pagination
$query = "SELECT * FROM orders";
if ($startDate && $endDate) {
    $query .= " WHERE order_date BETWEEN :startDate AND :endDate";
    if ($userId) {
        $query .= " AND user_id = :userId";
    }
} elseif ($userId) {
    $query .= " WHERE user_id = :userId";
}

$query .= " LIMIT :limit OFFSET :offset";
$sqlQuery = $connection->prepare($query);
if ($startDate && $endDate) {
    $sqlQuery->bindParam(':startDate', $startDate);
    $sqlQuery->bindParam(':endDate', $endDate);
}
if ($userId) {
    $sqlQuery->bindParam(':userId', $userId);
}
$sqlQuery->bindParam(':limit', $itemsPerPage, PDO::PARAM_INT);
$sqlQuery->bindParam(':offset', $offset, PDO::PARAM_INT);
$sqlQuery->execute();
$orders = $sqlQuery->fetchAll(PDO::FETCH_ASSOC);

// Get any message from the URL parameters
$message = isset($_GET['message']) ? $_GET['message'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Load fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Rye&display=swap" rel="stylesheet">
    <!-- Load Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Load custom CSS -->
    <link rel="stylesheet" href="../CSS/style2.css">
    <title>My Orders</title>
</head>

<body>
      <!-- Navbar Start -->
      <?php include '../includes/header.php';?>
   <!-- Navbar End -->

    <!-- Main content -->
    <main class="container my-5 marginTop">
        <div class=" mb-3 ">
            <div class="title w-100 d-flex justify-content-center  g-4 align-items-center mb-3 flex-column">
                <h3 class="display-5 m-auto header-color">Checks</h3>
            </div>
            <!-- Date selection form -->
            <form method="POST"  class="d-flex align-items-center mb-3 flex-wrap flex-column">
            <select class="form-select mb-3" name="user-acc" aria-label="Default select example" style="width:70%; height:45px; padding-left:8px;font-size:16px;">
            <option value="" selected>Choose user</option>
                    <?php
                    $totalQuery = 'SELECT * FROM users ';
                    $stmt = $connection->prepare($totalQuery);
                    $stmt->execute();
                    $ord = $stmt->fetch(PDO::FETCH_ASSOC);
                    foreach ($ord as $orde) {
                        echo "<option value='$orde'>$orde</option>";
                    }
                    ?>
                    </select>
                    <div class="my-5 w-100 ">

                        <label class="me-1 ">Start Date:</label>
                        <input type="date" style="width:30%;" id="startDate" name="startDate" class="placeholder-label me-5" value="<?php echo ($startDate); ?>">
                        <label class="ms-5 me-1">End Date:</label>
                        <input type="date" id="endDate"  style="width:30%;" name="endDate" class="placeholder-label me-5" value="<?php echo ($endDate); ?>">
                    </div>
                
                <button type="submit" class="btn btn-view my-4">View</button>
            </form>
        </div>
        
        <!-- Table to display orders -->
        <div class="table-responsive-lg">
            <table class="table my-4">
                <thead>
                    <tr class="table-dark table-color">
                        <th>Order Date</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through orders and display each order in a table row -->
                    <?php foreach ($orders as $order) { ?>
                        <tr>
                            <td><?php echo $order['order_date']; ?></td>
                            <td><?php echo $order['total_amount']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
      
        <!-- Pagination navigation -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <!-- Previous page link -->
                <?php if ($currentPage > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>&startDate=<?php echo urlencode($startDate); ?>&endDate=<?php echo urlencode($endDate); ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Page number links -->
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo $i == $currentPage ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&startDate=<?php echo urlencode($startDate); ?>&endDate=<?php echo urlencode($endDate); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Next page link -->
                <?php if ($currentPage < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>&startDate=<?php echo urlencode($startDate); ?>&endDate=<?php echo urlencode($endDate); ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </main>

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

    <!-- Load Bootstrap JS bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>


