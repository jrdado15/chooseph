<?php
  session_start();
  ob_start();
  include_once 'dbconfig.php';
  //Restricts user from going back to login page if logged in
  if(!isset($_SESSION['userid'])) {
    header('location: login.php');
    exit();
  }

  $email = $_SESSION['userid'];
  $check="SELECT * FROM users_profile WHERE email='$email' AND pub_id=0 LIMIT 1";
  if($conn->query($check)->num_rows > 0) {
    header('location: register.php');
    exit();
  }
  //Gets you public id
  $idEmail = $_SESSION['userid'];
  $idSql="SELECT * FROM users_profile WHERE email = '$idEmail'";
  $idArray = array();
  $idQuery = $conn->query($idSql);
  while($idRow = $idQuery->fetch_assoc()) {
      $idArray = $idRow;
  }
  //Gets your image list
  $imageId = $idArray['pub_id'];
  $imageSql="SELECT * FROM public_record WHERE pub_id = '$imageId'";
  $imageArray = array();
  $imageQuery = $conn->query($imageSql);
  while($imageRow = $imageQuery->fetch_assoc()) {
      $imageArray = $imageRow;
  }
  //Turns comma list into array
  $imageArray = explode(',', $imageArray['pub_img']);
  //print_r($imageArray);

  $curr_data = array();
  $likedUsersEmail = array();
  $likedUsersID = array();
  $minAge; $maxAge;  $sex;

  if(isset($_GET['btnSubmit'])) {
    $email = $_SESSION['userid'];
    $sql3 = "SELECT * FROM match_record WHERE unique_id1 = '$email' OR unique_id2 = '$email'";
    $query3 = $conn->query($sql3);
    $counter = 0;
    while($row3 = $query3->fetch_assoc()) {
      if ($email == $row3['unique_id1']){
        $likedUsersEmail[$counter] = $row3['unique_id2'];
      } elseif ($email == $row3['unique_id2']){
        $likedUsersEmail[$counter] = $row3['unique_id1'];
      }
      $counter++;
    }
    $counter = 0;
    foreach ($likedUsersEmail as $likedEmail) {
      $sql4 = "SELECT * FROM users_profile WHERE email = '$likedEmail'";
      $query4 = $conn->query($sql4);
      if(($row4 = $query4->fetch_assoc()) > 0) {
        $likedUsersID[$counter] = $row4['pub_id'];
        $counter++;
      }
    }
    $sql0 = "SELECT * FROM users_profile WHERE email = '$email'";
    $query0 = $conn->query($sql0);
    if(($row0 = $query0->fetch_assoc()) > 0) {
      $userPubID = $row0['pub_id'];
      $minAge = $_GET["min-age"];
      $maxAge = $_GET["max-age"];
      $sex = $_GET["sexSelect"];
      $implodedLikedUsersID = implode(", ",array_values($likedUsersID));
      if ($sex == "Everything"){
        $sql = "SELECT * FROM public_record WHERE pub_age BETWEEN $minAge AND $maxAge AND NOT pub_id = $userPubID AND pub_id NOT IN ('$implodedLikedUsersID')";
      } else {
        $sql = "SELECT * FROM public_record WHERE pub_sex='$sex' AND pub_age BETWEEN $minAge AND $maxAgeNOT AND NOT pub_id = $userPubIDAND AND pub_id NOT IN ('$implodedLikedUsersID')";
      }
      $result = $conn->query($sql);
      if ($result) {
        if ($result->num_rows > 0) {
          $counter = 0;
          while($row = $result->fetch_assoc()) { 
            $id = $row['pub_id'];
            
            if (in_array($id, $likedUsersID)){
              //
            } else {
              $curr_data[] = $row;
              $sql2 = "SELECT * FROM users_profile WHERE pub_id = '$id'";
              $query2 = $conn->query($sql2);
              if(($row2 = $query2->fetch_assoc()) > 0) {
                $curr_data[$counter]['pub_id'] = $row2['email'];
                //print_r($curr_data[$counter]);
                $counter++;
              }
            }
          }
        } else {
          echo '<script>alert("NO RECORD")</script>'; 
        }
      } else {
        echo $conn->error;
      }
    }
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>ChoosePH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/21ed7fc1ee.js" crossorigin="anonymous"></script>
  </head>
  <body>
  
    
    <div class="row mt-3 container-fluid">
      <!-- SIDEBAR START -->
      <div class="col-3 mt-3">
      <div class="card" style="width: 20rem; height: 100%">
        <img src="images/<?php echo $imageArray[0];?>" id="userPic" class="profile-cover mt-2" alt="..." style="border-radius: 10px;">
        <div class="card-body">
          <div class="row justify-content-center">
          <h5 class="card-title h2 mb-4"><?php echo $_SESSION['name']?></h5>  
          <!-- Menu Dropdown Start -->
          <div class="dropdown mt-1 ml-1">
            <button class="btn btn-light" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-v"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item" href="#"><i class="far fa-edit"></i>  Edit Profile</a>
              <a class="dropdown-item" href="logout.php?logout_id=<?php echo $_SESSION['userid']; ?>"><i class="fas fa-sign-out-alt"></i>  Logout</a>
            </div>
          </div>
          <!-- Menu Dropdown End -->
          </div>
          
          <div class="mb-2">
          <!-- Filter Start -->
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false">
                Filters
            </button>
            <div class="collapse" id="filter">
              <div class="card card-body">
              <form name="form" method="GET" action="index.php">
                <div class="input-group">
                    <input id="min-age" name="min-age" class="form-control" type="text" placeholder="Default input">
                    <script type="text/javascript">
                    document.getElementById('min-age').value = '18';
                    </script>
                    <input id="max-age" name="max-age" class="form-control" type="text" placeholder="Default input">
                    <script type="text/javascript">
                      document.getElementById('max-age').value = '70';
                    </script>
                    <div class="input-group-append">
                    <select class="btn custom-select" id="sexSelect" name="sexSelect" class="form-select form-select-sm" aria-label=".form-select-sm example">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option selected value="Everything">Everything</option>
                    </select> 
                    <input class="btn btn-primary" type="submit" name="btnSubmit" value="SUBMIT"/> <br/>
                  </div>
                </div>
                </form>
              </div>
            </div>
            <!--Filter End -->
            <hr>
          </div>
          
          <div class="btn-group d-flex" role="group" aria-label="...">
            <button id="conversations-toggle" type="button" class="btn btn-primary w-100">Conversations</button>
            <button id="matches-toggle" type="button" class="btn btn-primary w-100">Matches</button>
          </div> 
          <!--START Users Conversations -->
          <div id="conversations-div" class="conversations-list card-body" style="max-height:20rem; overflow-y:scroll"></div>
          <!--END -->
          <!--START Users Matches -->
          <div id="matches-div" class="matches-list card-body" style="display:none; max-height:20rem; overflow-y:scroll"></div>
          <!--END -->
        </div>
      </div>
      </div>
      <!-- SIDEBAR END -->


      <!-- RIGHT PANEL START -->
        <div class="col-9 justify-content-center text-center relative-full-div">
        <div class="card">
          <div class="row m-3" >
            <!-- Profile Pictures Start -->
            <div class="col-6">
            <img src="" id="img1" class="profile-cover" alt="..." style="">
            <img src="" id="img2" class="profile-cover" alt="..." style="">
            <img src="" id="img3" class="profile-cover" alt="..." style="">
            <img src="" id="img4" class="profile-cover" alt="..." style="">
            </div>
            <!-- Profile Pictures End -->
            <!-- Profile Info Start -->
            <div class="col-6 d-flex align-items-center justify-content-center"  style="background-color:#0ba8d3;">
              <div>
              <input type="hidden" id="person-email" value="">
              <h5 id="person-name" class="card-title h2 text-white"> MissingNO, </h5>
              <div class="row justify-content-center h3 text-white">
              <h5 id="person-age" class="h3 text-white"> ##</h5>, 
              <h5 id="person-sex" class="ml-1 h3 text-white"> ??</h5>
              </div>
              <p id="description" class="card-text h5 text-white"> ??? </p>
                <div class="row">
                    <button type="submit" class=" btn btn-info btn-light m-2 p-3 " value="PASS" name="passBtn0" href="#" onclick="passUser(0)" style="border-style: solid; border-color: #c8d6e5; border-radius: 50%;">
                      <img src="images/cross.png" alt="" style="width: 50px;">
                      </button>
                    <form name="form" method="GET" action="index.php">
                      <button type="submit" class="btn btn-light m-2 p-3 " value="SMASH" name="smashBtn0" href="#" onclick="smashUser(0)" style="border-style: solid; border-color: #c8d6e5; border-radius: 50%;">
                      <img src="images/heart.png" alt="" style="width: 50px;">
                      </button>
                    </form>
                </div>
              </div>     
            </div>
            <!-- Profile Info End -->
          </div>
        </div>
        </div>
      <!-- RIGHT PANEL END --> 

    <!-- Modal -->
    <div class="modal fade" id="matchesModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="person-name-modal">MissingNO</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <img src="" id="mimg1" class="profile-cover" alt="..." style="">
        <img src="" id="mimg2" class="profile-cover" alt="..." style="">
        <img src="" id="mimg3" class="profile-cover" alt="..." style="">
        <img src="" id="mimg4" class="profile-cover" alt="..." style="">
        <div class="modal-body" id="description-modal">
        ???
        </div>
        <div class="modal-footer">
        <form name="form" method="POST" action="index.php">
          <input type="submit" value="PASS" name="passBtn1" href="#" onclick="passUser(1)">
          <input type="submit" value="SMASH" name="smashBtn1" href="#" onclick="smashUser(1)">
        </form>
        </div>
        </div>
      </div>
    </div> 
    <!-- MODAL END -->

    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>      
    <script>
      const chatDiv = document.getElementById("conversations-div");
      const matchDiv = document.getElementById("matches-div");
      const chatBtn = document.getElementById("conversations-toggle");
      const matchBtn = document.getElementById("matches-toggle");
      const img1 = document.getElementById('img1');
      const img2 = document.getElementById('img2');
      const img3 = document.getElementById('img3');
      const img4 = document.getElementById('img4');
      const mimg1 = document.getElementById('mimg1');
      const mimg2 = document.getElementById('mimg2');
      const mimg3 = document.getElementById('mimg3');
      const mimg4 = document.getElementById('mimg4');
      var currData = [];
      var currFlag = 0;
      var sameData = true;
      var minAge, maxAge;
      var email, sex, publicName, publicDesc;

      chatBtn.onclick = function () {
        if (chatDiv.style.display !== "none") {
          //Do nothing
        } else {
          chatDiv.style.display = "block";
          matchDiv.style.display = "none";
        }
      };
      matchBtn.onclick = function () {
        if (matchDiv.style.display !== "none") {
          //Do nothing
        } else {
          matchDiv.style.display = "block";
          chatDiv.style.display = "none";
        }
      };
      function resetData(){
        currData = <?php echo json_encode($curr_data); ?>;
        minAge =  <?php echo json_encode($minAge); ?>;
        maxAge =  <?php echo json_encode($maxAge); ?>;
        sex = <?php echo json_encode($sex); ?>;
        document.getElementById('min-age').value = minAge;
        document.getElementById('max-age').value = maxAge;
        selectElement('sexSelect', sex);
        currFlag = 0;
        viewUser(currFlag);
      }
      function viewUser(flag){
        document.getElementById('person-name').innerHTML = currData[flag]['pub_name'];
        document.getElementById('person-age').innerHTML = currData[flag]['pub_age'];
        document.getElementById('person-sex').innerHTML = currData[flag]['pub_sex'];
        document.getElementById('description').innerHTML = currData[flag]['pub_desc'];
        var arr = currData[flag]['pub_img'].split(',');
        img1.src = 'images/' + arr[0];
        img2.src = 'images/' + arr[1];
        img3.src = 'images/' + arr[2];
        img4.src = 'images/' + arr[3];
        sameData = true;
        if(img1.src == "http://localhost/chooseph/images/")
          img1.style.display='none';
        if(img2.src == "http://localhost/chooseph/images/")
          img2.style.display='none';
        if(img3.src == "http://localhost/chooseph/images/")
          img3.style.display='none';
        if(img4.src == "http://localhost/chooseph/images/")
          img4.style.display='none';
      }
      function passUser(type){
        if(type == 0){
          //from main menu
          document.cookie = 'email=' + currData[currFlag]['pub_id'];
          
          if (sameData){
            if (currFlag == currData.length-1){
              currFlag = 0;
            } else {
              currFlag += 1;
            }
            document.getElementById('person-name').innerHTML = currData[currFlag]['pub_name'];
            document.getElementById('person-age').innerHTML = currData[currflag]['pub_age'];
            document.getElementById('person-sex').innerHTML = currData[currflag]['pub_sex'];
            document.getElementById('description').innerHTML = currData[currFlag]['pub_desc'];
            var arr = currData[currFlag]['pub_img'].split(',');
            img1.src = 'images/' + arr[0];
            img2.src = 'images/' + arr[1];
            img3.src = 'images/' + arr[2];
            img4.src = 'images/' + arr[3];
            if(img1.src == "http://localhost/chooseph/images/")
              img1.style.display='none';
            if(img2.src == "http://localhost/chooseph/images/")
              img2.style.display='none';
            if(img3.src == "http://localhost/chooseph/images/")
              img3.style.display='none';
            if(img4.src == "http://localhost/chooseph/images/")
              img4.style.display='none';
          }
        } else {
          //from matches modal 
          document.cookie = 'email=' + email;
        }
      }
      function smashUser(type){
        if(type == 0){
          //from main menu
          document.cookie = 'email=' + currData[currFlag]['pub_id'];
          if (sameData){
            if (currFlag == currData.length-1){
              currFlag = 0;
            } else {
              currFlag += 1;
            }
            document.getElementById('person-name').innerHTML = currData[currFlag]['pub_name'];
            document.getElementById('person-age').innerHTML = currData[currflag]['pub_age'];
            document.getElementById('person-sex').innerHTML = currData[currflag]['pub_sex'];
            document.getElementById('description').innerHTML = currData[currFlag]['pub_desc'];
            var arr = currData[currFlag]['pub_img'].split(',');
            img1.src = 'images/' + arr[0];
            img2.src = 'images/' + arr[1];
            img3.src = 'images/' + arr[2];
            img4.src = 'images/' + arr[3];
            if(img1.src == "http://localhost/chooseph/images/")
              img1.style.display='none';
            if(img2.src == "http://localhost/chooseph/images/")
              img2.style.display='none';
            if(img3.src == "http://localhost/chooseph/images/")
              img3.style.display='none';
            if(img4.src == "http://localhost/chooseph/images/")
              img4.style.display='none';
          }
        } else {
          //from matches modal
          document.cookie = 'email=' + email;
        }
      }
      function selectElement(id, valueToSelect) {    
        let element = document.getElementById(id);
        element.value = valueToSelect;
      } 
      function matchClicked(flag){
        email = document.getElementById("matchesEmail"+flag).value;
        publicName = document.getElementById("matchesPublicName"+flag).value;
        publicImage = document.getElementById("matchesPublicImage"+flag).value;
        publicDesc = document.getElementById("matchesPublicDesc"+flag).value;
        document.getElementById('person-name-modal').innerHTML = publicName;
        var arr = publicImage.split(',');
        mimg1.src = 'images/' + arr[0];
        mimg2.src = 'images/' + arr[1];
        mimg3.src = 'images/' + arr[2];
        mimg4.src = 'images/' + arr[3];
        if(mimg1.src == "http://localhost/chooseph/images/")
          mimg1.style.display='none';
        if(mimg2.src == "http://localhost/chooseph/images/")
          mimg2.style.display='none';
        if(mimg3.src == "http://localhost/chooseph/images/")
          mimg3.style.display='none';
        if(mimg4.src == "http://localhost/chooseph/images/")
          mimg4.style.display='none';
        document.getElementById('description-modal').innerHTML = publicDesc;
      }
    </script>
    <script src="conversations.js"></script>
    <script src="matches.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

  </body>
</html>

<?php
  if(isset($_GET['btnSubmit']) || isset($_GET['smashBtn0'])){
    echo "<script> resetData(); </script>";
  }  
  
  if(isset($_GET['smashBtn0'])) {
    $userEmail = $_SESSION['userid'];
    $emailLiked =  $_COOKIE['email'];

    foreach($curr_data as $subKey => $subArray){
      if($subArray['pub_id'] == $emailLiked){
        unset($curr_data[$subKey]);
      }
    }

    $sql = "INSERT INTO match_record(unique_id1, unique_id2, match_status) VALUES ('$userEmail', '$emailLiked', 'unmatched')";
    if ($conn->query($sql)) { 
      //echo "<script> alert('Record updated successfully') </script>";
      header('location: index.php?min-age=18&max-age=70&sexSelect=Everything&btnSubmit=SUBMIT');
    } else {
      echo "<script> alert('Error updating record:'". $conn->error .") </script> " ;
    }
  }

  if(isset($_POST['passBtn1'])) {
    $userEmail = $_SESSION['userid'];
    $emailLiker =  $_COOKIE['email'];
    
    $sql = "DELETE FROM match_record WHERE unique_id1 = '$emailLiker' AND unique_id2 = '$userEmail'";
    if ($conn->query($sql)) {
      //echo "<script> alert('Record updated successfully') </script>";
      header('location: index.php?min-age=18&max-age=70&sexSelect=Everything&btnSubmit=SUBMIT');
    } else {
      echo "<script> alert('Error updating record:'". $conn->error .") </script> " ;
    }
  }
  
  if(isset($_POST['smashBtn1'])) {
    $userEmail = $_SESSION['userid'];
    $emailLiker =  $_COOKIE['email'];
    
    $sql = "UPDATE match_record SET match_status = 'matched' WHERE unique_id1 = '$emailLiker' AND unique_id2 = '$userEmail'";
    if ($conn->query($sql)) {
      // echo "<script> alert('Record updated successfully') </script>";
      header('location: index.php?min-age=18&max-age=70&sexSelect=Everything&btnSubmit=SUBMIT');
    } else {
      echo "<script> alert('Error updating record:'". $conn->error .") </script> " ;
    }
  }

  ob_end_flush();
?>