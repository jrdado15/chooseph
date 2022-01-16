<?php
    session_start();
    if(isset($_SESSION['userid'])){
        include_once "dbconfig.php";
        $logout_id = $conn->real_escape_string($_GET['logout_id']);
        //If logout ID is sent
        if(isset($logout_id)){
            session_unset();
            session_destroy();
            header("location: login.php");
        }else{
            header("location: index.php?min-age=18&max-age=70&sexSelect=Everything&btnSubmit=SUBMIT");
        }
    }else{  
        header("location: login.php");
    }
?>