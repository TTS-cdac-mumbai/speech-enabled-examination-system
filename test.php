<?php
include_once 'translator.php';

// Assuming language selection is done elsewhere (e.g., user preference stored in session)
$selectedLanguage = "en";

?>

<!DOCTYPE html>
<html lang="<?php echo $selectedLanguage; ?>">
<?php
session_start();
require_once('dbconfig.php');
$id = $_SESSION['usersession'];
$lang = $_SESSION['lang'];
$test = $_SESSION['test'];

if ($id == null) {
    header('Location: index.php');
}
if (isset($_GET['medium'])) {
    // $test = $_GET['test'];
    $medium = $_GET['medium'];
    $_SESSION['adminsession'] = $id;
    $_SESSION['test'] = $test;
    $_SESSION['lang'] = $lang;
    $_SESSION['medium'] = $medium;
    // $test = $_GET['test'];
    // $testtitle = $lang . ' ' . $test;
    // $_SESSION['adminsession'] = $id;
    // $_SESSION['userid'] = $userid;
    // $_SESSION['test'] = $test;
    // $_SESSION['lang'] = $lang;
    $testtitle = $lang . ' ' . $test . ' ' . $medium;
    $translator = new Translator($medium);
    $langCode = $translator->getLanguageCode($medium);

    echo "<head>
                <title>Welcome Online Exam</title>
                <link rel='stylesheet' type='text/css' href='css/bootstrap.css'>
                <link rel='stylesheet' type='text/css' href='main.css'>
                <link rel='stylesheet' type='text/css' href='css/font/flaticon.css'>
                <link rel='stylesheet' type='text/css' href='css/mic.css'>

                <!--<link href='https://fonts.googleapis.com/css?family=Fira+Sans|Josefin+Sans' rel='stylesheet'>-->
                <meta charset='UTF-8'>
                <meta name='description' content='Online Exam'>
                <meta name='author' content='Akhil Regonda'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    if ($result1 = mysqli_query($conn, "SELECT * FROM `$lang` WHERE tests = '$test' and lang_medium='$medium' ")) {
        if (mysqli_num_rows($result1) > 0) {
            while ($row2 = mysqli_fetch_assoc($result1)) {
                $ttime = $row2['testtime'];
                echo "<script>
                                function timer(){
                                    console.log(" . json_encode($row2['testtime']) . ");
                                    var expDate = new Date().getTime();
                                    var countDown = " . json_encode($row2['testtime']) . ";
                                    console.log('countDown'+countDown);
                                    var countDownDate = expDate+(countDown*60000);
                                    var x = setInterval(function() {
                                    var now = new Date().getTime();
                                    var distance = countDownDate - now;
                                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                    document.getElementById('timer').innerHTML = minutes + 'm ' + seconds + 's ';
                                    if (distance < 0) {
                                        clearInterval(x);
                                        alert('time up!! click enter see you score');
                                        getScore(quiz);
                                    }
                                    }, 1000);
                                }
                            </script>
                            </head>";
            }
        }
    } else {
        echo " something went wrong! ";
    }
} else {
    echo "something went wrong! ";
}
?>

<body>
    <div class="oq-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class=""><a href="index.php"><img src="images/quiz.png" class="oq-logo"></a></div>
                </div>
                <div class="col-md-8">
                    <div class="oq-userArea pull-right">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="oq-viewTestsBody">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="oq-viewTests" id="test">
                        <div class="oq-instruct">
                            <h3 id="testHead"><?php echo $translator->translate("welcome_message"); ?> <?php echo $lang; ?></h3>
                            <b>User : <span id="user_id"><?php echo $id; ?></span></b>

                            <hr>
                            <i><span>Language : </span><span id="langCode"><?php echo $langCode; ?></span>
                                <?php
                                if ($res = mysqli_query($conn, "SELECT * FROM `$lang` where tests = '$test' and lang_medium = '$medium' ")) {
                                    $data = mysqli_fetch_assoc($res);
                                    error_log($data['exam_pattern']);
                                    echo "<h5 id='speakTimer'> " . $translator->translate("time") . " : <span id='timer'>" . $data['testtime'] . " Minutes. </span></h5>";
                                    echo "<h5 id='totalMarks'>" . $translator->translate("totalMarks") . " : <span id='total_marks'>" . $data['total_marks'] . ".</span></h5>";
                                }
                                ?>
                            </i>
                            <hr>
                            <button class="float3" style="font-size: 14px; vertical-align: middle; margin-bottom: 300px;" onclick="interrupt()" id="interrupt">Interrupt</button>
                            <button class="float2" style="font-size: 14px; vertical-align: middle; margin-bottom: 200px;" onclick="StopResume()" id="stop_resume">Pause</button>
                            <button class="speaker_btn float" id="speakerButton" style="font-size: 17px; vertical-align: middle;  margin-bottom: 100px;"></button>
                            <button class="record_btn float" id="micButton" onclick="micButtonClicked()" style="font-size: 17px; vertical-align: middle; margin-left: -30px;"></button>


                            <p id="sayInstructions"><?php echo $translator->translate("sayInstructions") . $translator->translate("sayStartExam"); ?></p>
                            <span id="spresult" class="spresult"></span>
                            <span id="spresult7" class="spresult7"></span>
                            <span id="spresult8" class="spresult8"></span>

                            <p id="instructions" class="spresult"><?php echo $translator->translate("instructions") . "</p> <p id = 'pattern'>  " . $data['exam_pattern']; ?></p>
                            <span id="spresult1" class="spresult1"></span>

                            <!-- <h4 id="speakTimer">Time remaining : <span id="timer">40 Minutes 0 seconds</span></h4> -->
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <form action="result.php" method="post" name="oqform">

                                    <script>
                                        function adjustTextareaSize(textareaId) {
                                            // console.log("inside textareaSize")
                                            var textarea = document.getElementById(textareaId);
                                            textarea.style.height = 'auto';
                                            textarea.style.height = textarea.scrollHeight + 'px';
                                            textarea.scrollTop = textarea.scrollHeight;
                                        }

                                        function checkForChanges(textareaId) {
                                            var textarea = document.getElementById(textareaId);
                                            var prevContent = textarea.value;

                                            setInterval(function() {
                                                if (textarea.value !== prevContent) {
                                                    adjustTextareaSize(textareaId);
                                                    prevContent = textarea.value;
                                                    // console.log("inside textareaSize function")
                                                }
                                            }, 500); // Adjust the interval as needed
                                        }
                                    </script>
                                    <?php
                                    $i = 1;
                                    if ($res = mysqli_query($conn, "SELECT * FROM `$testtitle`")) {
                                        if (mysqli_num_rows($res) > 0) {
                                            $rowscount = mysqli_num_rows($res);
                                            echo "<p id='spresult2' class='spresult2'></p>";
                                            $non_question = 0;
                                            $question_ids_nos = array();
                                            while ($row = mysqli_fetch_assoc($res)) {
                                                $j = 0;
                                                // $quet = mysqli_fetch_assoc($res); 
                                                $que = $row['questions'];
                                                error_log("Questions:" . $que);
                                                echo "<input type='hidden' id='rowscount' name='totalQus' value='" . $rowscount . "'>";
                                                echo "<input type='hidden' id='que' name='que' value='" . $que . "'>";
                                                echo "
                                                <div id='ques" . $i . "'>";
                                                if ($row['guidelines']) {
                                                    echo "<span>Note : " . nl2br($row['guidelines']) . "</span><br>";
                                                }
                                                $q_id  = nl2br($row['q_id']);
                                                echo "<input type='hidden' value='" . $q_id . "' id='quesId" . $i . "'>";
                                                echo "<button class='oq-aLeft oq-questions oq-form-control' disabled> " . $translator->translate("question") . " " . nl2br($row['q_no']) . ". " . nl2br($row['questions']) . "</button>";
                                                $question_ids_nos[$q_id] = nl2br($row['q_no']);
                                                // array_push($question_ids_nos, nl2br($row['q_no']));
                                                // Object.assign(question_id_no, {nl2br($row['q_id']): nl2br($row['q_no'])});
                                                echo "<input type='hidden' value='" . $i . "' id='quesNo'>";
                                                echo "<input type='hidden' value='" . $row['q_type'] . "' id = 'typeId" . $i . "' >";
                                                if ($row['q_type'] == "objective") {
                                                    echo "" . $translator->translate("marks") . " : <span id = 'marksId" . $i . "'>" . $row['marks'] . " , </span><br>";
                                                    echo "options are, <br>";

                                                    // echo "<span class='oq-options'><input type='radio' name='qus".$i."' value='A' id='temp".$i."'> &nbsp;1) <span id='oq-options".$i.++$j."'>".nl2br($row['option1'])."</span></span><br>
                                                    // <span class='oq-options'><input type='radio' name='qus".$i."' value='B'> &nbsp;2) <span id='oq-options".$i.++$j."'>".nl2br($row['option2'])."</span></span><br>"
                                                    // .($row['option3'] != null?"<span class='oq-options'><input type='radio' name='qus".$i."' value='C'> &nbsp;3) <span id='oq-options".$i.++$j."'>".nl2br($row['option3'])."</span></span>":"")."<br>"
                                                    // .($row['option4'] != null?"<span class='oq-options'><input type='radio' name='qus".$i."' value='D'> &nbsp;4) <span id='oq-options".$i.++$j."'>".nl2br($row['option4'])."</span></span>":"")."<br>"
                                                    // .($row['option5'] != null?"<span class='oq-options'><input type='radio' name='qus".$i."' value='E'> &nbsp;5) <span id='oq-options".$i.++$j."'>".nl2br($row['option5'])."</span></span>":"")."<br>";

                                                    $res2 = mysqli_query($conn, "SELECT answer FROM result where q_id='$q_id' and user_id='$id' ");
                                                    $answer_row = mysqli_fetch_assoc($res2);
                                                    // error_log("LOGGG ...  ". $answer_row['answer']);
                                                    $optionvalue = [" ", "A", "B", "C" , "D", "E"];
                                                    for ($x = 1; $x <= 5; $x++) {
                                                        $option_no = "option" . $x;
                                                        
                                                        if (mysqli_num_rows($res2) > 0 && ($row[$option_no] == $answer_row['answer'])) {
                                                            echo ($row[$option_no] != null?"<span class='oq-options'><input type='radio' name='qus".$i."' value='". $optionvalue[$x]."' id='temp".$i."' checked> &nbsp;". $x .") <span id='oq-options".$i.++$j."'>".nl2br($row[$option_no])."</span></span>":"")."<br>";
                                                        }else{
                                                            echo ($row[$option_no] != null?"<span class='oq-options'><input type='radio' name='qus".$i."' value='". $optionvalue[$x]."' id='temp".$i."'> &nbsp;". $x .") <span id='oq-options".$i.++$j."'>".nl2br($row[$option_no])."</span></span>":"")."<br>";
                                                        }
                                                      }

                                                    // if (mysqli_num_rows($res2) > 0 && ($row['option1'] == $answer_row['answer'])) {
                                                    //     echo "<span class='oq-options'><input type='radio' name='qus".$i."' value='A' id='temp".$i."' checked> &nbsp;1) <span id='oq-options".$i.++$j."'>".nl2br($row['option1'])."</span></span><br>";
                                                    // }else{
                                                    //     echo "<span class='oq-options'><input type='radio' name='qus".$i."' value='A' id='temp".$i."'> &nbsp;1) <span id='oq-options".$i.++$j."'>".nl2br($row['option1'])."</span></span><br>";
                                                    // }
                                                    // if (mysqli_num_rows($res2) > 0 && ($row['option2'] == $answer_row['answer'])) {
                                                    //     echo "<span class='oq-options'><input type='radio' name='qus".$i."' value='B' checked> &nbsp;2) <span id='oq-options".$i.++$j."'>".nl2br($row['option2'])."</span></span><br>";
                                                    // }else{
                                                    //     echo "<span class='oq-options'><input type='radio' name='qus".$i."' value='B'> &nbsp;2) <span id='oq-options".$i.++$j."'>".nl2br($row['option2'])."</span></span><br>";
                                                    // }
                                                    // if (mysqli_num_rows($res2) > 0 && ($row['option3'] == $answer_row['answer'])) {
                                                    //     echo ($row['option3'] != null?"<span class='oq-options'><input type='radio' name='qus".$i."' value='C' checked> &nbsp;3) <span id='oq-options".$i.++$j."'>".nl2br($row['option3'])."</span></span>":"")."<br>";
                                                    // }else{
                                                    //     echo ($row['option3'] != null?"<span class='oq-options'><input type='radio' name='qus".$i."' value='C'> &nbsp;3) <span id='oq-options".$i.++$j."'>".nl2br($row['option3'])."</span></span>":"")."<br>";
                                                    // }
                                                    // if (mysqli_num_rows($res2) > 0 && ($row['option4'] == $answer_row['answer'])) {
                                                    //     echo ($row['option4'] != null?"<span class='oq-options'><input type='radio' name='qus".$i."' value='D' checked> &nbsp;4) <span id='oq-options".$i.++$j."'>".nl2br($row['option4'])."</span></span>":"")."<br>";
                                                    // }else{
                                                    //     echo ($row['option4'] != null?"<span class='oq-options'><input type='radio' name='qus".$i."' value='D'> &nbsp;4) <span id='oq-options".$i.++$j."'>".nl2br($row['option4'])."</span></span>":"")."<br>";
                                                    // }
                                                    // if (mysqli_num_rows($res2) > 0 && ($row['option5'] == $answer_row['answer'])) {
                                                    //     echo ($row['option5'] != null?"<span class='oq-options'><input type='radio' name='qus".$i."' value='E' checked> &nbsp;5) <span id='oq-options".$i.++$j."'>".nl2br($row['option5'])."</span></span>":"")."<br>";;
                                                    // }else{
                                                    //     echo ($row['option5'] != null?"<span class='oq-options'><input type='radio' name='qus".$i."' value='E'> &nbsp;5) <span id='oq-options".$i.++$j."'>".nl2br($row['option5'])."</span></span>":"")."<br>";;
                                                    // }

                                                } elseif ($row['q_type'] == "none") {
                                                    echo "" . $translator->translate("totalMarks") . " : <span id = 'marksId" . $i . "'>" . $row['marks'] . " </span><br>";
                                                    $non_question++;
                                                } else {
                                                    echo "" . $translator->translate("marks") . " : <span id = 'marksId" . $i . "'>" . $row['marks'] . " </span><br>";
                                                    echo "</div><div>";
                                                    $saved_ans = "";
                                                    $res2 = mysqli_query($conn, "SELECT answer FROM result where q_id='$q_id' and user_id='$id' ");
                                                    if (mysqli_num_rows($res2) > 0) {
                                                        $row2 = mysqli_fetch_assoc($res2);
                                                        $saved_ans = $row2['answer'];
                                                    }

                                                    //     echo "<textarea rows='6' cols='80' id='answer" . $i . "'  onchange='adjustTextareaSize(\"answer" . $i . "\")'   name='answer" . $i . "' placeholder='your answer here'> " . $row2['answer'] . "</textarea>";
                                                    //     echo "<script>checkForChanges('answer" . $i . "');</script>";
                                                    // } else {

                                                    //     echo "<textarea rows='6' cols='80' id='answer" . $i . "' onchange='adjustTextareaSize(\"answer" . $i . "\")'  name='answer" . $i . "' placeholder='your answer here'  > </textarea>";
                                                    //     echo "<script>checkForChanges('answer" . $i . "');</script>";
                                                    // }
                                                    echo "<textarea rows='6' cols='80' id='answer" . $i . "'  onchange='adjustTextareaSize(\"answer" . $i . "\")'   name='answer" . $i . "' placeholder='your answer here'> " . $saved_ans . "</textarea>";
                                                    echo "<script>checkForChanges('answer" . $i . "');</script>";
                                                }

                                                echo "</div> </br>";

                                                $i++;
                                            }
                                            $actual_questions = $rowscount - $non_question;
                                            echo "<input type='hidden' id='actual_question' name='actual_question' value='" . $actual_questions . "'>";
                                            echo "<input type='submit' class='oq-btn' name='submit' id='oqsubmit' value='" . $translator->translate("submit") . "'>";
                                        }
                                    } else {
                                        echo "something went wrong! ";
                                    }


                                    ?>
                                    <script>
                                        var question_ids_nos = <?= json_encode($question_ids_nos, JSON_UNESCAPED_UNICODE); ?>;

                                        // console.log(question_ids_nos)
                                    </script>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/js-loading-overlay.js"></script>
    <script src="js/RecordRTC.js"></script>
    <script src="js/hark.js"></script>
    <script src="js/synthesize.js"></script>
    <script src="js/transcribe.js"></script>
    <script src="js/control.js"></script>
    <script src="js/script.js"></script>
    <script>


    </script>
    <div class="oq-footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6"><span class="oq-footerText">ONLINE QUIZ</span></div>
                <div class="col-md-6"><span class="oq-footerText pull-right">Developed by - <a href="https://www.cdac.in/">CDAC Mumbai</a></span></div>
            </div>
        </div>
    </div>
</body>

</html>