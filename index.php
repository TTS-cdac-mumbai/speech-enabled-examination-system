<?php
/*
###########################################################################
##                                                                       ##
##                Centre for Development of Advanced Computing           ##
##                               Mumbai                                  ##
##                         Copyright (c) 2024                            ##
##                        All Rights Reserved.                           ##
##                                                                       ##
##  Permission is hereby granted, free of charge, to use and distribute  ##
##  this software and its documentation without restriction, including   ##
##  without limitation the rights to use, copy, modify, merge, publish,  ##
##  distribute, sublicense, and/or sell copies of this work, and to      ##
##  permit persons to whom this work is furnished to do so, subject to   ##
##  the following conditions:                                            ##
##   1. The code must retain the above copyright notice, this list of    ##
##      conditions and the following disclaimer.                         ##
##   2. Any modifications must be clearly marked as such.                ##
##   3. Original authors' names are not deleted.                         ##
##   4. The authors' names are not used to endorse or promote products   ##
##      derived from this software without specific prior written        ##
##      permission.                                                      ##
##                                                                       ##
##                                                                       ##
###########################################################################
##                                                                       ##
##              Speech Enabled Examination System                        ##
##                                                                       ##
##            Designed and Developed by Languge Computing Group          ##
##          		       Date:  Nov 2023                               ##
##                                                                       ## 
###########################################################################
*/


    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
   require_once('dbconfig.php');
   $fail =0;
   if(isset($_POST['login'])){
       $uid = $_POST['userid'];
       $upass = $_POST['userpass'];
       $password = md5($upass);
       $result=mysqli_query($conn,"SELECT * FROM user WHERE userid = '$uid' AND password = '$password'");
       if(mysqli_num_rows($result) > 0){
           
            session_start();
            
            $_SESSION['usersession'] = $uid;
            error_log("Admin logged in sucessfully....");

            header("Location: sublist.php");
        }
        else{
            error_log("User tried logged in....");
            $fail = 1;
        }
    }
  
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Welcome to Examination System for Visually Impared People</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="main.css">
        <link href="https://fonts.googleapis.com/css?family=Fira+Sans|Josefin+Sans" rel="stylesheet">
        <meta charset="UTF-8">
        <meta name="description" content="Online Exam">
        <meta name="author" content="Sukanya Ledalla, Akhil Regonda, Nishanth Kadapakonda">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
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
                    <div class="col-md-2 pull-right">
                        <div class="oq-adminArea">
                            <a class="oq-admin" href="admin.php">Admin Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="oq-indexBody">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-7">
                        <div class="oq-bodyContent">
                            <h1>Welcome to Examination System for Visually Impared People</h1>
                            <p>This Site will provide the quiz for various subject of interest. You need to login for online quiz.</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-md-offset-1">
                        <div class="oq-login text-center">
                            <!-- <img src="images/quiz_1.png" class="oq-logo"><br><br> -->
                            <form class="form" action="" method="post"  autocomplete="off">
                                <?php
                                    if($fail == 1){
                                        echo "<span class='oq-error'>*Incorrect details</span><br><br>";
                                    }
                                    if(isset($_GET['signup'])){
                                        echo "<span class='oq-success'>Signup successful please login</span><br><br>";
                                    }
                                ?>
                                <input type="text" class="form-control" placeholder="Enter your Login ID" name="userid"><br>
                                <input type="password" class="form-control" placeholder="Enter your Password" name="userpass"><br>
                                <input type="submit" class="form-control oq-btn" value="Login" name="login"><br><br>
                                New user? <a href="signup.php" class="">Signup for New Account</a><br><br>
                            </form> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="oq-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6"><span class="oq-footerText">ONLINE QUIZ v0.3.9</span></div>
                    <div class="col-md-6"><span class="oq-footerText pull-right">Developed by - <a href="https://www.cdac.in/"><span class="oq-footerBy">CDAC Mumbai</a></span></div>
                </div>
            </div>
        </div>
        <script src="js/jquery-3.7.1.min.js"></script>
        <script src="js/bootstrap.js"></script>
    </body>
</html>