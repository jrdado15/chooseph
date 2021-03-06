<?php
  session_start();
  ob_start();
  include_once 'dbconfig.php';
  //Restricts user from going back to login page if logged in
  if(!isset($_SESSION['userid'])) {
    header('location: login.php');
    exit();
  }

  $curr_data = array();
  $likedUsersEmail = array();
  $likedUsersID = array();
  $minAge; $maxAge;  $sex;

  if(isset($_GET['btnSubmit'])) {
    $email = $_SESSION['userid'];
    $sql3 = "SELECT * FROM match_record WHERE unique_id1 = '$email'";
    $query3 = $conn->query($sql3);
    $counter = 0;
    while($row3 = $query3->fetch_assoc()) {
      $likedUsersEmail[$counter] = $row3['unique_id2'];
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
  </head>
  <body>
    
    <div class="row container-fluid">
      
      <div class="col-3">
      <div class="card" style="width: 18rem; height: 100%">
        <img src="https://mdbootstrap.com/img/Photos/Avatars/img%20(31).jpg" class="profile-cover" alt="...">
        <div class="card-body">
          <h5 class="card-title h2 mb-4"><?php echo $_SESSION['name']?></h5>
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
      <div class="col-9 justify-content-center text-center relative-full-div">
        <div class="card mt-4 mb-4 ">
          <img src="https://mdbootstrap.com/img/Photos/Avatars/img%20(31).jpg" class="date-cover" alt="...">
          <div class="card card-body">       
            <input type="hidden" id="person-email" value=""> 
            <h5 id="person-name" class="card-title h3"> MissingNO </h5>
            <p id="description" class="card-text h5"> ??? </p>  
            <!--START Temporary Age Range and Sex for showing date potentials -->
            <form name="form" method="GET" action="index.php">
            <input id="min-age" name="min-age" class="form-control" type="text" placeholder="Default input">
            <script type="text/javascript">
              document.getElementById('min-age').value = '18';
            </script>
            <input id="max-age" name="max-age" class="form-control" type="text" placeholder="Default input">
            <script type="text/javascript">
              document.getElementById('max-age').value = '70';
            </script>
            <select id="sexSelect" name="sexSelect" class="form-select form-select-sm" aria-label=".form-select-sm example">
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option selected value="Everything">Everything</option>
            </select> 
            <input type="submit" name="btnSubmit" value="SUBMIT"/> <br/>
            </form>
            <!--END Temporary -->
            
            
            <div class="row mt-auto">
              <span class="col-5"></span>
              <span class="col-1"><input type="submit" value="PASS" name="passBtn0" href="#" onclick="passUser(0)"></span>
              <span class="col-1">
                <form name="form" method="GET" action="index.php">
                  <input type="submit" value="SMASH" name="smashBtn0" href="#" onclick="smashUser(0)">
                </form>
              </span>
              <span class="col-5"></span>
            </div>
            

          </div>
        </div>
      </div>
      <a href="logout.php?logout_id=<?php echo $_SESSION['userid']; ?>">Logout</a>

    <!-- Modal -->
    <div class="modal fade" id="matchesModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="person-name-modal">MissingNO</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
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
        document.getElementById('description').innerHTML = currData[flag]['pub_desc'];
        sameData = true;
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
            document.getElementById('description').innerHTML = currData[currFlag]['pub_desc'];
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
            document.getElementById('description').innerHTML = currData[currFlag]['pub_desc'];
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
      function matchClicked(){
        email = document.getElementById("matchesEmail").value;
        publicName = document.getElementById("matchesPublicName").value;
        publicDesc = document.getElementById("matchesPublicDesc").value;
        document.getElementById('person-name-modal').innerHTML = publicName;
        document.getElementById('description-modal').innerHTML = publicDesc;
      }
      
    </script>
    <script src="conversations.js"></script>
    <script src="matches.js"></script>
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
      echo "<script> alert('Record updated successfully') </script>";
    } else {
      echo "<script> alert('Error updating record:'". $conn->error .") </script> " ;
    }
  }
  
  if(isset($_POST['smashBtn1'])) {
    $userEmail = $_SESSION['userid'];
    $emailLiker =  $_COOKIE['email'];
    
    $sql = "UPDATE match_record SET match_status = 'matched' WHERE unique_id1 = '$emailLiker' AND unique_id2 = '$userEmail'";
    if ($conn->query($sql)) {
      echo "<script> alert('Record updated successfully') </script>";
    } else {
      echo "<script> alert('Error updating record:'". $conn->error .") </script> " ;
    }
  }

  ob_end_flush();
?>