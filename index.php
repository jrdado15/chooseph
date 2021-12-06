<?php
session_start();

//KUNWARE HOMEPAGE TO MGA BOBO
echo $_SESSION['userid'];

//Restricts user from going back to login page if logged in
if(!isset($_SESSION['userid'])){
    header('location: login.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
    <?php
        //logout button kunware
    ?>
    </div>
</body>
</html>