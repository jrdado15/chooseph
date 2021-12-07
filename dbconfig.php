<?php
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db_name = "chooseph";
    $conn = mysqli_connect($server, $user, $pass, $db_name);
    if(!$conn){
        echo "Database connection error" . mysqli_connect_error();
    }
?>
