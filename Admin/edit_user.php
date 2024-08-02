<?php
session_start();
require('../includes/db2.php');

// Check if the user ID is set in the query string
if (!isset($_GET['id'])) {
    header('Location: all_user.php?message=User ID is missing.');
    exit();
}

// Retrieve the user ID from the query string
$id = intval($_GET['id']);

// Fetch the user details from the database
$query = "SELECT * FROM users WHERE user_id = :id";
$sqlQuery = $connection->prepare($query);
$sqlQuery->bindParam(':id', $id, PDO::PARAM_INT);
$sqlQuery->execute();
$userData = $sqlQuery->fetch(PDO::FETCH_ASSOC);

// Check if the user exists
if (!$userData) {
    header('Location: all_user.php?message=User not found.');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $user_name = $_POST['user_name'];
    $room_no = $_POST['room_no'];

    // Handle image upload
    $pic = $userData['pic']; // Default to the current image
    if (!empty($_FILES['pic']['name'])) {
        $uploadDir = 'uploaded_img/';
        $uploadFile = $uploadDir . basename($_FILES['pic']['name']);

        // Check if the file is an image
        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES['pic']['tmp_name']);
        if ($check !== false && in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES['pic']['tmp_name'], $uploadFile)) {
                $pic = $_FILES['pic']['name']; // Update the image name
            }
        }
    }

    // Update user details in the database
    $updateQuery = "UPDATE users SET user_name = :user_name, room_no = :room_no, pic = :pic WHERE user_id = :id";
    $updateStmt = $connection->prepare($updateQuery);
    $updateStmt->bindParam(':user_name', $user_name);
    $updateStmt->bindParam(':room_no', $room_no);
    $updateStmt->bindParam(':pic', $pic);
    $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
    $updateStmt->execute();

    // Redirect to the users page with a success message
    header('Location: all_user.php?message=User updated successfully.');
    exit();
}
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
    <title>Edit User</title>
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <main class="container mt-5 marginTop">
        <h3 class="display-5 mb-3">Edit User</h3>

        <form  class="container shadow-lg p-5" action="edit_user.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="user_name" class="form-label">Name</label>
                <input type="text" class="form-control" id="user_name" name="user_name" 
                value="<?php echo htmlspecialchars($userData['user_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="room_no" class="form-label">Room</label>
                <input type="text" class="form-control" id="room_no" name="room_no"
                 value="<?php echo htmlspecialchars($userData['room_no']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="pic" class="form-label">Image</label>
                <input type="file" class="form-control" id="pic" name="pic">
                <img src="uploaded_img/<?php echo htmlspecialchars($userData['pic']); ?>" alt="user image" class="img-thumbnail mt-2" style="width: 80px;">
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="users.php" class="btn btn-secondary">Cancel</a>
        </form>
    </main>

    <footer class="footer bg-dark text-light p-2 mt-4">
        <p class="text-center m-0">&copy; Cafeteria Shop. All Rights Reserved.</p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
