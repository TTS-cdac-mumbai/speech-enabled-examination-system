
<?php
session_start();
require_once('dbconfig.php');
// $obj = $_POST['userid'];
$lang = $_SESSION['lang'];
// $id = $_SESSION['usersession'];
// $userid = $_GET['userid'];

$marks_obtained = $_POST["marks_obtained"];
// $audio_src =  $_POST["audio_src"];
$q_id = $_POST["q_id"];
$userid = $_POST["user_id"];

error_log("RECEIVED : " . $userid . $lang . $marks_obtained . $q_id);
// $gender = $obj["audio_url"];

$sql = "UPDATE result SET marks ='$marks_obtained' where q_id ='$q_id' and user_id = '$userid'";

if (mysqli_query($conn, $sql)) {
    error_log("New record created successfully");
   } else {
    error_log( "Error: " . mysqli_error($conn));
   }


?>
