<?php  
$db_server = "localhost";
$db_user = "root";
$db_pass = "Mo@12345"; 
$db_name = "new";
$db_type = "mysql";
$db_port = 3306;
$connection = new PDO("$db_type:host=$db_server;port=$db_port;dbname=$db_name", $db_user, $db_pass);
?>