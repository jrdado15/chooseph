<?php
    session_start();
    if(isset($_SESSION['userid'])) {
        include_once "dbconfig.php";
        $outgoing_id = $_SESSION['userid'];
        $incoming_id = $conn->real_escape_string($_POST['incoming_id']);
        $output = "";
        $sql = "SELECT * FROM message_record LEFT JOIN users_profile ON users_profile.email = message_record.outgoing_msg_id WHERE (outgoing_msg_id = '$outgoing_id' AND incoming_msg_id = '$incoming_id') OR (outgoing_msg_id = '$incoming_id' AND incoming_msg_id = '$outgoing_id') ORDER BY msg_id";
        $query = $conn->query($sql);
        if($query->num_rows > 0) {
            while($row = $query->fetch_assoc()) {
                if($row['outgoing_msg_id'] === $outgoing_id) {
                    $output .= '<div class="chat outgoing">
                                    <div class="details">
                                        <p>'. $row['msg'] .'</p>
                                    </div>
                                </div>';
                } else {
                    $output .= '<div class="chat incoming">
                                    <div class="details">
                                        <p>'. $row['msg'] .'</p>
                                    </div>
                                </div>';
                }
            }
        } else {
            $output .= '<div class="text">No messages are available. Once you send message they will appear here.</div>';
        }
        echo $output;
    } else {
        header("location: login.php");
    }
?>