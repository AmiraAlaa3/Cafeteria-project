<?php 
session_start();
require('../includes/db2.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['mail'];
    $newPassword = $_POST['pass'];
    $confirmPassword = $_POST['passed'];
   
    if ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        $query = 'SELECT * FROM users WHERE email = :email';
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $query = 'UPDATE users SET password = :newPassword WHERE email = :email';
            $stmt = $connection->prepare($query);
            $stmt->bindParam(':newPassword', $newPassword); 
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            $success = "Password has been reset successfully.";
        } else {
            $error = "Email not found.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<div class="f-page mt-5 d-flex align-items-center justify-content-center flex-column g-4">
    <p class="title m-auto" style="font-size:45px;">Forgot Password</p>
    <form class="container" method="POST">
        <div class="inputs d-flex justify-content-between m-auto align-items-center mt-4 mb-4" style="width:70%;">
            <label style="font-size: 28px;" for="mail">Email:</label>
            <input style="width:70%;border:0; padding-left: 10px; height: 40px; font-size: 16px;" class="border-bottom" type="text" name="mail" id="mailInput" placeholder="Enter your Email" required>
        </div>
        <div class="inputs d-flex justify-content-between align-items-center m-auto mt-4 mb-4" style="width:70%;">
            <label style="font-size: 28px;" for="pass">New Password:</label>
            <input style="width:70%;border:0; padding-left: 10px; height: 40px; font-size: 16px;" class="border-bottom" type="password" name="pass" id="passInput" placeholder="Enter your New Password" required>
        </div>
        <div class="inputs d-flex justify-content-between align-items-center m-auto mt-4 mb-4" style="width:70%;">
            <label style="font-size: 28px;" for="passed">Confirm Password:</label>
            <input style="width:70%;border:0; padding-left: 10px; height: 40px; font-size: 16px;" class="border-bottom" type="password" name="passed" id="passConfirmInput" placeholder="Confirm your New Password" required>
        </div>
        <?php  
        if (isset($error)) {
            echo '<p class="text-danger">' . $error . '</p>';
        } elseif (isset($success)) {
            echo '<p class="text-success">' . $success . '</p>';
            echo '<script>setTimeout(function() { location.href = "login.php"; }, 3000);</script>';
        }
        ?>
        <button style="border:0;border-radius:8px; width:90px; height:45px; margin: 50px 0 40px 46%;" id="submit-btn" type="submit">Submit</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
