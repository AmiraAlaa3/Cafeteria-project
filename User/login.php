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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="f-page mt-5 d-flex align-items-center justify-content-center flex-column g-4">
        <p class="title m-auto" style="font-size:45px;">Cafeteria</p>
        <form class="container" method="POST">
            <div class="inputs d-flex justify-content-between m-auto align-items-center mt-4 mb-4" style="width:70%;">
                <label style="font-size: 28px;" for="mail">Email:</label>
                <input style="width:75%;border:0; padding-left: 10px; height: 40px; font-size: 16px;" class="border-bottom" type="email" name="mail" id="mailInput" placeholder="Enter your Email" required>
            </div>
            <div class="inputs d-flex justify-content-between align-items-center m-auto mt-4 mb-4" style="width:70%;">
                <label style="font-size: 28px;" for="pass">Password:</label>
                <input style="width:75%;border:0; padding-left: 10px; height: 40px; font-size: 16px;" class="border-bottom" type="password" name="pass" id="passInput" placeholder="Enter your Password" required>
            </div>
            <button style="border:0;border-radius:8px; width:90px; height:45px; margin: 50px 0 40px 46%; position:relative" type="submit">Login</button>
            <?php  
            if (isset($error)) {
                echo '<p class="text-danger">' . $error . '</p>';
            }
            ?>
        </form>
        <a href="./reset_password.php" class="m-auto" style="font-size:18px;">Forgot password?</a>
    </div>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
