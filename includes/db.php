<?php
$db_server = "sql12.freesqldatabase.com";
$db_user = "sql12722068"; 
$db_pass = "ck6CCqb8zH"; 
$db_name = "sql12722068";
$db_type = "mysql"; 
$db_port = 3306; 
$connection = new PDO("$db_type:host=$db_server;port=$db_port;dbname=$db_name", $db_user, $db_pass);
?>
