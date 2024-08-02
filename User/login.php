<!-- screen 1 -->
<?php 
session_start();
$host="localhost";
$dbName="PHP";
   $dbType="mysql";
   $userName="root";
   $password="Mo@12345";

   $connection=new PDO("$dbType:host=$host;dbname=$dbName",$userName,$password);
   

try {
    // Prepare the SQL query
    $query = 'SELECT * FROM USERNAME';
    $stmt = $connection->prepare($query);
    
    // Execute the query
    $stmt->execute();
    
    // Fetch all the results
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Display results
   
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body>
    <div  class="f-page mt-5 d-flex align-items-center justifiy-content-center flex-column g-4">
        <p class="title m-auto" style="font-size:45px;">Cafeteria</p>
        <form class="container"  method="POST">
            <div class="inputs d-flex  justify-content-between m-auto align-items-center  mt-4 mb-4" style="width:70%;">
                <label style="font-size: 28px;" for="mail">Email :</label>
                <input style="width:75% ;border:0; padding-left: 10px; height: 40px; font-size: 16px;" class="border-bottom" type="email" name="mail" id="mailInput" placeholder="Enter your Email">
            </div>
            <div class="inputs  d-flex justify-content-between align-items-center m-auto mt-4 mb-4" style="width:70%;">
                <label  style="font-size: 28px;" for="pass"> Password :</label>
                <input  style="width:75% ;border:0; padding-left: 10px; height: 40px; font-size: 16px;" class="border-bottom" type="password" name="pass" id="passInput" placeholder="Enter your Password">
            </div>

            <button style="border:0;border-radius:8px; width:90px; height:45px; margin: 50px 0 40px 46%; position:relative"> Login</button>
            <?php  
            if ($users) {
        
                foreach ($users as $user) {
                     $user1=$_POST["mail"];
                     $user2=$_POST["pass"];
        
                     if ($user1!= $user['email'] && $user2!=$user['password']) {
                        echo'<p ">Your user name or password is invalid </p> ';
                     }else {
                        echo '<p ">validddddd</p>';
                        $_SESSION['email'] = $user1;
                        $_SESSION['name'] = $user['user_name'];
                     }
                }
            } else {
                echo 'No users found.';
            }?>
        </form>
        <a href="./reset_password.php" class="m-auto" style="font-size:18px;">forgot password?</a>
        
    </div>

    

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script> 

</script>
</body>
</html>