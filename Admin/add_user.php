<!-- screen 7 -->
<?php
$message = isset($_GET['message']) ? $_GET['message'] : '';
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
    <title>Admin: Add User</title>
</head>

<body>
    <?php include '../includes/header.php';?>
    <!-- main  -->
    <main class="container mt-5 marginTop">
        <h3 class="display-5 mb-3">Add User</h3>
        <?php if ($message): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
    
        <form class="container" action="process_add.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input class="form-control" name="Name" id="name" type="text" placeholder="enter name of user">
            </div>

            <div class="mb-3">
                <label for="Email1" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="Email1" placeholder="enter  email of user" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                <label for="Password1" class="form-label">Password</label>
                <input type="password" name="passsword" class="form-control" id="Password1" placeholder="enter user password">
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">confirm password</label>
                <input class="form-control" name="Confirm_Password" id="confirm_password" type="password" placeholder="enter password of user">
            </div>
            <div class="mb-3">
                <label for="room_no" class="form-label">Room No</label>
                <input class="form-control" name="Room_No" id="room_no" type="number" placeholder="enter Room number">
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Profile Picture</label>
                <input class="form-control" type="file" name="Image" id="image">
            </div>

            <button type="submit" name="add_user" class="btn btn-primary">Submit</button>
        </form>
    </main>
    <footer class="footer bg-dark text-light p-2">
        <p class="text-center m-0">&copy; Cafeteria Shop. All Rights Reserved.</p>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>