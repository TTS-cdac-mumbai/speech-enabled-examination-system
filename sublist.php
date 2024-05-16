<?php
session_start();
require_once('dbconfig.php');
$userid = $_SESSION['usersession'];
if ($userid == null) {
    header('Location: index.php');
}
$result = mysqli_query($conn, "SELECT * FROM user WHERE userid = '$userid'");
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['usersession'] = $userid;
        
         $res3 = mysqli_query($conn, "SELECT * FROM set_test ORDER BY time DESC LIMIT 1;");
        if (mysqli_num_rows($res3) > 0) {
            while ($row3 = mysqli_fetch_assoc($res3)) {
                $selected_subject = $row3['selected_subject'];
                
                if ($selected_subject != "all" && $selected_subject != "none"){
                    $test = $row3['selected_test'];
                    $_SESSION['test'] = $row3['selected_test'];
                    $_SESSION['lang'] = $row3['selected_subject'];
                    header("Location: testlist.php?subject=".$row3['selected_subject']."&test=".$row3['selected_test']);

                }
            }
        }    
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
          
                $result1 = mysqli_query($conn, "SELECT * FROM   lang");
                echo "<script>var lang_test = [],k = 0;</script>";
                if (mysqli_num_rows($result1) > 0 && $selected_subject != "none") {
                    while ($row1 = mysqli_fetch_assoc($result1)) {
                        $sub = $row1['subjects'];
                        $result2 = mysqli_query($conn, "SELECT * FROM  `$sub`");
                        while ($row2 = mysqli_fetch_assoc($result2)) {
                            $available_tests = $row1['subjects'] . " " . $row2['tests'];
                                
                                echo "<script>lang_test[k] = ". json_encode($available_tests) . ";
                                            k++;
                                        
                                            </script>";
                        }
                    }
                
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
                                <span class="oq-username"> welcome <span id="user_id"><?php echo $row['username']; ?> </span> </span>
                                <a class="btn btn-primary" href="logout.php?logout">Logout</a>
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
                                    // remove dulicate enteries
                                    lang_test = [...new Set(lang_test)];
                                    if (lang_test.length == 0){
                                        document.write('<span id="oq-subjectsList"><b>No test is scheduled </b>, Please contact administrator. <br><br>');
                                    }else{
                                        document.write('<span id="oq-subjectsList"><b>Following are the available subjects :</b>, <br><br>');
                                        for (word in lang_test){ 
                                            document.write("<span>" + lang_test[word] + ", </span><br>");
    
                                        }
                                        document.write('<br> Choose one by saying the subject name or say repeat to repeat the list.</span>');
                                    }
                                </script>
                                <p id="spresult" class="spresult"></p>

                                <button class="record_btn float" id="micButton" onclick="micButtonClicked()" style="font-size: 17px; vertical-align: middle; margin-left: -30px;"></button>
                                <button class="speaker_btn float" id="speakerButton" style="font-size: 17px; vertical-align: middle;  margin-bottom: 100px;"></button>
                                <br><br><br>
                                <button class="float2" style="font-size: 14px; vertical-align: middle; margin-bottom: 200px;" onclick="StopResume()" id="stop_resume">Pause</button>
                          

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

                var langcode = "en";
                
            
                console.log(lang_test);

                         
                var lsub = document.getElementById("oq-subjectsList").textContent;
                console.log(lsub);


                synthesize(lsub);

                audio.onended = function(event) {
                    isPlaying = false;
                    speakerButton.classList.remove('speakering');
                    console.log("end");
                    if(lang_test.length != 0)
                        subjects();
                }

                //for testing
                // subjects();


                var resultPara = document.querySelector('.spresult');

                function subjects() {

                    console.log("subjects");

                    micButton.click();

                    document.addEventListener(eventCount, () => {

                        console.log("transcribeFinish Event :: text : ", transcribed_text)
                        var speechResult = transcribed_text;

                        // console.log("speechResult:" + speechResult);
                        // console.log("lang_test:" + lang_test);
                        found_element = false;
                        speechResult = speechResult.replace(/[.,]/g, '').toLowerCase().trim();

                        const words = speechResult.split(" ");
                   
                            if( words[words.length - 1] == "one"){
                                words[words.length - 1] = "1";
                                speechResult = words.join(" "); 
                            }

                        for (i in lang_test) {
                            console.log("Match : ", speechResult, "==" , lang_test[i])
                            
                            if (speechResult == lang_test[i]) {
                                found_element = true;
                             
                                // console.log("speech result: " + speechResult);
                                resultPara.textContent = 'Speech received: ' + speechResult + '.';

                                var testhead = document.getElementById("spresult").textContent;

                                synthesize(testhead);

                                audio.onended = function(event) {
                                    isPlaying = false;

                                    lang_test_array = lang_test[i].split(" ")
                                    test = lang_test_array[lang_test_array.length - 2] + " " + lang_test_array[lang_test_array.length - 1]; 
                                    lang_test_array.pop()
                                    lang_test_array.pop()
                                    sub =""
                                    for (i in lang_test_array)
                                        sub += lang_test_array[i] + " "

                                    console.log(sub , " " , test)
                                        
                                    window.location = "testlist.php?subject=" + sub.trim() + "&test=" + test;
                                }
                                break;
                            } else if ((speechResult.toLowerCase()).trim() === "repeat") {
                                found_element = true;
                                console.log("speech result: " + speechResult);
                                resultPara.textContent = 'Speech received: ' + speechResult + '.';
                                var testhead = document.getElementById("spresult").textContent;

                                synthesize(testhead);

                                audio.onended = function(event) {
                                    isPlaying = false;
                                    speakerButton.classList.remove('speakering');
                                    speakSub();

                                }
                                break;
                            }
                        }

                        if (found_element == false) {
                            console.log("Not matched")
                            resultPara.textContent = 'Speech received: ' + speechResult + '. Not matched. Please speak again';

                            var noOper = document.getElementById("spresult").textContent;

                            synthesize(noOper);

                            audio.onended = function(event) {
                                isPlaying = false;
                                speakerButton.classList.remove('speakering');
                                subjects();
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

                // for testing
                Mousetrap.bind('alt+n', function() { 
                    console.log("alt+n pressed");
                    window.location = "testlist.php?subject=economics&test=term%201";
                 });

            </script>
        </body>

        </html>

<?php
    }
}
?>
