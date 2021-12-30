<?php
  session_start();
  include_once "dbconfig.php";
  if(!isset($_SESSION['userid'])) {
    header("location: login.php");
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


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>ChoosePH</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="chat.css">
  <script src="https://kit.fontawesome.com/21ed7fc1ee.js" crossorigin="anonymous"></script>
</head>
<body>

        <div class="row container-fluid">
          <!-- Sidebar start -->
          <div class="col-3">
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
                  <div class="bg-primary border rounded text-white text-center m-2">
                    <p class="h5 mt-1">Conversations</p>
                  </div> 
                  <!--START Users Conversations -->
                  <div id="conversations-div" class="conversations-list sidebar card-body" style="max-height:20rem; overflow-y:scroll;"></div>
                  <!--END -->
                </div>
              </div>
          </div>
          <!-- Sidebar End -->
          <!-- Chatbox start -->
          <div class="col-9">
            <div class="wrapper">
              <section class="chat-area">
                <header>
                  <?php
                    $chatid = $conn->real_escape_string($_GET['chatid']);
                    $idSql="SELECT * FROM users_profile WHERE email = '$chatid'";
                    $idArray = array();
                    $idQuery = $conn->query($idSql);
                    while($idRow = $idQuery->fetch_assoc()) {
                        $idArray = $idRow;
                    }
                    $imageId = $idArray['pub_id'];
                    $imageSql="SELECT * FROM public_record WHERE pub_id = '$imageId'";
                    $imageArray = array();
                    $imageQuery = $conn->query($imageSql);
                    while($imageRow = $imageQuery->fetch_assoc()) {
                        $imageArray = $imageRow;
                    }
                    $imageArray = explode(',', $imageArray['pub_img']);
                    $sql = $conn->query("SELECT * FROM users_profile WHERE email = '$chatid'");
                    if($sql->num_rows > 0) {
                      $row = $sql->fetch_assoc();
                    } else {
                      header("location: index.php?min-age=18&max-age=70&sexSelect=Everything&btnSubmit=SUBMIT");
                    }
                  ?>
                  <a href="index.php?min-age=18&max-age=70&sexSelect=Everything&btnSubmit=SUBMIT" class="back-icon"><i class="fas fa-arrow-left"></i></a>
                  <img class="img-fluid rounded-circle z-depth-2" alt="" src="images/<?php echo $imageArray[0];?>" data-holder-rendered="true">
                  <div class="details">
                    <span><?php echo $row['first_name']. " " . $row['last_name'] ?></span>
                  </div>
                </header>
                <div class="chat-box">
                </div>
                <form action="#" class="typing-area">
                  <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $chatid; ?>" hidden>
                  <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
                  <button><i class="fab fa-telegram-plane"></i></button>
                </form>
              </section>
            </div>    
          </div>
        <!-- Chatbox end -->
        </div>

  <script src="chat.js"></script>
  <script src="conversations.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</body>
</html>
