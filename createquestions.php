<?php
session_start();
    require_once('dbconfig.php');
    $test = $_SESSION['test'];
    $lang = $_SESSION['lang'];
    $medium = $_SESSION['medium'];
    if(isset($_POST['newques'])){
        $guide = $_POST['guidelines'];
        $ques = $_POST['title'];
        $time = time();
        $ques_id = "question_".$time;
        $q_type = $_POST['q_type'];
        $o1 = $_POST['option1'];
        $o2 = $_POST['option2'];
        $o3 = $_POST['option3'];
        $o4 = $_POST['option4'];
        $o5 = $_POST['option5'];
        $ans = $_POST['answer'];
        $explain = $_POST['explain'];
        $marks = $_POST['marks'];
        $qno = $_POST['qno'];
        $addtest = $lang.' '.$test.' '.$medium;
        $res = mysqli_query($conn,"SELECT * FROM `$test` WHERE questions = '$ques'");
        if(mysqli_num_rows($res) > 0){
            header("Location: viewquestions.php?test=$test&error");
        }
        else{
            if(mysqli_query($conn,"INSERT INTO `$addtest`(q_id,guidelines,q_no,questions,option1,option2,option3,option4,option5,answer,exp,q_type,marks) VALUES('$ques_id','$guide','$qno','$ques','$o1','$o2','$o3','$o4','$o5','$ans','$explain','$q_type','$marks')")){
                header("Location: viewquestions.php?test=$test&medium=$medium");
            }
            else{
                echo "error".mysqli_error($conn);
            }
        }
    }
?>

