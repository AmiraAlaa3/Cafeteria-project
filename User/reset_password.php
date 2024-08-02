<!-- screen 1 -->
 <?php 
 session_start();
 if(!isset($_SESSION['username'])) {
    echo 'my  username';
 }
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
 }?>
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
        <p class="title m-auto" style="font-size:45px;">Forgot Password</p>
        <form class="container"  method="POST">
            <div class="inputs d-flex justify-content-between m-auto align-items-center  mt-4 mb-4" style="width:70%;">
                <label style="font-size: 28px;" for="mail">Email :</label>
                <input style="width:70% ;border:0; padding-left: 10px; height: 40px; font-size: 16px;" class="border-bottom" type="text" name="mail" id="mailInput" placeholder="Enter your Email">
            </div>
            <div class="inputs  d-flex justify-content-between align-items-center m-auto mt-4 mb-4" style="width:70%;">
                <label  style="font-size: 28px;" for="pass">new Password :</label>
                <input  style="width:70% ;border:0; padding-left: 10px; height: 40px; font-size: 16px;" class="border-bottom" type="password" name="pass" id="passInput" placeholder="Enter your Password">
            </div>
            <div class="inputs  d-flex justify-content-between align-items-center m-auto mt-4 mb-4" style="width:70%;">
                <label  style="font-size: 28px;" for="passed"> confirm Password :</label>
                <input  style="width:70% ;border:0; padding-left: 10px; height: 40px; font-size: 16px;" class="border-bottom" type="password" name="passed" id="passInput" placeholder="Enter your Password">
            </div>
            <?php  
            if ($users) {
        
                $user1=$_POST["mail"];
                $user2=$_POST["pass"];
                foreach ($users as $user) {
                     $stmt->bindParam(':username',$user['user_name']);
                     $stmt->bindParam(':password',$user["password"]);
                     // Prepare the SQL query
                     if($user1 == ':username'){}
                     $query = `UPDATE USERNAME SET password = {$user2} WHERE $user1 = :username ;`;
                    $stmt = $connection->prepare($query);
                    
                    // Execute the query
                  $stmt->execute();
                
                 // Fetch all the results
                  $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                 // Display results
                
                } 
            } 
            ?>
            <button style="border:0;border-radius:8px; width:90px; height:45px; margin: 50px 0 40px 46%; " id="login-btn">submit <?php ?></button>
        </form>
        <script>
            var btn=document.getElementById("login-btn");
            btn.addEventListener("click",function(){
                location.href="login.php";
            })

        </script>
        
    </div>
    











<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>