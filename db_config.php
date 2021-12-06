<?php 
     define( 'DB_HOST', 'localhost' ); // database host
     define( 'DB_NAME', 'database' ); // database name
     define( 'DB_USER', 'root' ); // database username
     define( 'DB_PASS', '' ); // database password
 
     $conn= new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
     if($conn->connect_error){
         die('Connection Failed'.$conn->connect_error);
     }
?>