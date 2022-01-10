<?php
session_start();
include_once 'dbconfig.php';

$minAge = $_GET['minAge'];
$maxAge = $_GET['maxAge'];
$sex = $_GET['sex'];
$currEmail = $_GET['currEmail'];
$currID = $_GET['currID'];
$email = $_SESSION['userid'];
$output = '';
$curr_data = array();
$likedUsersEmail = array();
$likedUsersID = array();

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
    $implodedLikedUsersID = implode(", ",array_values($likedUsersID));
    if ($sex == "Everything"){
    $sql = "SELECT * FROM public_record WHERE pub_age BETWEEN $minAge AND $maxAge AND NOT pub_id = $userPubID AND pub_id NOT IN ('$implodedLikedUsersID')";
    } else {
    $sql = "SELECT * FROM public_record WHERE pub_sex='$sex' AND pub_age BETWEEN $minAge AND $maxAge AND NOT pub_id = $userPubID AND pub_id NOT IN ('$implodedLikedUsersID')";
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
            $sql2 = "SELECT * FROM users_profile WHERE pub_id = '$currID'";
            $query2 = $conn->query($sql2);
            if(($row2 = $query2->fetch_assoc()) > 0) {
                $curr_data[$counter]['pub_id'] = $row2['email'];
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

$output .=  '
            <div class="card">
                <div class="row m-3" >
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
                        <div class="carousel-item active">
                        <img src="" id="img1" class="matches-picture" alt="..." >
                        </div>
                        <div class="carousel-item">
                        <img src="" id="img2" class="matches-picture" alt="..." >
                        </div>
                        <div class="carousel-item">
                        <img src="" id="img3" class="matches-picture" alt="..." >
                        </div>
                        <div class="carousel-item">
                        <img src="" id="img4" class="matches-picture" alt="..." >
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
                <div class="col-6 d-flex align-items-center justify-content-center"  style="background-color:#0ba8d3;">
                    <div>
                    <input type="hidden" id="person-id" value="' . $currID . '">
                    <input type="hidden" id="person-email" value="' . $curr_data[0]['pub_id'] . '">
                    <h5 id="person-name" class="card-title h2 text-white"> ' . $curr_data[0]['pub_name'] . ' next </h5>
                    <div class="row justify-content-center h3 text-white">
                    <h5 id="person-age" class="h3 text-white"> ' . $curr_data[0]['pub_age'] . ' </h5>, 
                    <h5 id="person-sex" class="ml-1 h3 text-white"> ' . $curr_data[0]['pub_sex'] . ' </h5>
                    </div>
                    <p id="description" class="card-text h5 text-white"> ' . $curr_data[0]['pub_desc'] . ' </p>
                    <div class="row">
                        <button type="submit" class=" btn btn-info btn-light m-2 p-3 " value="PASS" name="passBtn0" href="#" onclick="passUser()" style="border-style: solid; border-color: #c8d6e5; border-radius: 50%;">
                            <img src="images/cross.png" alt="" style="width: 50px;">
                            </button>
                        <form name="form" method="GET" action="index.php">
                            <button type="submit" class="btn btn-light m-2 p-3 " value="SMASH" name="smashBtn0" href="#" onclick="smashUser()" style="border-style: solid; border-color: #c8d6e5; border-radius: 50%;">
                            <img src="images/heart.png" alt="" style="width: 50px;">
                            </button>
                        </form>
                    </div>
                    </div>     
                </div>
                <!-- Profile Info End -->
                </div>
            </div>
            ';

echo $output;
?>