<?php 
//   if (isset($_COOKIE[session_name()])) {
//     setcookie(session_name(), '', time() - 3600, '/');
// }
require_once('dbconfig.php');
// $query2=mysqli_query($conn,"SELECT sessionid from loginlogs where id='quizadmin' ORDER BY time DESC LIMIT 1");

// $stored_session_id = mysqli_fetch_assoc($query2)["sessionid"];
// session_start();
// session_destroy();

session_start();
$fail = 0;
$msg='';
if(isset($_POST['login'])){
    
    $time=time()-30;
    $ip_address=getIpAddr();

    // Getting total count of hits on the basis of IP
    $query=mysqli_query($conn,"SELECT count(*) as total_count from loginlogs where TryTime > $time and IpAddress='$ip_address'");
    $check_login_row=mysqli_fetch_assoc($query);
    $total_count=$check_login_row['total_count'];
    //Checking if the attempt 3, or youcan set the no of attempt her. For now we taking only 3 fail attempted
    error_log($total_count);
    if($total_count==2){
    $fail = 1;
    $msg="To many failed login attempts. Please login after 30 sec";
    }else{
        $aid = $_POST['adminid'];
        $apass = $_POST['adminpass'];
    
        $password = md5($apass);

        error_log($_SESSION['captcha_code']);
        error_log($_POST['captcha_code']);
        

        $result = mysqli_query($conn,"SELECT * FROM admin WHERE loginid = '$aid' AND pass = '$password'");
        if(mysqli_num_rows($result)){
            if(strcasecmp($_SESSION['captcha_code'], $_POST['captcha_code']) != 0){  
                $msg2="<span style='color:red'>The Validation code does not match!</span>";// Captcha verification is incorrect.		
            }else{// Captcha verification is Correct. Final Code Execute here!		
                $msg2="<span style='color:green'>The Validation code has been matched.</span>";		
                
                $current_session_id=session_id();
                $_SESSION['adminsession'] = $aid;
                error_log("new Session id : " . $current_session_id);
                session_commit();

                // 3. hijack then destroy session specified.
                $query2=mysqli_query($conn,"SELECT sessionid from loginlogs where id='quizadmin' ORDER BY login_time DESC LIMIT 1");

                $stored_session_id = mysqli_fetch_assoc($query2)["sessionid"];
                error_log("previous Session id : " . $stored_session_id);
                session_id($stored_session_id);
                session_start();
                session_destroy();
                session_commit();

                // 4. restore current session id. If don't restore it, your current session will refer to the session you just destroyed!
                session_id($current_session_id);
                session_start();
                session_commit();
                
                // mysqli_query($conn,"UPDATE loginlogs set sessionid='$current_session_id' where id='quizadmin' ORDER BY login_time DESC LIMIT 1");
                mysqli_query($conn,"INSERT into loginlogs(id, IpAddress,TryTime,sessionid) values('$aid', '$ip_address','0000','$current_session_id')");

                error_log("Admin logged in sucessfully....");
                header("Location: adminmenu.php");
            }   
        }
        else{
            // echo "error!";
            $fail = 1;
            $aid = $_POST['adminid'];
            $total_count++;
            $rem_attm=3-$total_count;
            if($rem_attm==0){
            $msg="To many failed login attempts. Please login after 30 sec";
            }else{
            $msg="Please enter valid login details.<br/>$rem_attm attempts remaining";
            }
            $try_time=time();
            $current_session_id=session_id();
            error_log("Admin tried logged in....");
            mysqli_query($conn,"INSERT into loginlogs(id, IpAddress,TryTime,sessionid) values('$aid', '$ip_address','$try_time','$current_session_id')");

        }
    }
}

// Getting IP Address
function getIpAddr(){
    if (!empty($_SERVER['HTTP_CLIENT_IP'])){
    $ipAddr=$_SERVER['HTTP_CLIENT_IP'];
    }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
    $ipAddr=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
    $ipAddr=$_SERVER['REMOTE_ADDR'];
    }
    return $ipAddr;
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Welcome to Online Exam</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="main.css">
        <link href="https://fonts.googleapis.com/css?family=Fira+Sans|Josefin+Sans" rel="stylesheet">
        <script src="https://www.google.com/recaptcha/api.js"></script>

        <meta charset="UTF-8">
        <meta name="description" content="Online Exam">
        <meta name="author" content="Sukanya Ledalla, Akhil Regonda, Nishanth Kadapakonda">
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
                    <div class="col-md-6"></div>
                </div>
            </div>
        </div>
        <div class="oq-adminloginBody">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="oq-adminlogin text-center">
                            <!-- <img src="images/quiz_1.png" class="oq-logo"><br><br> -->
                            <span class="oq-signupHead">Admin login</span><br><br>
                            <div style="color:red;">
                            <?php if($fail == 1){
                                    echo $msg;
                                    }
                            ?>
                            </div>
                            <form class="form" action="" method="post" autocomplete="off">
                                <input type="text" class="form-control" placeholder="Enter you Login ID" name="adminid" required autofocus ><br>
                                <input type="password" class="form-control" placeholder="Enter your Password" name="adminpass" required><br>

                                <?php if(isset($msg2)){?>
                                    <table>
                                    <tr>
                                    <td colspan="2" align="center" valign="top"><?php echo $msg2;?></td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                    <td align="right" valign="top"> Validation code:</td>
                                    <td><img src="captcha.php?rand=<?php echo rand();?>" id='captchaimg'><br>
                                        <label for='message'>Enter the code above here :</label>
                                        <br>
                                        <input id="captcha_code" name="captcha_code" type="text" required>
                                        <br>
                                        Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh.</td>
                                    </tr>
                                    <!-- <tr> -->
                                    <!-- <td>&nbsp;</td> -->
                                    <!-- <td><input name="Submit" type="submit" onclick="return validate();" value="Submit" class="button1"></td> -->
                                    <!-- </tr> -->
                                </table>

                                    <br><br>
                                <input type="submit" class="form-control btn btn-primary" value="Login" name="login"><br><br>

                            </form> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="oq-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6"><span class="oq-footerText">ONLINE QUIZ</span></div>
                    <div class="col-md-6"></div>
                </div>
            </div>
        </div>
        <script src="js/jquery-3.7.1.min.js"></script>
        <script src="js/bootstrap.js"></script>
    </body>


    <script type='text/javascript'>
function refreshCaptcha(){
	var img = document.images['captchaimg'];
	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}
</script>
</html>