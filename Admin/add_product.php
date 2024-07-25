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
    <title>Admin: Add Products</title>
</head>

<body>
    <nav class='navbar navbar-expand-lg bg-dark p-lg-3 fixed-top' data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Cafeteria</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="all_product.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="all_user.php">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Manual Order</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Checks</a>
                    </li>
                </ul>
                <ul class="navbar-nav me-auto me-lg-0 mb-2 mb-lg-0">
                    <li class="nav-item d-flex align-items-center me-2 mb-lg-0 mb-sm-2">
                        <img src="../images/admin.png" alt="admin img" width="40" height="40" class="rounded-circle d-inline-block align-text-top">
                        <span class="text-light px-2">Admin</span>
                    </li>
                    <li class="nav-item">
                        <a class="btn  btn-outline-danger" href="#">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- main  -->
    <main class="container mt-5 pageHeader">
       <h3 class="display-5 mb-5">Add Products</h3>
    </main>
    <footer class="footer bg-dark text-light p-2">
        <p class="text-center m-0">&copy; Cafeteria Shop. All Rights Reserved.</p>
    </footer>

</body>
</html>