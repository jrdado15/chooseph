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
</head>
<body>
    <form name="register" method="post" enctype="multipart/form-data">
        <?php echo $_SESSION['name'];?><br>
        <input type="file" name="img[]" accept="image/*" required><br>
        <input type="file" name="img[]" accept="image/*"><br>
        <input type="file" name="img[]" accept="image/*"><br>
        <input type="file" name="img[]" accept="image/*"><br>
        Bio: <input type="text" name="bio" required><br>
        Sex: <select name="sex">
            <option value=Male selected>Male</option>
            <option value=Female>Female</option>
        </select><br>
        Age: <input type="number" name="age" min="18" max="100" required><br>
        <input type="submit" name="saveButton" value="SAVE">
    </form>
</body>
</html>