<?php
    session_start();
    require_once('dbconfig.php');
    $lang = $_SESSION['lang'];
    $id = $_SESSION['adminsession'];   
    if($id == null){
        header('Location: admin.php');
    }
    // if(isset($_POST['qusdelete'])){
    //     $test = $_SESSION['test'];
    //     $testtitle = $lang.' '.$test;
    //     $qid = $_POST['delval'];
    //     if(mysqli_query($conn,"DELETE FROM `$testtitle` WHERE q_id = '$qid'")){
    //         header("Location: viewquestions.php?test=$test");
    //     }
        
    // }
    // if(isset($_POST['testdelete'])){
    //     $test = $_POST['dropval'];
    //     $testtitle = $lang.' '.$test;
    //     if(mysqli_query($conn,"DROP TABLE `$testtitle`") && mysqli_query($conn,"DELETE FROM `$lang` WHERE tests = '$test'")){
    //         header("Location: viewtests.php?testlang=$lang");
    //     }
    //     else{
    //         echo "error ".mysqli_error($conn);
    //     }
    // }
    if(isset($_GET['settest'])){
        $sub = $_GET['lang2'];
        $test = $_GET['test2'];
                $testtitle = $sub.' '.$test;
                // INSERT INTO `set_test` (`test`) VALUES ('5443');
                // echo $testtitle;

                if(mysqli_query($conn,"INSERT INTO set_test(selected_subject, selected_test) VALUES ('$sub', '$test'); ")){
                    header("Location: adminmenu.php");
                }
                else{
                    echo "error ".mysqli_error($conn);
                }
                
            }
?>