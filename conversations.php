<?php
    session_start();
    if(isset($_SESSION['userid'])) {
        include_once "dbconfig.php";
        $userid = $_SESSION['userid'];
        $sql = "SELECT * FROM match_record WHERE (unique_id1 = '$userid' OR unique_id2 = '$userid') AND match_status = 'matched'";
        $query = $conn->query($sql);
        $output = "";
        if($query->num_rows == 0) {
            $output .= "No conversations";
        } elseif($query->num_rows > 0) {
            while($row = $query->fetch_assoc()) {
                if ($row['unique_id1'] == $_SESSION['userid']){
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
                    $sql2 = "SELECT * FROM users_profile WHERE email = '$matched_user'";
                    $query2 = $conn->query($sql2);
                    while($row2 = $query2->fetch_assoc()) {
                        $output .= '<a href="chat.php?chatid='. $row2['email'] .'">
                                        <div class="row align-items-center mb-3">
                                            <div class="col-4">
                                                <span class="img-responsive">
                                                    <img class="img-fluid rounded-circle z-depth-2" alt="" src="images/' . $imageArray[0] . '" data-holder-rendered="true">
                                                </span>
                                            </div>
                                            <div class="col-8">  
                                                <h4 class="h4">'. $row2['first_name'] .'</h4>
                                            </div>
                                        </div>
                                    </a>';
                    } 
                } elseif ($row['unique_id2'] == $_SESSION['userid']){
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
                    $sql2 = "SELECT * FROM users_profile WHERE email = '$matched_user'";
                    $query2 = $conn->query($sql2);
                    while($row2 = $query2->fetch_assoc()) {
                        $output .= '
                        <div class="sidebar-item">
                            <hr>
                            <a href="chat.php?chatid='. $row2['email'] .'">
                                        <div class="row align-items-center mb-3">
                                            <div class="col-4">
                                                <span class="img-responsive">
                                                    <img class="img-fluid rounded-circle z-depth-2" alt="" src="images/' . $imageArray[0] . '" data-holder-rendered="true">
                                                </span>
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