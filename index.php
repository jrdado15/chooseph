<?php
  session_start();
  include_once 'dbconfig.php';
  //Restricts user from going back to login page if logged in
  if(!isset($_SESSION['userid'])) {
    header('location: login.php');
    exit();
  }
  $curr_data = array();
  $minAge; $maxAge;  $sex;
  if(isset($_GET['btnSubmit'])) {
    $minAge = $_GET["min-age"];
    $maxAge = $_GET["max-age"];
    $sex = $_GET["sexSelect"];
    $sql = "SELECT * FROM public_record WHERE pub_sex='$sex' AND pub_age BETWEEN $minAge AND $maxAge";
    $result = $conn->query($sql);
    if ($result) {
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {    
          //add each row returned into array
          $curr_data[] = $row;
        }
      } else {
        echo '<script>alert("NO RECORD")</script>'; 
      }
    } else {
      echo $conn->error;
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
            <h5 id="person-name" class="card-title h3"> MissingNO </h5>
            <p id="description" class="card-text h5"> ??? </p>  
            <!--START Temporary Age Range and Sex for showing date potentials -->
            <form name="form" method="GET" action="index.php">
            <input id="min-age" name="min-age" class="form-control" type="text" placeholder="Default input">
            <input id="max-age" name="max-age" class="form-control" type="text" placeholder="Default input">
            <select id="sexSelect" name="sexSelect" class="form-select form-select-sm" aria-label=".form-select-sm example">
              <option selected value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
            <input type="submit" name="btnSubmit" value="SUBMIT"/> <br/>
            </form>
            <!--END Temporary -->
            <div class="row mt-auto">
              <!-- Kailangna pagkapindot neto mapupunta sa print-->
              <span class="col-5"></span>
              <span class="col-1"><a name="passBtn" href="#" onclick="passUser()"> <i class="fas fa-times-circle fa-4x"></i></a></span>
              <span class="col-1"><a name="smashBtn" href='#' onclick="smashUser()"> <i class="fas fa-check-circle fa-4x"></i></a></span> 
              <span class="col-5"></span>
            </div>
          </div>
        </div>
      </div>
      <a href="logout.php?logout_id=<?php echo $_SESSION['userid']; ?>">Logout</a>
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
      var sex;
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
        sameData = true;
      }
      function viewUser(flag){
        document.getElementById('person-name').innerHTML = currData[flag]['pub_name'];
        document.getElementById('description').innerHTML = currData[flag]['pub_desc'];
        sameData = true;
      }
      function passUser(){
        if (sameData){
          if (currFlag == currData.length-1){
            currFlag = 0;
          } else {
            currFlag += 1;
          }
          document.getElementById('person-name').innerHTML = currData[currFlag]['pub_name'];
          document.getElementById('description').innerHTML = currData[currFlag]['pub_desc'];
        }
      }
      function smashUser(){
        if (sameData){
          if (currFlag == currData.length-1){
            currFlag = 0;
          } else {
            currFlag += 1;
          }
          document.getElementById('person-name').innerHTML = currData[currFlag]['pub_name'];
          document.getElementById('description').innerHTML = currData[currFlag]['pub_desc'];
        }
      }
      function selectElement(id, valueToSelect) {    
        let element = document.getElementById(id);
        element.value = valueToSelect;
      }
    </script>
    <script src="conversations.js"></script>
    <script src="matches.js"></script>
  </body>
</html>
<?php
  if(isset($_GET['btnSubmit'])) {
    echo "<script> resetData(); viewUser(0); </script>";
  } 
?>