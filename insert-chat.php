<?php
    session_start();
    if(isset($_SESSION['userid'])) {
        include_once "dbconfig.php";
        $outgoing_id = $_SESSION['userid'];
        $incoming_id = $conn->real_escape_string($_POST['incoming_id']);
        $message = $conn->real_escape_string($_POST['message']);
        if(!empty($message)) {
            $sql = $conn->query("INSERT INTO message_record (incoming_msg_id, outgoing_msg_id, msg) VALUES ('$incoming_id', '$outgoing_id', '$message')") or die();
        }
    } else {
        header("location: login.php");
    } 
?>