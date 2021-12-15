<?php
    session_start();
    if(isset($_SESSION['userid'])) {
        include_once "dbconfig.php";
        $userid = $_SESSION['userid'];
        $sql = "SELECT * FROM match_record WHERE unique_id2 = '$userid' AND match_status = 'unmatched'";
        $query = $conn->query($sql);
        $output = "";
        if($query->num_rows == 0) {
            $output .= "No matches";
        } elseif($query->num_rows > 0) {
            while($row = $query->fetch_assoc()) {
                if ($row['unique_id1'] == $_SESSION['userid']){
                    $matched_user = $row['unique_id2'];
                    $sql2 = "SELECT * FROM users_profile WHERE email = '$matched_user'";
                    $query2 = $conn->query($sql2);

                    $email = $matched_user;
                    $sql3 = "SELECT * FROM users_profile WHERE email = '$email'";
                    $query3 = $conn->query($sql3);
                    if(($row3 = $query3->fetch_assoc()) > 0) {
                        $pub_id = $row3['pub_id'];
                        $sql4 = "SELECT * FROM public_record WHERE pub_id = '$pub_id'";
                        $query4 = $conn->query($sql4);
                        if(($row4 = $query4->fetch_assoc()) > 0) {
                            $_SESSION['pub_name'] = $row4['pub_name'];
                            $_SESSION['pub_desc']  = $row4['pub_desc'];
                        }
                    }

                    while($row2 = $query2->fetch_assoc()) {
                        $output .= '<a onclick="matchClicked()" data-bs-toggle="modal" data-bs-target="#matchesModal">
                                        <div class="row align-items-center mb-3">
                                            <div class="col-4">
                                                <span class="img-responsive">
                                                    <img class="img-fluid rounded-circle z-depth-2" alt="100x100" src="https://mdbootstrap.com/img/Photos/Avatars/img%20(31).jpg" data-holder-rendered="true">
                                                </span>
                                            </div>
                                            <div class="col-8">  
                                                <input type="hidden" id="matchesEmail" value="'. $row2['email'] .'">
                                                <input type="hidden" id="matchesPublicName" value="'. $row4['pub_name'] .'">
                                                <input type="hidden" id="matchesPublicDesc" value="'. $row4['pub_desc'] .'">
                                                <h4 class="h4">'. $row2['first_name'] .'</h4>
                                            </div>
                                        </div>
                                    </a>';
                                    
                    }  
                    
                } elseif ($row['unique_id2'] == $_SESSION['userid']){
                    $matched_user = $row['unique_id1'];
                    $sql2 = "SELECT * FROM users_profile WHERE email = '$matched_user'";
                    $query2 = $conn->query($sql2);

                    $email = $matched_user;
                    $sql3 = "SELECT * FROM users_profile WHERE email = '$email'";
                    $query3 = $conn->query($sql3);
                    if(($row3 = $query3->fetch_assoc()) > 0) {
                        $pub_id = $row3['pub_id'];
                        $sql4 = "SELECT * FROM public_record WHERE pub_id = '$pub_id'";
                        $query4 = $conn->query($sql4);
                        if(($row4 = $query4->fetch_assoc()) > 0) {
                            $_SESSION['pub_name'] = $row4['pub_name'];
                            $_SESSION['pub_desc']  = $row4['pub_desc'];
                        }
                    }

                    while($row2 = $query2->fetch_assoc()) {
                        $output .= '<a onclick="matchClicked()" data-bs-toggle="modal" data-bs-target="#matchesModal">
                                        <div class="row align-items-center mb-3">
                                            <div class="col-4">
                                                <span class="img-responsive">
                                                    <img class="img-fluid rounded-circle z-depth-2" alt="100x100" src="https://mdbootstrap.com/img/Photos/Avatars/img%20(31).jpg" data-holder-rendered="true">
                                                </span>
                                            </div>
                                            <div class="col-8">  
                                                <input type="hidden" id="matchesEmail" value="'. $row2['email'] .'">
                                                <input type="hidden" id="matchesPublicName" value="'. $row4['pub_name'] .'">
                                                <input type="hidden" id="matchesPublicDesc" value="'. $row4['pub_desc'] .'">
                                                <h4 class="h4">'. $row2['first_name'] .'</h4>
                                            </div>
                                        </div>
                                    </a>';
                                    
                    }  
                }
                 
            }
        }
    }
    echo $output;
?>