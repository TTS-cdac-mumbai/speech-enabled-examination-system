<?php
session_start();
require_once('dbconfig.php');
$id = $_SESSION['adminsession'];
$lang = $_SESSION['lang'];
if ($id == null) {
    header('Location: admin.php');
}
if (isset($_GET['test'])) {
    $test = $_GET['test'];
    $medium = $_GET['medium'];
    $_SESSION['adminsession'] = $id;
    $_SESSION['test'] = $test;
    $_SESSION['lang'] = $lang;
    $_SESSION['medium'] = $medium;
    $testtitle = $lang . ' ' . $test . ' ' . $medium;
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
                            <a href="viewtests.php?testlang=<?php echo $lang; ?>"><span class="glyphicon glyphicon-arrow-left"></span> Back</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                                <?php
                                if (isset($_GET['error'])) {
                                    echo "<div class='row'><div class='col-md-12'><div class='pull-right'><span class='oq-error'>*Question already exists!</span></div></div></div><br>";
                                }
                                ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="oq-testsHeadText"><?php echo strtoupper($test . " " . $medium); ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <a href="savetest.php?test=<?php echo $test; ?>" class="oq-btn">View the quiz</a>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="pull-right">
                                            <a class="oq-addbtn" data-toggle="modal" data-target=".newtest">Add New Question</a>
                                        </div>
                                    </div>
                                </div><br>
                                <span>List of questions are shown below:</span>
                            </div>



                            <div class="modal fade newtest" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <div class="oq-questionModal">
                                                <form class="form" action="createquestions.php" method="post">
                                                    <span>Guidelines</span><br>
                                                    <textarea class="form-control" placeholder="Enter guidelines" name="guidelines"></textarea><br>
                                                    <span>Question no.*</span><br>
                                                    <input class="form-control" type='text' class='form-inline' id='qno' placeholder="Enter the question number or part number" name='qno' required><br>
                                                    <span>Question*</span><br>
                                                    <textarea class="form-control" placeholder="Enter the question" name="title" required></textarea><br>

                                                    <span>Marks*</span><br>
                                                    <input class="form-control" type='number' class='form-inline' id='marks' name='marks' required>

                                                  <br>
                                                    
                                                    <!-- <span>Marks</span><br><br>
                                                    <textarea class="form-control" placeholder="Enter the Marks" name="marks" required></textarea><br> -->
                                                    <span>Question Type*</span><br>
                                                    <div class="form-control">
                                                        <input type='radio' id='none' class='form-inline' name='q_type' value='none' onchange="hideOptions()" checked required>
                                                        <label for='none' class='form-inline'>None</label>
                                                        <input type='radio' class='form-inline' id='objective' name='q_type' value='objective' onchange="hideOptions()" required>
                                                        <label for='objective' class='form-inline'>Objective</label>
                                                        <input type='radio' id='descriptive' class='form-inline' name='q_type' value='descriptive' onchange="hideOptions()" required>
                                                        <label for='descriptive' class='form-inline'>Descriptive</label>
                                                        <br>

                                                    </div>


                                                    <div id="objective_options" hidden>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-inline"><input type="radio" name="answer" value="A"> &nbsp;A) &nbsp;<textarea class="form-control" placeholder="Enter option A" name="option1"></textarea></div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-inline"><input type="radio" name="answer" value="B"> &nbsp;B) &nbsp;<textarea class="form-control" placeholder="Enter option B" name="option2"></textarea></div>
                                                            </div>
                                                        </div><br>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-inline"><input type="radio" name="answer" value="C"> &nbsp;C) &nbsp;<textarea class="form-control" placeholder="Enter option C" name="option3"></textarea></div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-inline"><input type="radio" name="answer" value="D"> &nbsp;D) &nbsp;<textarea class="form-control" placeholder="Enter option D" name="option4"></textarea></div>
                                                            </div>
                                                        </div><br>
                                                        <div class="row">
                                                            <div class="col-md-6 col-md-offset-3">
                                                                <div class="form-inline"><input type="radio" name="answer" value="E"> &nbsp;E) &nbsp;<textarea class="form-control" placeholder="Enter option E" name="option5"></textarea></div>
                                                            </div>
                                                        </div>
                                                    </div><br><br>
                                                    <span>Explanation:</span><br>
                                                            <div><textarea rows="2" class="form-control" placeholder="Enter explanation here" name="explain"></textarea></div>
                                                    <br>
                                                    <input type="submit" class="form-control oq-btn" value="Create question" name="newques">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <table class="table oq-table">
                                <tbody>
                                    <?php
                                    $res = mysqli_query($conn, "SELECT * FROM `$testtitle`");
                                    if ($res) {

                                        if (mysqli_num_rows($res) > 0) {
                                            $i = 1;
                                            while ($row1 = mysqli_fetch_assoc($res)) {
                                                if ($row1['guidelines']) {
                                                    echo "<tr><td colspan='3'> Note : " . nl2br(ucfirst($row1['guidelines'])) . "</td></tr>";
                                                }
                                                
                                                echo "<tr><td> Question " . nl2br(ucfirst($row1['q_no'])) . ". " . nl2br(ucfirst($row1['questions'])) . "<br><br>";

                                                if ($row1['q_type'] == "objective") {
                                                    echo "<div class='row'><div class='col-md-6'>A) " . nl2br(ucfirst($row1['option1'])) . "</div><div class='col-md-6'>B)  " . nl2br(ucfirst($row1['option2'])) . "</div></div><br><div class='row'><div class='col-md-6'>C) " . nl2br(ucfirst($row1['option3'])) . "</div><div class='col-md-6'>D) " . nl2br(ucfirst($row1['option4'])) . "</div></div><br>" . ($row1['option5'] != null ? "<div class='row'><div class='col-md-6 col-md-offset-3'>E) " . nl2br(ucfirst($row1['option5'])) . "</div></div><br>" : " ") . "<div class='row'><div class='col-md-6 col-md-offset-3'>Answer : " . nl2br(ucfirst($row1['answer'])) . "</div></div><br>";
                                                }
                                                echo  "<div class='row'><div class='col-md-10 col-md-offset-1'><span class='oq-news'>
                                                Marks : " . nl2br(ucfirst($row1['marks'])) . " <br > Question Type : ".  nl2br(ucfirst($row1['q_type'])) ." <br> Explaination:</span><br><br>" . nl2br(ucfirst($row1['exp'])) . 
                                                "</div></div></td><td class='oq-operations'><a data-toggle='modal' data-target='." . $row1['q_id'] . "' class='oq-btn'><span class='glyphicon glyphicon-pencil'></span> Edit Question</a> <br><br><a data-toggle='modal' data-target='.del" . $row1['q_id'] . "' class='oq-deletebtn'><span class='glyphicon glyphicon-remove'></span> Delete</a></td></tr>";
                                                $i++;



                                                echo "<div class='modal fade " . $row1['q_id'] . "' tabindex='-1' role='dialog' aria-labelledby='mySmallModalLabel'>
                                                                  <div class='modal-dialog' role='document'>
                                                                    <div class='modal-content'>
                                                                        <div class='modal-body'>
                                                                            <div class='oq-questionModal'>
                                                                                <form class='form' action='upquestions.php' method='post'>
                                                                                    <span>Guidelines</span><br><br>
                                                                                    <textarea class='form-control' name='guidelines'>" . $row1['guidelines'] . "</textarea><br>
                                                                                    <span>Question</span><br><br>
                                                                                    <input type='hidden' class='form-control' value='" . $row1['q_id'] . "' name='qid'>
                                                                                    <input type='text' class='form-control' value='" . $row1['q_no'] . "' name='qno'><br>
                                                                                    <textarea class='form-control' name='title'>" . $row1['questions'] . "</textarea><br>

                                                                                    <input type='number' class='form-inline' id='marks' name='marks' value =" . $row1['marks'] .">
                                                                                    <label for='marks' class='form-inline'>Marks</label>";
                                                if ($row1['q_type'] == "objective") {

                                                    echo "       <div class='row'>
                                                                                        <div class='col-md-6'>
                                                                                            <div class='form-inline'><input type='radio' name='answer' value='A' " . ($row1['answer'] == 'A' ? 'checked' : '') . "> &nbsp;A) &nbsp;<input type='text' class='form-control'  name='option1' value='" . $row1['option1'] . "' required></div>
                                                                                        </div>
                                                                                        <div class='col-md-6'>
                                                                                            <div class='form-inline'><input type='radio' name='answer' value='B' " . ($row1['answer'] == 'B' ? 'checked' : '') . "> &nbsp;B) &nbsp;<input type='text' class='form-control'  name='option2' value='" . $row1['option2'] . "' required></div>
                                                                                        </div>
                                                                                    </div><br>
                                                                                    <div class='row'>
                                                                                        <div class='col-md-6'>
                                                                                            <div class='form-inline'><input type='radio' name='answer' value='C' " . ($row1['answer'] == 'C' ? 'checked' : '') . "> &nbsp;C) &nbsp;<input type='text' class='form-control'  name='option3' value='" . $row1['option3'] . "' ></div>
                                                                                        </div>
                                                                                        <div class='col-md-6'>
                                                                                            <div class='form-inline'><input type='radio' name='answer' value='D' " . ($row1['answer'] == 'D' ? 'checked' : '') . "> &nbsp;D) &nbsp;<input type='text' class='form-control'  name='option4' value='" . $row1['option4'] . "' ></div>
                                                                                        </div>
                                                                                    </div><br>
                                                                                    <div class='row'>
                                                                                        <div class='col-md-6 col-md-offset-3'>
                                                                                            <div class='form-inline'><input type='radio' name='answer' value='E' " . ($row1['answer'] == 'E' ? 'checked' : '') . "> &nbsp;E) &nbsp;<input type='text' class='form-control'  name='option5' value='" . $row1['option5'] . "'></div>
                                                                                        </div>
                                                                                    </div>";
                                                }

                                                echo " <br><br>
                                                                                    <div class='row'>
                                                                                        <div class='col-md-8 col-md-offset-2'>
                                                                                            <div>
                                                                                                <textarea rows='5' name='explain' class='form-control'>" . $row1['exp'] . "</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div><br>
                                                                                    <input type='submit' class='form-control oq-btn' value='Update question'  name='upques'>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                  </div>
                                                                </div>";

                                                echo "<div class='modal fade del" . $row1['q_id'] . "' tabindex='-1' role='dialog' aria-labelledby='mySmallModalLabel'>
                                                                  <div class='modal-dialog modal-sm' role='document'>
                                                                    <div class='modal-content'>
                                                                        <div class='modal-body'>
                                                                            <div class='oq-questionModal'>
                                                                                <span>Are you sure you want to delete?</span><br><br>
                                                                                <form class='form' action='quesdel.php' method='post'>
                                                                                    <input type='hidden' name='delval' value='" . $row1['q_id'] . "'>
                                                                                    <input type='submit' name='qusdelete' value='Yes' class='oq-deletebtn form-control'><br>
                                                                                    <input type='button' value='No' class='oq-btn form-control' data-dismiss='modal'> 
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                  </div>
                                                                </div>";
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

        <script>
            function hideOptions() {

                var descriptive_option = document.getElementById("descriptive");
                var none_option = document.getElementById("none");

                var objective_options = document.getElementById("objective_options");
                console.log("called", descriptive_option.checked)
                if (descriptive_option.checked == true || none.checked == true) {
                    objective_options.style.display = "none";
                } else {
                    objective_options.style.display = "block";

                }
            }
        </script>

    </body>

    </html>

<?php
}
?>