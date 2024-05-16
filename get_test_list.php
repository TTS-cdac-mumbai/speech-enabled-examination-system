<?php
session_start();
require_once('dbconfig.php');

$data = $_POST['myData'];
$subject= $data['subject'];
$res3 = mysqli_query($conn, "SELECT * FROM `$subject`");
$tests = array();
if (mysqli_num_rows($res3) > 0) {
    while ($row3 = mysqli_fetch_assoc($res3)) {
        // array_push($tests, $row3["tests"]);
        array_push($tests, $row3["tests"] . " " .$row3["lang_medium"]);
    }
    error_log("TESTS . $tests");
    echo json_encode($tests);
}
?>