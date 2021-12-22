<?php
  session_start();
  include_once 'dbconfig.php';
  if(!isset($_SESSION['userid'])) {
    header('location: login.php');
    exit();
  }
  $name = $_SESSION['fname'];
  $email = $_SESSION['userid'];
  $check="SELECT * FROM public_record WHERE pub_name='$name' LIMIT 1";
  if($conn->query($check)->num_rows > 0) {  
    header('location: index.php?min-age=18&max-age=70&sexSelect=Everything&btnSubmit=SUBMIT');
    exit();
  }
  if(isset($_POST['saveButton'])) {
    $img = $_FILES['img']['name'];
    $list = '';
    if($img[0]!='')
      $list .= time() . '_' . $img[0];
    $list .= ',';
    if($img[1]!='')
      $list .= time() . '_' . $img[1];
    $list .= ',';
    if($img[2]!='')
        $list .= time() . '_' . $img[2];
    $list .= ',';
    if($img[3]!='')
      $list .= time() . '_' . $img[3];
    $bio = $_POST['bio'];
    $sex = $_POST['sex'];
    $age = $_POST['age'];
    $sql="INSERT INTO public_record SET pub_name='$name', pub_desc='$bio', pub_sex='$sex', pub_age='$age', pub_img='$list'";
    if(!$conn->query($sql)) {
      echo $conn->error;
    } else {
      $last_id = $conn->insert_id;
      $sql2 = "SELECT * FROM users_profile WHERE email='$email'";
      $q = $conn->query($sql2);
      if(mysqli_num_rows($q) == 1) {
        $sql3="UPDATE users_profile SET pub_id='$last_id'";
        if($conn->query($sql3)) {
          for($i=0;$i<4;$i++) {
            $tmpFilePath = $_FILES['img']['tmp_name'][$i];
            if($tmpFilePath!="") {
              $newFilePath = "images/" . time() . '_' . $img[$i];
              move_uploaded_file($tmpFilePath, $newFilePath);
            }
          }
          header('location: index.php?min-age=18&max-age=70&sexSelect=Everything&btnSubmit=SUBMIT');
          exit();
        }
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChoosePH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/21ed7fc1ee.js" crossorigin="anonymous"></script>
</head>
<body>
        <div class="container d-flex justify-content-center align-items-center">
                <div class="card text-center" style="width: 500px;">
                        <div class="card-body">
                        <form name="register" method="post" enctype="multipart/form-data">
                        <h3>Welcome, <?php echo $_SESSION['name'];?><br></h3>
                        <p>To get started, please upload at least one (1) picture.</p>
                        <hr>
                        <div class="btn btn-outline-secondary btn-rounded m-2">
                        <img src="images\addImage.png" id="profileImage" style="width:100px;">
                        <input class="d-none" type="file" name="img[]" id="imageUpload" accept="image/*" required>
                        </div>
                        <div class="btn btn-outline-secondary btn-rounded m-2">
                        <img src="images\addImage.png" id="profileImage2" style="width:100px;">
                        <input class="d-none" type="file" name="img[]" id="imageUpload2" accept="image/*" required>
                        </div> <br>
                        <div class="btn btn-outline-secondary btn-rounded m-2">
                        <img src="images\addImage.png" id="profileImage3" style="width:100px;">
                        <input class="d-none" type="file" name="img[]" id="imageUpload3" accept="image/*" required>
                        </div>
                        <div class="btn btn-outline-secondary btn-rounded m-2">
                        <img src="images\addImage.png" id="profileImage4" style="width:100px;">
                        <input class="d-none" type="file" name="img[]" id="imageUpload4" accept="image/*" required>
                        </div>
                        <p>  Bio: <input type="text" name="bio" required><br></p>
                        <div class="m-1">
                        Sex: <select class="" name="sex" style="width: 200px;">
                            <option  value=Male>Male</option>
                            <option  value=Female>Female</option>
                        </select>
                        </div>
                        Age: <input type="number"  name="age" min="18" max="100" required><br>
                        <input type="submit" class="btn btn-primary btn-rounded m-3" name="saveButton" value="SAVE">
                    </form>
                        </div>
                </div>  
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script>
          //FUNCTION FOR IMAGE UPLOAD

          $("#profileImage").click(function(e) {
              $("#imageUpload").click();
          });

          $("#profileImage2").click(function(e) {
              $("#imageUpload2").click();
          });

          $("#profileImage3").click(function(e) {
              $("#imageUpload3").click();
          });

          $("#profileImage4").click(function(e) {
              $("#imageUpload4").click();
          });

          function previewProfileImage( uploader ) {   
              //ensure a file was selected 
              if (uploader.files && uploader.files[0]) {
                  var imageFile = uploader.files[0];
                  var reader = new FileReader();    
                  reader.onload = function (e) {
                      //set the image data as source
                      $('#profileImage').attr('src', e.target.result);
                  }    
                  reader.readAsDataURL( imageFile );
              }
          }

          $("#imageUpload").change(function(){
              previewProfileImage( this );
          });

          function previewProfileImage2( uploader ) {   
              //ensure a file was selected 
              if (uploader.files && uploader.files[0]) {
                  var imageFile = uploader.files[0];
                  var reader = new FileReader();    
                  reader.onload = function (e) {
                      //set the image data as source
                      $('#profileImage2').attr('src', e.target.result);
                  }    
                  reader.readAsDataURL( imageFile );
              }
          }

          $("#imageUpload2").change(function(){
              previewProfileImage2( this );
          });


          function previewProfileImage3( uploader ) {   
              //ensure a file was selected 
              if (uploader.files && uploader.files[0]) {
                  var imageFile = uploader.files[0];
                  var reader = new FileReader();    
                  reader.onload = function (e) {
                      //set the image data as source
                      $('#profileImage3').attr('src', e.target.result);
                  }    
                  reader.readAsDataURL( imageFile );
              }
          }

          $("#imageUpload3").change(function(){
              previewProfileImage3( this );
          });

          function previewProfileImage4( uploader ) {   
              //ensure a file was selected 
              if (uploader.files && uploader.files[0]) {
                  var imageFile = uploader.files[0];
                  var reader = new FileReader();    
                  reader.onload = function (e) {
                      //set the image data as source
                      $('#profileImage4').attr('src', e.target.result);
                  }    
                  reader.readAsDataURL( imageFile );
              }
          }

          $("#imageUpload4").change(function(){
              previewProfileImage4( this );
          });

        </script>
      </body>
</html>