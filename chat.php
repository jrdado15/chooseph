<?php
  session_start();
  include_once "dbconfig.php";
  if(!isset($_SESSION['userid'])) {
    header("location: login.php");
    exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>ChoosePH</title>
  <link rel="stylesheet" href="chat.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>
<body>
  <div class="wrapper">
    <section class="chat-area">
      <header>
        <?php
          $chatid = $conn->real_escape_string($_GET['chatid']);
          $sql = $conn->query("SELECT * FROM users_profile WHERE email = '$chatid'");
          if($sql->num_rows > 0) {
            $row = $sql->fetch_assoc();
          } else {
            header("location: index.php");
          }
        ?>
        <a href="index.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <img class="img-fluid rounded-circle z-depth-2" alt="100x100" src="https://mdbootstrap.com/img/Photos/Avatars/img%20(31).jpg" data-holder-rendered="true">
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
  <script src="chat.js"></script>
</body>
</html>
