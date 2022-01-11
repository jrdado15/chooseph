<?php
session_start();
if(isset($_SESSION['userid'])) {
    $output = '';
    $imageIndex = $_GET['num'];
    //dsa
    include_once 'dbconfig.php';
    
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

    $sql5 = "SELECT * FROM users_profile WHERE email = '$email'";
    $query5 = $conn->query($sql5);
    while($row5 = $query5->fetch_assoc()) {
        $rotationNum = $row5['rotation'];
    }

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
                        $sql2 = "SELECT * FROM users_profile WHERE pub_id = '$id'";
                        $query2 = $conn->query($sql2);
                        if(($row2 = $query2->fetch_assoc()) > 0) {
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
                    //dito pupunta pag walang data
                    $output = "";
                } else {
                    $image_array = array();
                    $image_array = explode(',', $curr_data[$rotationNum]['pub_img']);
                }
            } else {
                //dito pupunta pag walang nakita using filters (age range and sex)
                $output = "";
            }
        } else {
            $output = $conn->error;
        }
    }
    //dsa
    if ($imageIndex == 1){ // first picture
    $output .=  '
                <img src="images/'. $image_array[0] .'" id="img1" class="matches-picture" alt="..." >
                ';
    } elseif ($imageIndex == 2){ // second picture
    $output .=  '
                <img src="images/'. $image_array[1] .'" id="img1" class="matches-picture" alt="..." >
                ';
    } elseif ($imageIndex == 3){ // third picture
    $output .=  '
                <img src="images/'. $image_array[2] .'" id="img1" class="matches-picture" alt="..." >
                ';
    } elseif ($imageIndex == 4){ // fourth picture
    $output .=  '
                <img src="images/'. $image_array[3] .'" id="img1" class="matches-picture" alt="..." >
                ';
    }

    echo $output;
}
?>