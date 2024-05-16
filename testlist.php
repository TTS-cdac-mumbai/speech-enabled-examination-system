<?php
session_start();
require_once('dbconfig.php');
$userid = $_SESSION['usersession'];
if ($userid == null) {
    header('Location: index.php');
}
// $subject = $_GET['subject'];

$result = mysqli_query($conn, "SELECT * FROM user WHERE userid = '$userid'");
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['usersession'] = $userid;

    
        $subject = $_GET['subject'];
        $test = $_GET['test'];
        $_SESSION['lang'] = $_GET['subject'];
        $_SESSION['test'] = $_GET['test'];

       
?>
        <!DOCTYPE html>
        <html>

        <head>
            <title>Welcome Online Exam</title>
            <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
            <link rel="stylesheet" type="text/css" href="main.css">
            <link rel="stylesheet" type="text/css" href="css/font/flaticon.css">
            <link rel="stylesheet" type="text/css" href="css/mic.css">
            <link href="https://fonts.googleapis.com/css?family=Fira+Sans|Josefin+Sans" rel="stylesheet">
            <meta charset="UTF-8">
            <meta name="description" content="Online Exam">
            <meta name="author" content="Sukanya Ledalla, Akhil Regonda, Nishanth Kadapakonda">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <?php
            require_once('dbconfig.php');
            ?>
            <?php     
                        $res4 = mysqli_query($conn, "SELECT * FROM `$subject` where tests='$test';");
                        echo "<script>var words = [],k = 0;</script>";    
                        if (mysqli_num_rows($res4) > 1) {                   
                            while($row4 = mysqli_fetch_assoc($res4)){
                                // echo "$row4[lang_medium] ";
                                // echo $test_list;
                                echo "<script>words[k] = '$row4[lang_medium]';
                                            console.log('Words:'+words);
                                            k++;
                                            </script>";
                            }
                         }else{
                            header("Location: test.php?medium=". mysqli_fetch_assoc($res4)[lang_medium]);
                         }
                         
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
                                <span class="oq-username"> welcome <span id="user_id"><?php echo $row['username']; ?></span> </span>
                                <a class="btn btn-primary" href="logout.php">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="oq-menuBody">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <div class="oq-menu">
                                <script type="text/javascript">
                                    document.write('<span id="oq-subjectsList">Your examination question paper for subject  <b> <?php echo $subject ?> </b> is available in the following languages, <br><br>');
                                    for (i = 0; i < k; i++) {

                                        document.write("<span>" + words[i] + ", </span><br>");

                                    }
                                    document.write('</br>Choos one language by saying it or say repeat to repeat the list</span>');
                                </script>
                                <p id="spresult" class="spresult"></p>
                                
                                <button class="record_btn float" id="micButton" onclick="micButtonClicked()"  style="font-size: 17px; vertical-align: middle; margin-left: -30px;"></button>
                                <button class="speaker_btn float" id="speakerButton" style="font-size: 17px; vertical-align: middle;  margin-bottom: 100px;"></button>
                                <br><br><br>
                                <button class="float2" style="font-size: 14px; vertical-align: middle; margin-bottom: 200px;" onclick="StopResume()" id="stop_resume">Pause</button>

                                <!-- <button onclick="next()"> NEXT </button> -->
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
            <script src="js/js-loading-overlay.js"></script>
            <script src="js/synthesize.js"></script>
            <script src="js/transcribe.js"></script>
            <script src="js/RecordRTC.js"></script>
            <script src="js/hark.js"></script>
            <script src="js/control.js"></script>
            <script src="js/mousetrap.min.js"></script>

            <script type="text/javascript">

                localStorage.clear();
                
                var langcode = "en";
                for (i = 0; i < k; i++) {
                    console.log('words ' + words[i]);
                }


                var lsub = document.getElementById("oq-subjectsList").textContent;
                console.log(lsub);

                synthesize(lsub.trim());

                audio.onended = function(event) {
                    isPlaying = false;
                    speakerButton.classList.remove('speakering');

                    console.log("end");
                    subjects();
                }

           

                var resultPara = document.querySelector('.spresult');

                function subjects() {
                    console.log("subjects");
                    micButton.click();
                    document.addEventListener(eventCount, () => {

                        console.log("transcribeFinish Event :: text : ", transcribed_text)
                        var speechResult = (transcribed_text.toLowerCase()).trim();

                        console.log("speechResult:" + speechResult);
                        console.log("words:" + words);
                        
                        for (var i = 0; i < k; i++) {
                            if (speechResult === words[i].toLowerCase()) {
                                flag = 1;
                                console.log("speech result: " + speechResult);
                                resultPara.textContent = 'Speech received: ' + speechResult + '.';

                                var testhead = document.getElementById("spresult").textContent;

                                synthesize(testhead);
                            

                                audio.onended = function(event) {
                                    isPlaying = false;

                                        window.location = "test.php?medium=" + speechResult;
                                }

                               
                                break;
                            } else if ((speechResult.toLowerCase()).trim() === "repeat") {
                                flag = 1;
                                console.log("speech result: " + speechResult);
                                resultPara.textContent = 'Speech received: ' + speechResult + '.';


                                var testhead = document.getElementById("spresult").textContent;

                                synthesize(testhead);

                                audio.onended = function(event) {
                                    isPlaying = false;

                                    speakSub();
                                }

                    
                                break;
                            }
                        }
                        if (i == k) {
                            flag = 2;
                            resultPara.textContent = 'Speech received: ' + speechResult + '. No such operation.';
                            // var syn = window.speechSynthesis;
                            var noOper = document.getElementById("spresult").textContent;

                            synthesize(noOper);

                            audio.onended = function(event) {
                                isPlaying = false;

                                console.log('No such operation over. speak time:' + event.elapsedTime + ' milliseconds.');
                                speakSub();
                            }
                        }
                    });
                }

                function speakSub() {
                    console.log("in speakSub");
                    var lsub = document.getElementById("oq-subjectsList").textContent;

                    synthesize(lsub);

                    audio.onended = function(event) {
                        isPlaying = false;

                        console.log("speekSubend");
                        subjects();
                    }
                }

            // play pause on space bar press
                $(window).keypress(function (e) {
                    if (e.key === ' ' || e.key === 'Spacebar') {
                        // ' ' is standard, 'Spacebar' was used by IE9 and Firefox < 37
                        e.preventDefault()
                        console.log('Space pressed')

                        StopResume();
                    }
                })
                
                 Mousetrap.bind('alt+h', function() { 
                    console.log("alt+n pressed");
                    window.location = "test.php?medium=hindi";
                 });
                 Mousetrap.bind('alt+n', function() { 
                    console.log("alt+n pressed");
                    window.location = "test.php?medium=english";
                 });
                 Mousetrap.bind('alt+m', function() { 
                    console.log("alt+n pressed");
                    window.location = "test.php?medium=marathi";
                 });

            </script>
        </body>

        </html>

<?php
    }
}
?>