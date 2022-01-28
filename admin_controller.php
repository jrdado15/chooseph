<?php 
session_start();   
//admin login
if(isset($_POST['btn_submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
     
    if ($username=='admin' && $password=='1234'){
         $_SESSION['admin_id'] = 'admin';
         header('location: admin.php');
    } else {
         echo '<script>alert("INVALID CREDENTIALS!!!!")</script>';
    }
   }

//admin logout
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['admin_id']);
    header('location: admin_login.php');
    exit();
}

?>