<?php
    include_once 'autoloader.php';

    if(isset($_GET['state']) && FB_APP_STATE == $_GET['state']){ 
          $fbLogin = tryAndLoginWithFacebook( $_GET);

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
    <a href = "<?php echo getFacebookLoginUrl() ?>">
        <div class = "btn">
            Login with Facebook
        </div>
    </a>
</body>
</html>