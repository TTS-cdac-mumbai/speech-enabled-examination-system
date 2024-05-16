<?php
session_start();
require_once('dbconfig.php');
$id = $_SESSION['adminsession'];
$lang = $_SESSION['lang'];
$test = $_SESSION['test'];


if ($id == null) {
    header('Location: admin.php');
}
if (isset($_GET['userid'])) {
    // $test = $_GET['test'];
    $userid = $_GET['userid'];
    $medium = $_GET['medium'];
    $_SESSION['adminsession'] = $id;
    $_SESSION['lang'] = $lang;
    $_SESSION['test'] = $test;
?>

    <!DOCTYPE html>
    <html>

    <head><?php
        echo "<title>". $userid . "_" . $lang. "_" .$test."</title>"; ?>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="main.css">
        <link rel="stylesheet" type="text/css" href="css/font/flaticon.css">
        <link href="https://fonts.googleapis.com/css?family=Fira+Sans|Josefin+Sans" rel="stylesheet">
        <meta charset="UTF-8">
        <meta name="description" content="Online Exam">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </head>

    <body>
        <br>
            <div class="container-fluid">
                <div class="row">
                    <div  class="text-center">
                        <img src="images/quiz_text_black.png" class="oq-logo" width="300px" height="80px">
                    </div>
                  
                </div>
            </div>
       
        <hr>
        <div >
            <div class="container-fluid">
                <div class="row">
                  
                        <div class="oq-viewTests">
                            
                                <h3 class="text-center"><b><?php echo ucfirst($lang) ?></b> </h3>
                               
                                <h3 class="text-center"><?php echo   ucfirst($test) . " " . ucfirst($medium)   ?></h3>
                               
                                <h4 class="text-center"><?php echo "Username: " . ucfirst($userid); ?></h4>
                           
                            <br><span>List of questions answers are shown below:</span>

                            <table class="table oq-table">
                                <tbody>

                                    <?php
                                    
                                    $res = mysqli_query($conn, "SELECT * FROM result where user_id = '$userid' AND lang = '$lang' AND test = '$test' AND medium = '$medium' ");
                                    if ($res) {
                                        if (mysqli_num_rows($res) > 0) {
                                            $i = 1;

                                            while ($result_row = mysqli_fetch_assoc($res)) {

                                                $lang = nl2br($result_row['lang']);
                                                $test = nl2br($result_row['test']);

                                                $qid = nl2br($result_row['q_id']);

                                                $testtitle = $lang . ' ' . $test . ' ' . $medium ;
                                                error_log("TEST : " . $testtitle . $qid);

                                                $user_answer = nl2br($result_row['answer']);

                                                $res2 = mysqli_query($conn, "SELECT * FROM `$testtitle` where q_id = '$qid' ");
                                                $test_row = mysqli_fetch_assoc($res2);
                                                // error_log($test_row[0]);
                                                // error_log("CHECK:" . $correct_ans . $user_answer . $checked);
                                                echo "<tr><td><b>Question " . $test_row['q_no'] . ") " . $test_row['questions'] . "</b><br>";

                                              
                                                if ($test_row['q_type'] == "objective") {



                                                    $optionvalue = [" ", "A", "B", "C" , "D", "E"];
                                                    for ($x = 1; $x <= 5; $x++) {
                                                        $option_no = "option" . $x;
                                                        
                                                        if ($test_row[$option_no] != null) {
                                                            echo $optionvalue[$x] .  ". ". ($test_row[$option_no])."<br>";
                                                        }
                                                    }

                                                    // echo "<br> A." . $test_row['option1'] . "<br> B." . $test_row['option2'] . "<br> C." . $test_row['option3'] . "<br> D." . $test_row['option4'] . "<br> E." . $test_row['option5'];
                                                }
                                                error_log("CHECK:" . $correct_ans . $user_answer . $checked);

                                                $audio_str = $result_row['audio'];
                                                $audio_names = explode(",", $audio_str);

                                                echo "<br><div class='row'><div class='col-md-8 '><b>Answer :</b> " . $user_answer . "</div></div>
                                                                
                                                                <div class='row'><div class='col-md-8'>";
                                                echo "<i><b>Audio files: </b>";
                                                foreach ($audio_names as  $key=>$audio_name) {
                                                    if($audio_name != "")
                                                    echo "<br>[". $key ."] https://speechindia.in/asr/exam_for_blind/" . "$audio_name" ;
                                                   
                                                }
                                                echo
                                                "</i></div></div>
                                                               
                                                                <span class='oq-news'>                                                            
                                                                Explaination:<br>" . nl2br(ucfirst($test_row['exp']) . "Marks: " . $test_row['marks']) . "</span>";


                                        
                                                $i++;
                                            }
                                        } else {
                                            echo "<span class='oq-news'>No questions available</span>";
                                        }
                                    } else {
                                        echo "<span class='oq-news'>No questions available</span>";
                                    }
                                    ?>
                                </tbody>


                            </table>


                        </div>
                        <div>
                         

                        </div>

                    
                </div>
            </div>
        </div>


        <div class="oq-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6"><span class="oq-footerText">ONLINE EXAM 2024 </span></div>
                    <div class="col-md-6">
                        <!--<span class="oq-footerText pull-right">Developed by - <a href="https://www.cdac.in/">CDAC Mumbai</a></span>-->
                    </div>
                </div>
            </div>
        </div>
        <script src="js/jquery-3.7.1.min.js"></script>
        <script src="js/bootstrap.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    </body>
    <script>
     
    </script>

    </html>

<?php
}
?>