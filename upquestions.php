<?php
session_start();
    require_once('dbconfig.php');
    $lang = $_SESSION['lang'];
    $test = $_SESSION['test'];
    $medium = $_SESSION['medium'];
    if(isset($_POST['upques'])){
        $guide = $_POST['guidelines'];
        $ques = $_POST['title'];
        $id = $_POST['qid'];
        $o1 = $_POST['option1'];
        $o2 = $_POST['option2'];
        $o3 = $_POST['option3'];
        $o4 = $_POST['option4'];
        $o5 = $_POST['option5'];
        $ans = $_POST['answer'];
        $marks = $_POST['marks'];
        $qno = $_POST['qno'];
        $testtitle = $lang.' '.$test .' '.$medium;
        $explain = $_POST['explain'];
        if(mysqli_query($conn,"UPDATE `$testtitle` SET guidelines='$guide', q_no='$qno', questions='$ques',option1='$o1',option2='$o2',option3='$o3',option4='$o4',option5='$o5',answer='$ans',exp='$explain',marks='$marks' WHERE q_id = '$id'")){
            header("Location: viewquestions.php?test=$test&medium=$medium");
        }
        else{
            echo "error".mysqli_error($conn);
        }
    }
?>