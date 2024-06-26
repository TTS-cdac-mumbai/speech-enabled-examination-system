<?php
session_start();
require_once('dbconfig.php');
$id = $_SESSION['adminsession'];
if ($id == null || $_SESSION['usersession'] != null ) {
    header('Location: admin.php');
}
if (isset($_GET['viewtest']) || isset($_GET['testlang'])) {
    $lang = $_GET['testlang'];
    $result = mysqli_query($conn, "SELECT * FROM lang WHERE subjects = '$lang'");
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['lang'] = $lang;
            $_SESSION['adminsession'] = $id;
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
                                        <span class="oq-testsHeadText"><?php echo strtoupper($lang); ?></span>
                                        <div class="pull-right">
                                            <?php
                                            if (isset($_GET['error'])) {
                                                echo "<span class='oq-error'>*Test already exists! </span>";
                                            }
                                            ?>
                                            <a class="oq-addbtn" data-toggle="modal" data-target=".newtest">Add New Test</a>
                                        </div><br><br>
                                        <span>List of tests are shown below:</span>
                                    </div>
                                    <div class="modal fade newtest" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <form class="form" action="createtest.php" method="post">
                                                        <span>Test Title</span><br>
                                                        <span>(Keep test title same if you want same test in different language)</span>
                                                        <input type="text" pattern="[A-Za-z0-9 ]{4,20}" title="provide valid test name" class="form-control" placeholder="Enter test title" name="title" required><br>
                                                        <span>Test Time</span><br><br>
                                                        <input type="number" class="form-control" placeholder="Time limit for the test (in minutes)" name="testtime" required><br>
                                                        <span>Total Marks</span><br><br>
                                                        <input type="number" class="form-control" placeholder="Maximum marks of test" name="maxmarks" required><br>
                                                        <span>Exam Pattern</span><br><br>
                                                        <textarea class='form-control' name='exam_pattern' placeholder="Enter the exam pattern" ></textarea><br>
                                                        <!-- <input type="text" class="form-control" name="exam_pattern" ><br><br> -->

                                                        <span>Language</span><br>
                                                      
                                                        <select name="lang_medium" id="language" class='form-control'>
                                                           <option value="english">English</option>
                                                            <option value="hindi">Hindi</option>
                                                            <option value="marathi">Marathi</option>
                                                            
                                                        </select><br><br>
                                                        <input type="submit" class="form-control oq-btn" value="Create" name="newtest" >
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <table class="table oq-table">
                                        <tbody>
                                            <?php
                                            $res = mysqli_query($conn, "SELECT * FROM `$lang`");
                                            if (mysqli_num_rows($res) > 0) {
                                                echo "<tr><th>S no.</th><th>Test Name</th><th>Language</th><th>Time</th><th>Total Marks</th><th>Operations</th></tr>";
                                                $i = 1;
                                                while ($row1 = mysqli_fetch_assoc($res)) {
                                                    echo "<tr><td>" . $i . "</td><td>" . htmlspecialchars(ucfirst($row1['tests'])) . "</td><td>" . ucfirst($row1['lang_medium']) . "</td><td>" . $row1['testtime'] . " Mins</td> <td> ". $row1['total_marks']." </td> <td class='oq-testsoperations'><a data-toggle='modal' data-target='." . $row1['t_id'] . "' class='oq-editbtn'><span class='glyphicon glyphicon-pencil'></span> Edit</a> &nbsp;<a href='viewquestions.php?test=" . $row1['tests'] . "&medium=" . $row1['lang_medium'] . "' class='oq-btn'><span class='glyphicon glyphicon-pencil'></span> Add/Modify questions</a> &nbsp;<a data-toggle='modal' data-target='.del" . $row1['t_id'] . "' class='oq-deletebtn'><span class='glyphicon glyphicon-remove'></span> Delete</a></td></tr>";
                                                    $i++;

                                                    echo "<div class='modal fade " . $row1['t_id'] . "' tabindex='-1' role='dialog' aria-labelledby='mySmallModalLabel'>
                                                                          <div class='modal-dialog' role='document'>
                                                                            <div class='modal-content'>
                                                                                <div class='modal-body'>
                                                                                    <form class='form' action='uptests.php' method='get'>
                                                                                        <input type='hidden' value='" . $row1['t_id'] . "' name='tid'>
                                                                                        <input type='hidden' value='" . htmlspecialchars($row1['tests']) . "' name='test'>
                                                                                        <span>Test Title</span><br><br>
                                                                                        <span>(Keep test title same if you want same test in different language)</span>
                                                                                        <input type='text' class='form-control' value='" . htmlspecialchars($row1['tests']) . "' name='title' required><br><br>



                                                                                        <span>Time Limit</span><br><br>
                                                                                        <input type='text' class='form-control' value='" . $row1['testtime'] . "' name='testtime' required><br>
                                                                                        <span>total Marks</span><br><br>
                                                                                        <input type='number' class='form-control' value='" . $row1['total_marks'] . "' name='maxmarks' required><br>
                                                                                        <span>Exam Pattern</span><br><br>
                                                                                        <textarea class='form-control' name='exam_pattern' > ". htmlspecialchars($row1['exam_pattern']) . "</textarea><br>
                                                                                        <span>Test Language</span><br><br>
                                                                                        <input type='text' class='form-control' value='" . $row1['lang_medium'] . "' name='lang_medium' required><br>
                                                                                        <input type='submit' class='form-control oq-btn' value='Update' name='update'>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                          </div>
                                                                        </div>";


                                                    echo "<div class='modal fade del" . $row1['t_id'] . "' tabindex='-1' role='dialog' aria-labelledby='mySmallModalLabel'>
                                                                              <div class='modal-dialog modal-sm' role='document'>
                                                                                <div class='modal-content'>
                                                                                    <div class='modal-body'>
                                                                                        <div class='oq-questionModal'>
                                                                                            <span>Are you sure you want to delete?</span><br><br>
                                                                                            <form class='form' action='quesdel.php' method='post'>
                                                                                                <input type='hidden' name='dropval' value='" . htmlspecialchars($row1['tests']) . "'>
                                                                                                <input type='submit' name='testdelete' value='Yes' class='oq-deletebtn form-control' required><br>
                                                                                                <input type='button' value='No' class='oq-btn form-control' data-dismiss='modal'> 
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                              </div>
                                                                            </div>";
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
