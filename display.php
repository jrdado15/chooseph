<?php
session_start();
if(isset($_SESSION['userid'])) {
    include_once 'dbconfig.php';

    //set variables
    $minAge = $_GET['minAge'];
    $maxAge = $_GET['maxAge'];
    $sex = $_GET['sex'];
    $email = $_SESSION['userid'];
    $output = '';
    $curr_id;
    $rotationNum;
    $curr_data = array();
    $likedUsersEmail = array();
    $likedUsersID = array();

    //get rotation number
    $idSql = "SELECT * FROM users_profile WHERE email = '$email'";
    $idQuery = $conn->query($idSql);
    while($row5 = $idQuery->fetch_assoc()) {
        $rotationNum = $row5['rotation'];
    }

    //get email of every user who liked or were liked by the user
    $matchSql = "SELECT * FROM match_record WHERE unique_id1 = '$email' OR unique_id2 = '$email'";
    $matchQuery = $conn->query($matchSql);
    $counter = 0;
    while($row3 = $matchQuery->fetch_assoc()) {
        if ($email == $row3['unique_id1']){
            $likedUsersEmail[$counter] = $row3['unique_id2'];
        } elseif ($email == $row3['unique_id2']){
            $likedUsersEmail[$counter] = $row3['unique_id1'];
        }
        $counter++;
    }
    $counter = 0;

    //get every liked email's public id from users_profile and store into an array
    foreach ($likedUsersEmail as $likedEmail) {
        $likeSql = "SELECT * FROM users_profile WHERE email = '$likedEmail'";
        $likeQuery = $conn->query($likeSql);
        if(($row4 = $likeQuery->fetch_assoc()) > 0) {
            $likedUsersID[$counter] = $row4['pub_id'];
            $counter++;
        }
    }
    
    //get everyone where it fits the filter (age, sex), is not liked by the user, and did not like the user
    $emailSql = "SELECT * FROM users_profile WHERE email = '$email'";
    $emailQuery = $conn->query($emailSql);
    if(($row0 = $emailQuery->fetch_assoc()) > 0) {
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
                        //do nothing
                    } else {
                        $curr_data[] = $row;
                        $idSql2 = "SELECT * FROM users_profile WHERE pub_id = '$id'";
                        $idQuery2 = $conn->query($idSql2);
                        if(($row2 = $idQuery2->fetch_assoc()) > 0) {
                            if ($counter == 0){
                                $curr_id = $curr_data[0]['pub_id'];
                            }
                            $curr_data[$counter]['pub_id'] = $row2['email'];
                            $counter++;
                        }
                    }
                }
                if ($counter <= $rotationNum){
                    $rotationNum  = $rotationNum - $counter;
                    $sql = "UPDATE users_profile SET rotation = $rotationNum WHERE email = '$email'";
                    if ($conn->query($sql)) { }
                }
                if ($counter == 0){
                    //if no data was found
                    $output .=  '
                                <div class="card">
                                    <div class="row m-3" >
                                    <!-- Profile Info Start -->
                                    <div class="col-12 d-flex align-items-center justify-content-center"  style="background-color:#0ba8d3;">
                                        <h5 id="person-age" class="h3 text-white"> No data found, try again tomorrow! </h5>   
                                    </div>
                                    <!-- Profile Info End -->
                                    </div>
                                </div>
                                ';
                } else {
                    //if one or more data is found
                    $output .=  '
                                <div>
                                    <input type="hidden" id="person-id" value="' . $curr_id . '">
                                    <input type="hidden" id="person-email" value="' . $curr_data[$rotationNum]['pub_id'] . '">
                                    <h5 id="person-name" class="card-title h2 text-white"> ' . $curr_data[$rotationNum]['pub_name'] . ' </h5>
                                    <div class="row justify-content-center h3 text-white">
                                        <h5 id="person-age" class="h3 text-white"> ' . $curr_data[$rotationNum]['pub_age'] . ' </h5>, 
                                        <h5 id="person-sex" class="ml-1 h3 text-white"> ' . $curr_data[$rotationNum]['pub_sex'] . ' </h5>
                                    </div>
                                    <p id="description" class="card-text h5 text-white"> ' . $curr_data[$rotationNum]['pub_desc'] . ' </p>
                                    <div class="row justify-content-center">
                                        <form name="form" method="GET" action="index.php">
                                            <button type="submit" class=" btn btn-info btn-light m-2 p-3 " value="PASS" name="passBtn0" href="#" onclick="passUser()" style="border-style: solid; border-color: #c8d6e5; border-radius: 50%;">
                                                <img src="images/cross.png" alt="" style="width: 50px;">
                                            </button>
                                            <button type="submit" class="btn btn-light m-2 p-3 " value="SMASH" name="smashBtn0" href="#" onclick="smashUser()" style="border-style: solid; border-color: #c8d6e5; border-radius: 50%;">
                                                <img src="images/heart.png" alt="" style="width: 50px;">
                                            </button>
                                        </form>
                                    </div>
                                </div>   
                                
                                ';
                }
            } else {
                //if no users left because of filter
                $output .=  '
                            <div class="card">
                                <div class="row m-3" >
                                <!-- Profile Info Start -->
                                <div class="col-12 d-flex align-items-center justify-content-center"  style="background-color:#0ba8d3;">
                                    <h5 id="person-age" class="h3 text-white"> No data found, try again tomorrow! </h5>   
                                </div>
                                <!-- Profile Info End -->
                                </div>
                            </div>
                            ';
            }
        } else {
            $output = $conn->error;
        }
    }
    
    echo $output;
}
?>