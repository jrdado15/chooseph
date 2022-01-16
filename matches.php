<?php
    session_start();
    if(isset($_SESSION['userid'])) {
        include_once "dbconfig.php";
        $userid = $_SESSION['userid'];
        $sql = "SELECT * FROM match_record WHERE unique_id2 = '$userid' AND match_status = 'unmatched'";
        //Gets your matches list
        $query = $conn->query($sql);
        $output = "";
        if($query->num_rows == 0) {
            $output .= "No matches";
        } elseif($query->num_rows > 0) {
            $counter = 0;
            while($row = $query->fetch_assoc()) {
                $matchedUserEmail = $row['unique_id1'];
                $sql2 = "SELECT * FROM users_profile WHERE email = '$matchedUserEmail'";
                $query2 = $conn->query($sql2);
                if(($row2 = $query2->fetch_assoc()) > 0) {
                    $pub_id = $row2['pub_id'];
                    $sql3 = "SELECT * FROM public_record WHERE pub_id = '$pub_id'";
                    $query3 = $conn->query($sql3);
                    if(($row3 = $query3->fetch_assoc()) > 0) {
                        $publicName = $row3['pub_name'];
                        $publicDesc = $row3['pub_desc'];
                        $imageArray = explode(',', $row3['pub_img']);
                    }
                }
                //Echoes this html code to index
                $output .= '
                <div class="sidebar-item">
                <hr>
                <a onclick="matchClicked('.$counter.')" data-bs-toggle="modal" data-bs-target="#matchesModal">
                    <div class="row align-items-center mb-3">
                        <div class="col-4">
                            <span class="img-responsive">
                                <img class="img-fluid rounded-circle z-depth-2" alt="" src="images/' . $imageArray[0] . '" data-holder-rendered="true">
                            </span>
                        </div>
                        <div class="col-8">  
                            <input type="hidden" id="matchesEmail'.$counter.'" value="'. $matchedUserEmail .'">
                            <input type="hidden" id="matchesPublicName'.$counter.'" value="'. $publicName .'">
                            <input type="hidden" id="matchesPublicImage'.$counter.'" value="'. $row3['pub_img'] .'">
                            <input type="hidden" id="matchesPublicDesc'.$counter.'" value="'. $publicDesc .'">
                            <h4 class="h4">'. $row2['first_name'] .'</h4>
                        </div>
                    </div>
                </a>
                <hr>
                </div>';
                $counter++;
            }
        }
    }
    echo $output;
?>