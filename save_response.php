
<?php
session_start();
require_once('dbconfig.php');
// $obj = $_POST['userid'];
$lang = $_SESSION['lang'];
$test = $_SESSION['test'];
$medium = $_SESSION["medium"];

$id = $_SESSION['usersession'];
$_SESSION['userid'] = $id;

$question_id = $_POST["question_id"];
$question = $_POST["question"];
$option =  $_POST["option"];
$new_audio =  $_POST["filename"];
$obj_marks = $_POST["obj_marks"];

echo ($question);
error_log("RECEIVED : " . $id . $test . $question_id . $lang . $question . $option);
// $gender = $obj["audio_url"];


$testtitle = $lang . ' ' . $test . ' ' .$medium ;

$res2 = mysqli_query($conn, "SELECT answer, option1, option2, option3, option4, option5, marks, q_type FROM `$testtitle` where q_id = '$question_id' ");
if (mysqli_num_rows($res2) > 0) {
$row = mysqli_fetch_array($res2);

    $marks = 0;
    if($row[0] != ""){
    error_log($row[0]);
    $correct_ans = "";
    if ($row[0] == "A")
        $correct_ans = $row[1];
    if ($row[0] == "B")
        $correct_ans = $row[2];
    if ($row[0] == "C")
        $correct_ans = $row[3];
    if ($row[0] == "D")
        $correct_ans = $row[4];
    if ($row[0] == "E")
        $correct_ans = $row[5];
    if ($correct_ans == $option)
        $marks = $row[6];
    }
}
        

$res3 = mysqli_query($conn, "SELECT audio FROM result WHERE user_id = '$id' AND q_id = '$question_id'");
if (mysqli_num_rows($res3) > 0) {
    $old_audio = mysqli_fetch_array($res3);
    error_log($old_audio[0]);

    $audio = $old_audio[0].",". $new_audio;
    
    error_log($audio);
}else{
    $audio = $new_audio;
}



if( isset($_POST["status"]) )
{
    $status = $_POST["status"];
    $sql = "UPDATE result SET ans_status = '$status' where q_id = '$question_id' AND user_id = '$id' ";

}else{
$sql = "INSERT INTO result (user_id, lang, test, medium, q_id, answer, audio, ans_status, marks ) VALUES('$id', '$lang', '$test', '$medium', '$question_id', '$option', '$audio', 'attempted', '$marks') 
ON DUPLICATE KEY UPDATE answer = '$option', audio = '$audio', marks = '$marks', ans_status = 'attempted' ";

}



if (mysqli_query($conn, $sql)) {
    error_log("New record created successfully");
   } else {
    error_log( "Error: " . mysqli_error($conn));
    
   }

?>
