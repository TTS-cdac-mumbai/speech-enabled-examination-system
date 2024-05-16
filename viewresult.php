<?php
session_start();
require_once('dbconfig.php');
$id = $_SESSION['adminsession'];
if ($id == null) {
    header('Location: admin.php');
}


if (isset($_GET['viewtest']) || isset($_GET['lang']) || isset($_GET['test'])) {
    $lang = $_GET['lang'];
    $test = $_GET['test'];

    $lastSpacePos = strrpos($test, ' ');
    // Extract the substring starting from the position of the last space plus 1
    $medium =  substr($test, $lastSpacePos + 1);
    $test = substr($test, 0, $lastSpacePos);
    
    $wrongkey = "";


    if (isset($_POST['checkKey'])) {
        $key = $_POST['newlang'];
        $userid = $_POST['userid'];
        error_log("KEY: " . $key . $userid);

        $result = mysqli_query($conn, "SELECT * FROM `$lang` WHERE tests = '$test' and eval_key = '$key'");
        if (mysqli_num_rows($result) > 0) {
            error_log("matched:");
            $wrongkey = "";
            header('Location: viewanswers.php?userid=' . $userid . '&medium=' . $medium);
        } else {
            error_log(" not matched:");
            $wrongkey = "Key is wrong";
        }
    }


    $result = mysqli_query($conn, "SELECT * FROM lang WHERE subjects = '$lang'");
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['lang'] = $lang;
            $_SESSION['adminsession'] = $id;
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
                <meta name="author" content="Akhil Regonda">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <?php
                require_once('dbconfig.php');
                ?>
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
                                        <span class="oq-testsHeadText"><?php echo strtoupper($lang) . " " . strtoupper($test) . " " . strtoupper($medium); ?></span>
                                        <div class="pull-right">
                                            <?php
                                            if (isset($_GET['error'])) {
                                                echo "<span class='oq-error'>*Test already exists! </span>";
                                            }
                                            echo "<span class='oq-error'>$wrongkey</span>";
                                            ?>
                                            <!-- <a class="oq-addbtn" data-toggle="modal" data-target=".newtest">Add New Test</a> -->
                                        </div><br><br>


                                        <span>List of tests are shown below:</span>
                                    </div>
                                    <!-- <div class="modal fade newtest" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                                              <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <form class="form" action="createtest.php" method="post">
                                                            <span>Test Title</span><br><br>
                                                            <input type="text" class="form-control" placeholder="Enter test title" name="title" required><br><br>
                                                            <span>Test Time</span><br><br>
                                                            <input type="text" class="form-control" placeholder="Time limit for the test (in minutes)" name="testtime" required><br>
                                                            <input type="submit" class="form-control oq-btn" value="Create" name="newtest">
                                                        </form>
                                                    </div>
                                                </div>
                                              </div>
                                            </div> -->


                                    <table class="table oq-table">
                                        <tbody>
                                            <?php
                                            // $res2 = mysqli_query($conn, "SELECT * FROM result where user_id = '$userid' AND lang = '$lang' AND test = '$test'");

                                            // $score = 0;
                                            // while ($row2 = mysqli_fetch_assoc($res2)) {
                                            //     $score += nl2br($row2['marks']);
                                            // }
                                            // error_log("Score:" . $score);




                                            $res = mysqli_query($conn, "SELECT DISTINCT user_id, lang FROM result where lang='$lang'");
                                            if (mysqli_num_rows($res) > 0) {


                                                echo "<tr><th>S no.</th><th>User</th><th>Time</th><th>Result</th><th>Score</th></tr>";
                                                $i = 1;
                                                $testtitle = $lang . ' ' . $test;

                                                // to get the tootal exam time
                                                $subject_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM $lang WHERE tests = '$test'"));
                                                // error_log("TIME". $test_time['testtime']);

                                                while ($row1 = mysqli_fetch_assoc($res)) {

                                                    $userid = ucfirst($row1['user_id']);


                                                    // get total number of questions
                                                    // $total_questions_query = mysqli_query($conn, " SELECT * FROM `$testtitle`");
                                                    // $total_questions_query_rows = mysqli_num_rows($total_questions_query);

                                                    $total_answers_query = mysqli_query($conn, "SELECT SUM(marks) AS value_sum FROM result where user_id = '$userid' AND lang = '$lang' AND test = '$test'");
                                                    // $total_answers_query_no_of_rows = mysqli_num_rows($total_answers_query) ;

                                                    $marks_obtained = mysqli_fetch_assoc($total_answers_query)['value_sum'];

                                                    $total_time_spent_query = mysqli_query($conn, "SELECT timestamp FROM result where user_id = '$userid' AND lang = '$lang' AND test = '$test' ORDER BY timestamp");

                                                    $total_time_spent = mysqli_fetch_array($total_time_spent_query, MYSQLI_NUM  );
                                                    $total_time_spent_query_rows = mysqli_num_rows($total_time_spent_query);

                                                    // $to_time = strtotime($total_time_spent[$total_time_spent_query_rows]);
                                                    // $from_time = strtotime($total_time_spent[0]);
                                                    // error_log("CAL : to : ". $total_time_spent[1] . " ". $to_time . " from " . $from_time . " " . $total_time_spent[0]);
                                                    // $diff_minutes =  round(abs($to_time - $from_time) / 60, 2) . " minute";

                                                    // $corrected_answers = 0;
                                                    // if ($total_answers_query_no_of_rows > 0 ){
                                                    //     while ($total_answers_query_rows = mysqli_fetch_assoc($total_answers_query)) {
                                                    //         $corrected_answers += nl2br($total_answers_query_rows['marks']);
                                                    //         if($total_answers_query_no_of_rows == 1);
                                                    //     }
                                                    // }



                                                    echo "<tr><td>" . $i . "</td><td>" . $userid . "</td><td>  /" .  $subject_row['testtime'] . " Mins</td>
                                                               
                                                               <td><a data-toggle='modal' data-target='.enter-key" . $i . "'><span class='glyphicon glyphicon-th-list'></span>&nbsp;&nbsp; Evaluate</a></td>
                                                               <td>" . $marks_obtained . "/" . $subject_row['total_marks'] . "</td>
                                                               </tr>";


                                                    echo "<div class='modal fade enter-key" . $i . "' tabindex='-1' role='dialog' aria-labelledby='mySmallModalLabel'>
                                                    <div class='modal-dialog' role='document'>
                                                        <div class='modal-content'>
                                                            <div class='modal-header'>
                                                                <div class='text-center'>
                                                                    
                                                                </div>
                                                            </div>
                                                            <div class='modal-body'>
                                                                <div class='text-center'>
                                                                    <div class='row'>
                                                                    <div class='col-md-8 col-md-offset-2'>
                                
                                                                        <form class='form' action='' method='post'>
                                                                            <div >User:" . $row1['user_id'] . "
                                                                            </div><br>
                                                                          
                                                                            <span class='oq-modalLangHead'>Enter the key</span><br><br>
                                                                           

                                                                                <input type='hidden' class='form-control' name='userid' value= " . $row1['user_id'] . " required>
                                                                                <br>
                                                                                <input type='text' class='form-control' placeholder='Enter the key' name='newlang' required>
                                                                                <br>
                                                                                <input type='submit' class='form-control oq-btn' value='Submit' name='checkKey'>
                                                                                <br>
                                                                                <a href='get_keys.php?lang=".$lang."&test=".$test."' target='_blank'>Get Key</a>
                                                                            
                                                                        </form>
                                                                        </div>
                                                                    </div><br><br>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>";
                                                    $i++;
                                                }
                                            } else {
                                                echo "<span class='oq-news'>No tests available</span>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="oq-footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6"><span class="oq-footerText">ONLINE QUIZ</span></div>
                            <div class="col-md-6"><span class="oq-footerText pull-right">Developed by - <a href="https://www.cdac.in/">CDAC Mumbai</a></span></div>
                        </div>
                    </div>
                </div>





                <script src="js/jquery-3.7.1.min.js"></script>
                <script src="js/bootstrap.js"></script>
            </body>

            </html>
<?php

        }
    }
}



?>