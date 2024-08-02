<?php  
$db_server = "localhost";
$db_user = "root";
$db_pass = ""; 
$db_name = "cafeteria";
$db_type = "mysql";
$db_port = 3307;
$connection = new PDO("$db_type:host=$db_server;port=$db_port;dbname=$db_name", $db_user, $db_pass);
?>