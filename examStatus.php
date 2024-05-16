<?php
session_start();
require_once('dbconfig.php');

$lang = $_SESSION['lang'];
$test = $_SESSION['test'];
$medium = $_SESSION["medium"];

$id = $_SESSION['usersession'];
$_SESSION['userid'] = $id;

$res = mysqli_query($conn, "SELECT q_id FROM `result` WHERE user_id = '$id' and lang = '$lang' and  medium = '$medium' and test = '$test'and ans_status = 'skip'");
if (mysqli_num_rows($res) > 0) {   
    $i = 0;
	$skip = array();                
    while($skip = mysqli_fetch_assoc($res)){
        $statusSkip[$i] = $skip['q_id'];
        $i++;
    }
}

$res = mysqli_query($conn, "SELECT q_id FROM `result` WHERE user_id = '$id' and lang = '$lang' and  medium = '$medium' and test = '$test'and ans_status = 'attempted'");
if (mysqli_num_rows($res) > 0) {   
    $i = 0;
	$attempted = array();                
    while($attempted = mysqli_fetch_assoc($res)){
        $statusAttempted[$i] = $attempted['q_id'];
        $i++;
    }
}

$res = mysqli_query($conn, "SELECT q_id FROM `result` WHERE user_id = '$id' and lang = '$lang' and  medium = '$medium' and test = '$test'and ans_status = 'review'");
if (mysqli_num_rows($res) > 0) {   
    $i = 0;
	$review = array();                
    while($review = mysqli_fetch_assoc($res)){
        $statusReview[$i] = $review['q_id'];
        $i++;
    }
}

$res = mysqli_query($conn, "SELECT q_id FROM `result` WHERE user_id = '$id' and lang = '$lang' and  medium = '$medium' and test = '$test'and ans_status = 'completed'");
if (mysqli_num_rows($res) > 0) {   
    $i = 0;
	$completed = array();                
    while($completed = mysqli_fetch_assoc($res)){
        $statusCompleted[$i] = $completed['q_id'];
        $i++;
    }
}

$status = array('attempted' => $statusAttempted, 'skip' => $statusSkip, 'review' => $statusReview, 'completed' => $statusCompleted );

echo json_encode($status);
?>