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

    <head>
        <title>Welcome Online Exam</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="main.css">
        <link rel="stylesheet" type="text/css" href="css/font/flaticon.css">
        <link href="https://fonts.googleapis.com/css?family=Fira+Sans|Josefin+Sans" rel="stylesheet">
        <meta charset="UTF-8">
        <meta name="description" content="Online Exam">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </head>

    <body>
        <div class="oq-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <div class=""><a href="index.php"><img src="images/quiz.png" class="oq-logo"></a></div>
                    </div>
                    <div class="col-md-8">
                        <div class="oq-userArea pull-right">
                            <a href="javascript:window.history.back();"><span class="glyphicon glyphicon-arrow-left"></span> Back</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="adminmenu.php"><span class="glyphicon glyphicon-home"></span>&nbsp;&nbsp;Home</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a class="oq-btn" href="logout.php?logout">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="oq-viewTestsBody">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="oq-viewTests">
                            <div class="oq-testsHead">
                                <span class="oq-testsHeadText">
                                    <h3 class="text-center"><?php echo "Subject- " . ucfirst($lang) ?> </h3>
                                </span>
                                <span class="oq-testsHeadText">
                                    <h3 class="text-center"><?php echo  "Test-  " . ucfirst($test) . " " . ucfirst($medium)   ?></h3>
                                </span>
                             
                                <h4 class="text-center"><?php echo "Username: " . ucfirst($userid); ?></h4>
                            </div>
                            <div>
                                <!-- Result: -->
                                <?php

                                $testtitle = $lang . ' ' . $test . ' ' . $medium;

                                // error_log("Total Questons : " . $testtitle);
                                $res = mysqli_query($conn, " SELECT * FROM `$testtitle`");
                                $rows = mysqli_num_rows($res);
                                // echo ("Total Questions : " . $rows . "<br>");

                                $res = mysqli_query($conn, "SELECT * FROM result where user_id = '$userid' AND lang = '$lang' AND test = '$test' AND medium = '$medium' ");
                                $no_of_rows = mysqli_num_rows($res);


                                $unanswered = $rows - $no_of_rows;

                                $total_answers_query = mysqli_query($conn, "SELECT SUM(marks) AS value_sum FROM result where user_id = '$userid' AND lang = '$lang' AND test = '$test' AND medium = '$medium' ");
                                // $total_answers_query_no_of_rows = mysqli_num_rows($total_answers_query) ;

                                $marks_obtained = mysqli_fetch_assoc($total_answers_query)['value_sum'];
                                $subject_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM $lang WHERE tests = '$test'"));



                                $corrected = 0;
                                while ($row1 = mysqli_fetch_assoc($res)) {
                                    $corrected += nl2br($row1['marks']);
                                }

                                // $wrong = $no_of_rows - $corrected;   

                                echo
                                "<div class='container-fluid'>
                                
                                <div class='col-xs-6 col-sm-4'>Total Questions :   $rows</div>
                                <div class='col-xs-6 col-sm-4'>Questions attempted:   $no_of_rows</div>                               
                                <div class='col-xs-6 col-sm-4'>Unanswered Questions:    $unanswered</div>                         
                                <div class='col-xs-6 col-sm-4'>Marks Obtained:    " . $marks_obtained . " / " . $subject_row['total_marks'] . " </div>                         
                                                           
                                                           
                                </div>";

                                // echo "<br><a href='generate_pdf.php?userid=".$userid."&subject=".$lang."&test=".$test."&medium=".$medium."' target='_blank'>Generate PDF(beta)</a>";

                                echo "<br><b><a  class='btn btn-primary' href='viewanswers_printable.php?userid=".$userid."&medium=".$medium." ' target='_blank'>Get Printable html</a></b>";

                                ?>
                            </div>



                            <br><span>List of questions are shown below:</span>



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
                                                echo "<tr><td><b>" . $i . ") " . $test_row['questions'] . "</b>";

                                              
                                                if ($test_row['q_type'] == "objective") {

                                                    $correct_ans;
                                                    if ($test_row['answer'] == "A")
                                                        $correct_ans = $test_row['option1'];
                                                    if ($test_row['answer'] == "B")
                                                        $correct_ans = $test_row['option2'];
                                                    if ($test_row['answer'] == "C")
                                                        $correct_ans = $test_row['option3'];
                                                    if ($test_row['answer'] == "D")
                                                        $correct_ans = $test_row['option4'];
                                                    if ($test_row['answer'] == "E")
                                                        $correct_ans = $test_row['option5'];

                                                    if ($result_row['marks'] != 0) {
                                                        $checked = "checked";
                                                        $unchecked = "unchecked";
                                                    } else {
                                                        $unchecked = "checked";
                                                        $checked = "unchecked";
                                                    }



                                                    echo "<br> A." . $test_row['option1'] . "<br> B." . $test_row['option2'] . "<br> C." . $test_row['option3'] . "<br> D." . $test_row['option4'] . "<br> E." . $test_row['option5'];
                                                }
                                                error_log("CHECK:" . $correct_ans . $user_answer . $checked);

                                                $audio_str = $result_row['audio'];
                                                $audio_names = explode(",", $audio_str);

                                                echo "<br><div class='row'><div class='col-md-8 '><b>Answer :</b> " . $user_answer . "</div></div>
                                                                
                                                                <div class='row'><div class='col-md-6 col-md-offset-3'>";

                                                foreach ($audio_names as $audio_name) {
                                                    if($audio_name != "")
                                                    echo  "<audio controls>  
                                                                <source src='../../../asr/exam_for_blind/" . $audio_name . "' 
                                                            
                                                                type='audio/wav' id='aud" . $i . "'></audio>";
                                                }
                                                echo
                                                "</div></div>
                                                               
                                                                <span class='oq-news'>                                                            
                                                                Explaination:<br>" . nl2br(ucfirst($test_row['exp']) . "Marks: " . $test_row['marks']) . "</span>";


                                                if ($test_row['q_type'] == "objective") {
                                                    echo "<div class='row'><div class='col-md-6 col-md-offset-3'>
                                                                        Correct Answer: " . $test_row['answer'] . ". " . $correct_ans . "<br> <input type='radio' id='correct" . $i . "' name=" . $i . " value='correct' onchange='save($i , " . $test_row['marks'] . " , 0, " . $qid . ")' " . $checked . ">
                                                                                    <label for='correct_incorrect'>Correct</label>
                                                                                    <input type='radio' id='incorrect" . $i . "' name=" . $i . " value='incorrect' onchange='save($i , " . $test_row['marks'] . " , 0, " . $qid . ")' " . $unchecked . ">
                                                                                    <label for='correct_incorrect'>Incorrect</label>
                                                                                    
                                                                                    
                                                                                    </div></div>
                                                                                    
                                                                                    </td></tr>";
                                                } else {
                                                    echo "<div class='row'><div class='col-md-6 '>
                                                                    <input type='number' id='eval_marks" . $i . "' value = '" . $result_row['marks'] . "' onchange='save( $i , " . $test_row['marks'] . " , 1 , \"". $qid . "\", \"". $userid . "\")'>
                                                                                                                                                               
                                                                                </div></div>
                                                                                
                                                                                </td></tr>";
                                                }
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
                            <button type="button" class='btn btn-primary' onClick="window.location.reload()">
                                Recalculate
                            </button>

                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="oq-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6"><span class="oq-footerText">ONLINE QUIZ</span></div>
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
        // const element = document.getElementById("myBtn");
        // element.addEventListener("click", function() {
        //     console.log("submitted");
        // });
        
    
        function save(index, marks, q_type, qid, userid) {
            console.log("save", index, marks, q_type, qid, userid);

            if (q_type == 0) {
                correct = document.getElementById("correct" + index);
                if (correct.checked == false)
                    marks = 0;
            } else {
                //check marks 
                eval_marks = document.getElementById("eval_marks" + index).value;
                if (eval_marks >= 0 && eval_marks <= marks) {
                    marks = eval_marks;
                } else {
                    document.getElementById("eval_marks" + index).value = "";
                    return;
                }

            }

            audio = document.getElementById("aud" + index)
            var audio_src = (audio.src).split("\/").at(-1);


            var data = {
                marks_obtained: marks,
                // audio_src: audio_src
                q_id: qid,
                user_id : userid
            };

            console.log("result save: ", data);

            $.post("save_eval_response.php", data);

        }
        // save( 1 , 12 , 1 , 'question_1689149934')

    </script>

    </html>

<?php
}
?>