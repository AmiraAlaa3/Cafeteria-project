<!-- screen 9 -->

<?php 
session_start();
// Include the database connection file

$itemsPerPage = 3;

// Get the current page number from the URL or default to 1 if not set
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Calculate the offset for the SQL query
$offset = ($currentPage - 1) * $itemsPerPage;


$db_server = "localhost";
$db_user = "root";
$db_pass = "Mo@12345"; 
$db_name = "new";
$db_type = "mysql";
$db_port = 3306;
$connection = new PDO("$db_type:host=$db_server;port=$db_port;dbname=$db_name", $db_user, $db_pass);
$totalQuery = 'SELECT * FROM users ';
$stmt = $connection->prepare($totalQuery);
$stmt->execute();
$orders = $stmt->fetch(PDO::FETCH_ASSOC);
$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : (isset($_GET['startDate']) ? $_GET['startDate'] : '');
$endDate = isset($_POST['endDate']) ? $_POST['endDate'] : (isset($_GET['endDate']) ? $_GET['endDate'] : '');
$user_acc =isset($_POST['user-acc']) ? $_POST['user-acc'] : (isset($_GET['user-acc']) ? $_GET['user-acc'] : '');
echo $user_acc;
if($user_acc){
    $totalQuery .= " WHERE (SELECT user_id FROM users WHERE user_name = :name)";
    $totalStmt->bindParam(':name', $user_acc);
}
$totalStmt = $connection->prepare($totalQuery);
if($startDate && $endDate) {
    $totalQuery .= " WHERE order_date BETWEEN :startDate AND :endDate";
}
$totalStmt = $connection->prepare($totalQuery);
if ($startDate && $endDate) {
    $totalStmt->bindParam(':startDate', $startDate);
    $totalStmt->bindParam(':endDate', $endDate);
}
$totalStmt->execute();
$totalItems = $totalStmt->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);

$query = "SELECT * FROM orders";
if ($startDate && $endDate) {
    $query .= " WHERE order_date BETWEEN :startDate AND :endDate";
}
$query .= " LIMIT :limit OFFSET :offset";
$sqlQuery = $connection->prepare($query);
if ($startDate && $endDate) {
    $sqlQuery->bindParam(':startDate', $startDate);
    $sqlQuery->bindParam(':endDate', $endDate);
}
$sqlQuery->bindParam(':limit', $itemsPerPage, PDO::PARAM_INT);
$sqlQuery->bindParam(':offset', $offset, PDO::PARAM_INT);
$sqlQuery->execute();
$datas = $sqlQuery->fetchAll(PDO::FETCH_ASSOC);

// Get any message from the URL parameters
$message = isset($_GET['message']) ? $_GET['message'] : '';

 ?>
<!DOCTYPE html>
<html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checks</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
 </head>
 <body>
   <?php include '../includes/header.php';?>
   <main class="container mt-5 marginTop">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="display-5 m-auto mt-5">Checks</h3>
        </div>
    </main>
    <form method="POST" action="orders.php" class="d-flex align-items-center mb-3">
                <label class="me-1">Start Date:</label>
                <input type="date" id="startDate" name="startDate" class="placeholder-label me-5" value="<?php echo ($startDate); ?>">
                <br><br>
                
                <label class="ms-5 me-1">End Date:</label>
                <input type="date" id="endDate" name="endDate" class="placeholder-label me-5" value="<?php echo ($endDate); ?>">
                <br><br>
                <select class="form-select" name="user-acc" aria-label="Default select example" style="width:40%; height:45px; padding-left:8px;font-size:16px;">
                    <?php
                    foreach ($orders as $order) {
                        echo "<option value='$order'>$order</option>";
                    }
                    ?>
                    </select>
                <button type="submit" class="btn btn-view">View</button>
            </form>
    <div class="container mt-5 namess">
       <table class="table w-100 table-striped">
       <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <?php foreach($datas as $data){
                echo' <td> $order["order_date"]; </td>' ;
                echo '<td>  $order["total_amount"];</td>';
            } 
            ?>
            </tr>
        </tbody>
       </table>
    </div>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>   
 </body>
 </html>