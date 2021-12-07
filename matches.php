<?php
    session_start();
    if(isset($_SESSION['userid'])) {
        include_once "dbconfig.php";
        $userid = $_SESSION['userid'];
        $sql = "SELECT * FROM match_record WHERE unique_id1 = '$userid'";
        $query = $conn->query($sql);
        $output = "";
        if($query->num_rows == 0) {
            $output .= "No matches";
        } elseif($query->num_rows > 0) {
            while($row = $query->fetch_assoc()) {
                $matched_user = $row['unique_id2'];
                $sql2 = "SELECT * FROM users_profile WHERE email = '$matched_user'";
                $query2 = $conn->query($sql2);
                while($row2 = $query2->fetch_assoc()) {
                    $output .= '<div class="row align-items-center mb-3">
                                    <div class="col-4">
                                        <span class="img-responsive">
                                            <img class="img-fluid rounded-circle z-depth-2" alt="100x100" src="https://mdbootstrap.com/img/Photos/Avatars/img%20(31).jpg" data-holder-rendered="true">
                                        </span>
                                    </div>
                                    <div class="col-8">  
                                        <h4 class="h4">'. $row2['first_name'] .'</h4>
                                    </div>
                                </div>';
                }   
            }
        }
    }
    echo $output;
?>