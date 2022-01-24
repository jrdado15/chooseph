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
  //Gets your public id
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

  //Start
  $minAge; $maxAge; $sex; $rotationNum = 0;
  
  if(isset($_GET['btnSubmit'])) {
    $minAge = $_GET["min-age"];
    $maxAge = $_GET["max-age"];
    $sex = $_GET["sexSelect"];
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
    <!-- Hidden Data -->
    <input type="hidden" id="rotation" value=0>

    <div class="row mt-3 container-fluid">
      <!-- SIDEBAR START -->
      <div class="col-3 mt-3">
      <div class="card" style="width: 20rem; height: 100%">
        <div class="profile-div">
          <img src="images/<?php echo $imageArray[0];?>" id="userPic" class="profile-picture mt-2" alt="..." style="border-radius: 10px;">
          <!-- Menu Dropdown Start -->
          <div class="topright dropdown mt-1 ml-1">
            <button class="btn btn-outline-primary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-v"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item" href="editprofile.php"><i class="far fa-edit"></i>  Edit Profile</a>
              <a class="dropdown-item" href="logout.php?logout_id=<?php echo $_SESSION['userid']; ?>"><i class="fas fa-sign-out-alt"></i>  Logout</a>
            </div>
          </div>
          <!-- Menu Dropdown End -->
        </div>
      <div class="card-body">
          <div class="row justify-content-center">
          <h5 class="card-title h2 mb-4"><?php echo $_SESSION['name']?></h5>  

          </div>
          <div class="mb-2">
          <!-- Filter Start -->
            <button class="btn btn-primary" style="width: 100%" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false">
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
            <div class="vl"></div>
            <button id="matches-toggle" type="button" class="btn btn-primary w-100">Matches</button>
          </div> 
          <!--START Users Conversations -->
          <div id="conversations-div" class="conversations-list sidebar card-body" style="max-height:20rem; overflow-y:scroll;"></div>
          <!--END -->
          <!--START Users Matches -->
          <div id="matches-div" class="matches-list sidebar card-body" style="display:none; max-height:20rem; overflow-y:scroll;"></div>
          <!--END -->
        </div>
      </div>
      </div>
      <!-- SIDEBAR END -->

      <!-- RIGHT PANEL START -->
        <div class="col-9 justify-content-center text-center relative-full-div">
        <div class="card">
          <div class="row">
          <!-- Profile Pictures Start -->
          <div class="col-6">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
              <ol class="carousel-indicators">
                  <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                  <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                  <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                  <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
              </ol>
              <div class="carousel-inner">
                  <div class="display-image-1 carousel-item active">
                    <img src="images/placeholder.png" id="img1" class="matches-picture" alt="..." >
                  </div>
                  <div class="display-image-2 carousel-item">
                    <img src="images/placeholder.png" id="img2" class="matches-picture" alt="..." >
                  </div>
                  <div class="display-image-3 carousel-item">
                    <img src="images/placeholder.png" id="img3" class="matches-picture" alt="..." >
                  </div>
                  <div class="display-image-4 carousel-item">
                    <img src="images/placeholder.png" id="img4" class="matches-picture" alt="..." >
                  </div>
              </div>
              <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
              </a>
            </div>
          </div>
          <!-- Profile Pictures End -->
          <!-- Profile Info Start -->
          <div class="display-info col-6 d-flex align-items-center justify-content-center"  style="background-color:#0ba8d3;">
                
          </div>
          <!-- Profile Info End -->
          </div>
      </div>

        </div>
      <!-- RIGHT PANEL END --> 

    <!-- Modal -->
    <div class="modal" tabindex="-1" id="matchesModal" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <div id="modalcarousel" class="carousel slide profile-div" data-ride="carousel">
                <ol class="carousel-indicators">
                  <li data-target="#modalcarousel" data-slide-to="0" class="active"></li>
                  <li data-target="#modalcarousel" data-slide-to="1"></li>
                  <li data-target="#modalcarousel" data-slide-to="2"></li>
                  <li data-target="#modalcarousel" data-slide-to="3"></li>
                </ol>
                <!-- Image 1-4 placeholder -->
                <div id="image-carousel" class="carousel-inner">
                  <div class="carousel-item active">
                    <img src="" id="mimg1" class="modal-picture" alt="..." style="">
                  </div>
                  <div class="carousel-item">
                    <img src="" id="mimg2" class="modal-picture" alt="..." style="">
                  </div>
                  <div class="carousel-item">
                    <img src="" id="mimg3" class="modal-picture" alt="..." style="">
                  </div>
                  <div class="carousel-item">
                    <img src="" id="mimg4" class="modal-picture" alt="..." style="">
                  </div>
                </div>
                <a class="carousel-control-prev" href="#modalcarousel" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#modalcarousel" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
                </a>
                <button type="button" class="close topright" data-bs-dismiss="modal" aria-label="Close">
                  <span class="far fa-times-circle" style="color:white; font-size: 30px;"></span>
                </button>
            </div>
            <br>
            <div class="text-center">
              <h5 class="modal-title" id="person-name-modal">MissingNO</h5>    
              <p id="description-modal">???</p>
            </div>
          </div>
          <div class="modal-footer">
              <form name="form" method="POST" action="index.php">
                <input type="submit" class="btn btn-danger"  value="Delete" name="passBtn1" href="#" onclick="passUser(1)">
                <input type="submit" class="btn btn-success" value="Confirm" name="smashBtn1" href="#" onclick="smashUser(1)">
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
      const carousel = document.getElementById("image-carousel");
      const mimg1 = document.getElementById('mimg1');
      const mimg2 = document.getElementById('mimg2');
      const mimg3 = document.getElementById('mimg3');
      const mimg4 = document.getElementById('mimg4');
      var minAge, maxAge, rotationNum = 0;
      var email, sex;

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

      function selectElement(id, valueToSelect) {    
        let element = document.getElementById(id);
        element.value = valueToSelect;
      } 

      function setData(){
        minAge = <?php echo json_encode($minAge); ?>;
        maxAge = <?php echo json_encode($maxAge); ?>;
        sex = <?php echo json_encode($sex); ?>;
        document.getElementById('min-age').value = minAge;
        document.getElementById('max-age').value = maxAge;
        selectElement('sexSelect', sex);
      }

      function passUser(){
        document.cookie = 'email=' + email;
      }

      function smashUser(){
        var likedEmail = document.getElementById("person-email").value;
        document.cookie = 'email=' + email;
        document.cookie = 'likedEmail=' + likedEmail;
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
    <script type="text/javascript" src="conversations.js"></script>
    <script type="text/javascript" src="matches.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

  </body>
</html> 

<?php
  if(isset($_GET['btnSubmit'])){
    echo "<script> setData(); </script>";
    echo "<script type='text/javascript' src='display.js'></script>";
    echo "<script type='text/javascript' src='display-images.js'></script>";
  }

  //if user submits pass button, add +1 to rotation and reload page
  if (isset($_GET['passBtn0'])){
    $userEmail = $_SESSION['userid'];
    echo "<script> alert('Error updating record:'". $conn->error .") </script> " ;
    $sql5 = "SELECT * FROM users_profile WHERE email = '$email'";
    $query5 = $conn->query($sql5);
    while($row5 = $query5->fetch_assoc()) {
      $rotationNum = $row5['rotation'];
    }
    $rotationNum++;
    $sql = "UPDATE users_profile SET rotation = $rotationNum WHERE email = '$email'";
    if ($conn->query($sql)) {
      header('location: index.php?min-age=18&max-age=70&sexSelect=Everything&btnSubmit=SUBMIT');
    } else {
      echo "<script> alert('Error updating record:'". $conn->error .") </script> " ;
    }
  }

  //if user submits heart button, add +1 to rotation, indicate user liked current person, and reload page
  if(isset($_GET['smashBtn0'])){
    $userEmail = $_SESSION['userid'];
    $emailLiked = $_COOKIE['likedEmail'];
    echo "<script> alert('Error updating record:'". $conn->error .") </script> " ;
    $sql5 = "SELECT * FROM users_profile WHERE email = '$email'";
    $query5 = $conn->query($sql5);
    while($row5 = $query5->fetch_assoc()) {
      $rotationNum = $row5['rotation'];
    }
    $rotationNum++;
    $sql = "UPDATE users_profile SET rotation = $rotationNum WHERE email = '$email'";
    if ($conn->query($sql)) {
      foreach($curr_data as $subKey => $subArray){
        if($subArray['pub_id'] == $emailLiked){
          unset($curr_data[$subKey]);
        }
      }
      $sql = "INSERT INTO match_record(unique_id1, unique_id2, match_status) VALUES ('$userEmail', '$emailLiked', 'unmatched')";
      if ($conn->query($sql)) { 
        header('location: index.php?min-age=18&max-age=70&sexSelect=Everything&btnSubmit=SUBMIT');
      } else {
        echo "<script> alert('Error updating record:'". $conn->error .") </script> " ;
      }
    } else {
      echo "<script> alert('Error updating record:'". $conn->error .") </script> " ;
    }
  }  

  //if user submits pass button on modal, remove that person on match record and reload page
  if(isset($_POST['passBtn1'])) {
    $userEmail = $_SESSION['userid'];
    $emailLiker =  $_COOKIE['email'];
    
    $sql = "DELETE FROM match_record WHERE unique_id1 = '$emailLiker' AND unique_id2 = '$userEmail'";
    if ($conn->query($sql)) {
      header('location: index.php?min-age=18&max-age=70&sexSelect=Everything&btnSubmit=SUBMIT');
    } else {
      echo "<script> alert('Error updating record:'". $conn->error .") </script> " ;
    }
  }
  
  //if user submits smash button on modal, change the relationship to matched and reload page
  if(isset($_POST['smashBtn1'])) {
    $userEmail = $_SESSION['userid'];
    $emailLiker =  $_COOKIE['email'];
    
    $sql = "UPDATE match_record SET match_status = 'matched' WHERE unique_id1 = '$emailLiker' AND unique_id2 = '$userEmail'";
    if ($conn->query($sql)) {
      header('location: index.php?min-age=18&max-age=70&sexSelect=Everything&btnSubmit=SUBMIT');
    } else {
      echo "<script> alert('Error updating record:'". $conn->error .") </script> " ;
    }
  }

  ob_flush();
?>