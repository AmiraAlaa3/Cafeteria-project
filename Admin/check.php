<!-- screen 9 -->

<?php 
require('../includes/db2.php');
$sql = "SELECT id, name ,amount FROM users";
$result = $connection->query($sql);

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
    <div class="input d-flex justify-content-around container mt-5 g-4 flex-wrap "> 
        <input type="date" id="datefrom" name="datefrom" class="mb-4 form-control" placeholder="Date From" style="width:40%; height:45px; padding-left:8px;font-size:16px;">
        <input type="date" id="dateto" name="dateto" class="mb-4 form-control" placeholder="Date To" style="width:40%; height:45px; padding-left:8px;font-size:16px;">
        <select class="form-select" aria-label="Default select example" style="width:40%; height:45px; padding-left:8px;font-size:16px;">
            <option style="width:35%; height:45px; padding-left:8px;" selected>Open this select menu</option>
            <option value="1">One</option>
            <option value="2">Two</option>
            <option value="3">Three</option>
        </select>
        <input type="submit" name="submit" value="Filter Orders">
        <?php 
        if ($result->num_rows > 0) {
            echo `<table class="table w-100 table-striped">
            <thead>
            <tr>
            <th scope="col">Name</th>
            <th scope="col">Total Amount</th>
            </tr>
            </thead`;
            while ($row1 = $result->fetch_assoc()) {
                echo "<tr>
                <td>" . $row1['name'] . "</td>
                <td>" . $row1['total'] . "</td>
              </tr>";
    }
    echo "</table>";
            }
        
        ?>
        <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
        $user_name = $_POST['user'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $query = "SELECT order_num, total FROM orders WHERE user_id = (SELECT user_id FROM users WHERE user_name = $user_name ?";
        if (!empty($start_date) && !empty($end_date)) {
            $query .= " AND order_date BETWEEN ? AND ?";
        }
        $stmt = $conn->prepare($query);
        if (!empty($start_date) && !empty($end_date)) {
            $stmt->bind_param("iss", $user_id, $start_date, $end_date);
        } else {
            $stmt->bind_param("i", $user_id);
        }

        // Execute query
        $stmt->execute();
        $result2 = $stmt->get_result();

        // Check results
        if ($result2->num_rows > 0) {
            echo "<table border='1'>
                    <tr>
                        <th>Order Number</th>
                        <th>Total</th>
                    </tr>";
            while ($row = $result2->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['order_num'] . "</td>
                        <td>" . $row['total'] . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "No orders found.";
        }

        // Close statement
        $stmt->close();
    }
    $conn->close();
    ?>
    </div>
    <div class="container mt-5 name">
       <table class="table w-100 table-striped">
       <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">1</th>
                <td>Mark</td>
            </tr>
            <tr>
                <th scope="row">2</th>
                <td>Jacob</td>
            </tr>
        </tbody>
       </table>
    </div>
    <div class="container mt-5 date">
       <table class="table w-100 table-striped">
       <thead>
            <tr>
                <th scope="col">Order Date</th>
                <th scope="col">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">1</th>
                <td>Mark</td>
            </tr>
            <tr>
                <th scope="row">2</th>
                <td>Jacob</td>
            </tr>
        </tbody>
       </table>
    </div>





 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>   
 </body>
 </html>