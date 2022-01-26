<?php
    session_start();
    if(isset($_SESSION['userid'])) {
        include_once "dbconfig.php";
        $userid = $_SESSION['userid'];
        $sql = "SELECT * FROM match_record WHERE (unique_id1 = '$userid' OR unique_id2 = '$userid') AND match_status = 'matched'";
        //Gets your conversations list
        $query = $conn->query($sql);
        $output = "";
        if($query->num_rows == 0) {
            $output .= "No conversations";
        } elseif($query->num_rows > 0) {
            while($row = $query->fetch_assoc()) {
                if ($row['unique_id1'] == $_SESSION['userid']){ //If you are the one who has matched
                    $matched_user = $row['unique_id2'];
                    $idSql="SELECT * FROM users_profile WHERE email = '$matched_user'";
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
                    $matchSql = "SELECT * FROM users_profile WHERE email = '$matched_user'";
                    $matchQuery = $conn->query($matchSql);
                    while($row2 = $matchQuery->fetch_assoc()) {
                        //Echoes this html code to index
                        $output .= '
                        <div class="sidebar-item">
                        <a href="chat.php?chatid='. $row2['email'] .'">
                                        <div class="row align-items-center mb-3">
                                            <div class="col-4">
                                                    <img class="ml-4" style="height: 55px; width: 55px; object-fit:cover; border-radius: 50%;" alt="" src="images/' . $imageArray[0] . '" data-holder-rendered="true">
                                            </div>
                                            <div class="col-8">  
                                                <h4 class="h4 ml-3">'. $row2['first_name'] .'</h4>
                                            </div>
                                        </div>
                                    </a>
                        </div>
                        ';
                    } 
                } elseif ($row['unique_id2'] == $_SESSION['userid']){ //If you are the one who has been matched
                    $matched_user = $row['unique_id1'];
                    $idSql="SELECT * FROM users_profile WHERE email = '$matched_user'";
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
                    $matchSql = "SELECT * FROM users_profile WHERE email = '$matched_user'";
                    $matchQuery = $conn->query($matchSql);
                    while($row2 = $matchQuery->fetch_assoc()) {
                        //Echoes this html code to index
                        $output .= '
                        <div class="sidebar-item">
                            <hr>
                            <a href="chat.php?chatid='. $row2['email'] .'">
                                        <div class="row align-items-center mb-3">
                                            <div class="col-4">
                                              <img class="ml-4" style="height: 50px; border-radius: 10%;" alt="" src="images/' . $imageArray[0] . '" data-holder-rendered="true">    
                                            </div>
                                            <div class="col-8">  
                                                <h4 class="h4">'. $row2['first_name'] .'</h4>
                                            </div>
                                        </div>
                                    </a>
                            <hr>
                        </div>
                        '
                                    
                                    ;
                    } 
                }
                  
            }
        }
    }
    echo $output;
?>