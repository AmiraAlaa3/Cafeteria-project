<?php
session_start();
require('../includes/db2.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['mail'];
    $password = $_POST['pass'];

    
    $query = 'SELECT * FROM users WHERE email = :email';
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

   
    if ($user && $password == $user['password']) {
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['user_name'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header('Location: ../Admin/home.php'); 
        } else {
            header('Location: home.php'); 
        }
        exit();
    } else {
        $error = 'Your username or password is invalid.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>test login</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free Website Template" name="keywords">
    <meta content="Free Website Template" name="description">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../CSS/style.min.css" rel="stylesheet"></head>
<body>
    <div class="container-fluid my-5">
        <div class="container">
            <div class="reservation position-relative overlay-top overlay-bottom">
                <div class="row align-items-center">
                    <div class="col-lg-6 my-5 my-lg-0">
                        <div class="p-5">
                            <div class="mb-4">
                                <h1 class="display-3 text-primary">Welcome to Cafeteria</h1>
                            </div>
                            <p class="text-white">We’re delighted to see you here. <br> Please log in to savor your favorite brews, access special offers,
                                 and more. New here? Sign up to join our café community and start enjoying all the perks!</p>
                            <ul class="list-inline text-white m-0">
                                <li class="py-2"><i class="fa fa-check text-primary mr-3"></i>Enjoy our selection of rare coffee blends</li>
                                <li class="py-2"><i class="fa fa-check text-primary mr-3"></i>Relax in our inviting and cozy café environment</li>
                                <li class="py-2"><i class="fa fa-check text-primary mr-3"></i>You can track your order on our website</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="text-center p-5" style="background: rgba(51, 33, 29, .8);">
                            <h1 class="text-white mb-4 mt-5">Log in</h1>
                            <form class="mb-5">
                                <div class="form-group">
                                    <input type="email" class="form-control bg-transparent border-primary p-4" placeholder="Email"
                                        required="required" />
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control bg-transparent border-primary p-4" placeholder="password"
                                        required="required" />
                                </div>
                                <div>
                                    <button class="btn btn-primary btn-block font-weight-bold py-3" type="submit">log in</button>
                                </div>
                                <a href="reset_password.php" class="m-auto" style="font-size:18px;">Forgot password?</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     
</body>
</html>
