<?php  

require('../includes/db.php');

if (isset($_POST['add_user'])) {
    $name = $_POST['Name'];
    $email = $_POST['email'];
    $password = $_POST['passsword'];
    $confirm_password = $_POST['Confirm_Password'];
    $room_number = $_POST['Room_No'];
    
    // Define regex patterns
    $patternPassword = '/^[a-zA-Z0-9]{3,8}$/'; 
    $patternName = '/^[a-zA-Z ]{3,}$/';

    if (!preg_match($patternName, $name)) {
        header("location:add_user.php?message=Your name should be greater than 3 characters.");
        exit;
    }
    if (!preg_match($patternPassword, $password)) {
        header("location:add_user.php?message=Your password should be between 3 and 8 alphanumeric characters.");
        exit;
    }
    if ($password !== $confirm_password) {
        header("location:add_user.php?message=Your confirm password should be equal to the password.");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("location:add_user.php?message=Your email is not valid.");
        exit;
    }

    $checkEmail = "select * from users where email = :email";
    $sqlCheckEmail = $connection->prepare($checkEmail);
    $sqlCheckEmail->bindParam(':email', $email);
    $sqlCheckEmail->execute();
    $result = $sqlCheckEmail->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        header("location:add_user.php?message=This email already exists. Choose another email.");
        exit;
    } else {
        if (isset($_FILES["Image"]) && $_FILES["Image"]["error"] == 0) {
            $uploadDir = 'uploaded_img/';
            $fileName = $_FILES["Image"]["name"];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Generate a unique file name to avoid overwriting existing files
            $uniqueFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $targetFile = $uploadDir . $uniqueFileName;

            $check = getimagesize($_FILES["Image"]["tmp_name"]);
            if ($check === false) {
                header("location:add_user.php?message=File is not an image.");
                exit;
            }

            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($fileExtension, $allowedTypes)) {
                header("location:add_user.php?message=Only JPG, JPEG, PNG & GIF files are allowed.");
                exit;
            }

            // Check file size (max 5MB)
            if ($_FILES["Image"]["size"] > 5000000) {
                header("location:add_user.php?message=Sorry, your file is too large.");
                exit;
            }

            if (move_uploaded_file($_FILES["Image"]["tmp_name"], $targetFile)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $query = "insert into users (user_name, email, password, pic, room_no) VALUES (:name, :email, :password, :pic, :room_no)";
                $sqlQuery = $connection->prepare($query);

                $sqlQuery->bindParam(':name', $name);
                $sqlQuery->bindParam(':email', $email);
                $sqlQuery->bindParam(':password', $hashedPassword);
                $sqlQuery->bindParam(':pic', $uniqueFileName); 
                $sqlQuery->bindParam(':room_no', $room_number);

                try {
                    $sqlQuery->execute();
                    header("location:add_user.php?message=User added successfully.");
                } catch (PDOException $e) {
                    header("location:add_user.php?message=Error adding user: " . $e->getMessage());
                }
            } else {
                header("location:add_user.php?message=Sorry, there was an error uploading your file.");
                exit;
            }
        } else {
            header("location:add_user.php?message=No image file selected or file upload error.");
            exit;
        }
    }
}
?>
