<?php 
include_once 'dbconfig.php';
require_once 'admin_controller.php';
//Load user's list from database 
$accountArray = array();
$sql = "SELECT * FROM ods_public_record ";
$result = mysqli_query($conn, $sql);
$index = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $accountArray[$index] = $row;
    $index++;
}

if (!isset($_SESSION['admin_id'])) {
    header('location: admin.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!--STYLES-->  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
</head>
<body>


    <div class="d-flex align-items-center justify-content-center h-100">
        <div class="card" style="width: 28rem;">
            <div class="card-body">
                    <div class="card-title h3"> Account List:</div>
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>Sex</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <!--Load SQL Data-->  
                                    <?php if(count($accountArray) > 0): ?>
                                                <?php for ($x = 0; $x < count($accountArray); $x++): ?>                          
                                                            <tr>
                                                                <th><?php echo $accountArray[$x]['pub_name']; ?></th>
                                                                <th><?php echo $accountArray[$x]['pub_age']; ?></th>
                                                                <th><?php echo $accountArray[$x]['pub_sex']; ?></th>
                                                            </tr>
                                                    </div>
                                                <?php endfor; ?>
                                            <?php endif; ?>
                                    </table>
                                <!--End SQL Data-->     
                                </tbody>                   
                    <div class="row mt-5">
                            <a class="btn btn-primary ml-3" style="width: 200px;" href="userlist.php">Download as PDF <i class="fas fa-file-pdf"></i></a> 
                        <form action="admin.php" method="get">
                            <button class="btn btn-primary ml-3" style="width: 100px;" name="logout">Logout</button>  
                        </form>
                    </div> 
            </div>
        </div>
    </div>

    

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>