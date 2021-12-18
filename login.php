<?php
    include_once 'autoloader.php';

    if(isset($_GET['state']) && FB_APP_STATE == $_GET['state']){ 
        $fbLogin = tryAndLoginWithFacebook( $_GET);
    }

    //Restricts user from going back to login page if logged in
    if(isset($_SESSION['userid'])){
        header('location: index.php?min-age=18&max-age=70&sexSelect=Everything&btnSubmit=SUBMIT');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChoosePH</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="choosephStyles.css">
    <script src="https://kit.fontawesome.com/21ed7fc1ee.js" crossorigin="anonymous"></script>
</head>
<body>

        <div class="h-100 d-flex align-items-center justify-content-center" style="">
                <div class="card text-center" style="width: 500px;">
                    <img class="card-img-top" src="images/brand.png" alt="Card image cap">
                        <div class="card-body">
                            <a href = "<?php echo getFacebookLoginUrl() ?>" class="btn btn-lg">
                            <i class="fab fa-facebook-f p-2"></i>Sign in with facebook</a>
                        </div>
                </div>  
        </div>






        
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>