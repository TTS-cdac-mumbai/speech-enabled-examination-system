<?php
session_start();
require_once('dbconfig.php');
$id = $_SESSION['adminsession'];
if ($id == null ){
    
 header('Location: admin.php');
}
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

            <script src="js/jquery-3.7.1.min.js"></script>
            <script src="js/bootstrap.js"></script>
           
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
                            <a href="adminmenu.php"> Back</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="adminmenu.php"><span class="glyphicon glyphicon-home"></span>&nbsp;&nbsp;Home</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a class="oq-btn" href="logout.php?logout">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>                    
            <div class="container">
                <div class="col-md-4  col-md-offset-4">
                    <form class="form" action="" method="post">
                        
                        <div>
                            <br>
                           
                                <br>
                            <input type='password' name= "pass" placeholder="enter pass key" >

                        </div><br>
                    <br>
                        <div>
                        <input type="submit" class="form-control oq-btn" value="Get Key"
                        name='checkKey'>
                        </div>
                        
                    </form>
                    <hr>
                    <?php
                    require_once('dbconfig.php');

                    

                    if (isset($_POST['checkKey'])) {
                        $sub = $_GET['lang'];
                        $pass = $_POST['pass'];
                        $test = $_GET['test'];

                        if($pass == "1231"){                
                             error_log("sub and test : " . $sub . $test );
                            
                            echo "<h3>".$sub . ":<h3>";
                            
                            $result = mysqli_query($conn, "SELECT * FROM `$sub` where tests = '$test' ");
                            if (mysqli_num_rows($result) > 0) {
                                error_log("found key ");
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<h4>".$row["tests"] . ": " . $row["eval_key"] . "</h4>";
                                }
                            } 
                        }
                    }

                    ?>
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
    </html>



