<?php
session_start();
require('../includes/db2.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['mail'];
    $newPassword = $_POST['passwd'];
    $confirmPassword = $_POST['Confirm_passed'];

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
    <meta charset="utf-8">
    <title>Reset Password</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free Website Template" name="keywords">
    <meta content="Free Website Template" name="description">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../CSS/style.min.css" rel="stylesheet">
</head>

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
                            <h1 class="text-white mb-4 mt-5">Reset Password</h1>
                            <form class="mb-5" method="POST">
                                <div class="form-group">
                                    <input type="email" class="form-control bg-transparent border-primary p-4" name="mail" placeholder="Email" required="required" />
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control bg-transparent border-primary p-4" placeholder="New Password" name="passwd" required="required" />
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control bg-transparent border-primary p-4" placeholder="Confirm Password" name="Confirm_passed" required="required" />
                                </div>
                                <?php
                                if (isset($error)) {
                                    echo '<p class="text-danger">' . $error . '</p>';
                                } elseif (isset($success)) {
                                    echo '<p class="text-success">' . $success . '</p>';
                                    echo '<script>setTimeout(function() { location.href = "login.php"; }, 3000);</script>';
                                }
                                ?>
                                <div>
                                    <button class="btn btn-primary btn-block font-weight-bold py-3" type="submit">Reset Password</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</body>

</html>