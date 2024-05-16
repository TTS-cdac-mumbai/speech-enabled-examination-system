<?php
session_start();
    require_once('dbconfig.php');
    $lang = $_SESSION['lang'];
    if(isset($_POST['newtest'])){
        $test = htmlspecialchars(strtolower($_POST['title']));
        $guide = "";
        $lang_medium = $_POST['lang_medium'];
        $addtest = $lang.' '.$test.' '.$lang_medium;
        $timelimit = $_POST['testtime'];
        $maxmarks = $_POST['maxmarks'];
        $exam_pattern = htmlspecialchars($_POST['exam_pattern']);

        

        $time = time();
        $test_id = "test_".$time;
        $test_key = substr(bin2hex(random_bytes(6)), 0, 6);
        $res = mysqli_query($conn,"SELECT * FROM `$lang` WHERE tests = '$test' and lang_medium = '$lang_medium'");
        if(mysqli_num_rows($res) > 0){
            header("Location: viewtests.php?testlang=$lang&error");
        }
        else{
            if(mysqli_query($conn,"CREATE TABLE `$addtest` (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,q_id VARCHAR(50),guidelines TEXT,q_no VARCHAR(10),questions TEXT,option1 VARCHAR(100),option2 VARCHAR(100),option3 VARCHAR(100),option4 VARCHAR(100),option5 VARCHAR(100),answer TEXT,exp TEXT,q_type VARCHAR(50),marks INT(3))") && mysqli_query($conn,"INSERT INTO `$lang`(t_id,tests,testtime,eval_key,total_marks,lang_medium,exam_pattern) VALUES('$test_id','$test','$timelimit','$test_key','$maxmarks','$lang_medium','$exam_pattern')")){
                header("Location: viewtests.php?testlang=$lang");
            }
            else{
                echo "error".mysqli_error($conn);
            }
        }
    }
?>
