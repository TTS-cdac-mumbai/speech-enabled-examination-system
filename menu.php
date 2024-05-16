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
                                <span class="oq-username"> welcome <?php echo $row['username']; ?> </span>
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
                                <span id="oq-subjectsList"><span class="oq-menuHead">Welcome to online quiz <?php echo $row['username']; ?></span><br><br>To know about all the subjects, say List the subjects .</span>
                                <p id="spresult" class="spresult"></p>

                                <button class="record_btn float" id="micButton" style="font-size: 17px; vertical-align: middle; margin-left: -30px;"></button>
                                <!-- <audio src="" controls autoplay type="audio/mpeg" id="audio" style="width: 200px;"> -->
                                <br><br><br><br>
                                <button onclick="StopResume()" id="stop_resume">pause</button>
                                <button onclick="next()"> NEXT</button>
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
            <script src="js/js-loading-overlay.js"></script>
            <script src="js/synthesize.js"></script>
            <script src="js/transcribe.js"></script>
            <script src="js/RecordRTC.js"></script>
            <script src="js/control.js"></script>
            <script src="js/hark.js"></script>
            <script type="text/javascript">
                // var syn = window.speechSynthesis;
                // var SpeechRecognition = SpeechRecognition || webkitSpeechRecognition;
                // var SpeechGrammarList = SpeechGrammarList || webkitSpeechGrammarList;
                // var SpeechRecognitionEvent = SpeechRecognitionEvent || webkitSpeechRecognitionEvent;

                var lsub = document.getElementById("oq-subjectsList").textContent;
                // alert("lsub=" + lsub);



                //var slsub = new SpeechSynthesisUtterance(lsub);
                //syn.speak(slsub);
                synthesize(lsub); //changed 

                // document.addEventListener('synthesisFinish', ()=>{
                audio.onended = function(event) {
                    isPlaying = false;
                    console.log("end");
                    subjects();
                }
                // });


                // slsub.onend = function(event) {
                //     console.log("end");
                //     subjects();
                // }


                var resultPara = document.querySelector('.spresult');
                //var word = "list the subjects | list the subject | subject | subjects";

                function subjects() {
                    // var flag=0;
                    // console.log("subjects");
                    // var grammar = '#JSGF V1.0; grammar phrase; public <phrase> = ' + word +';';
                    // var recognition = new SpeechRecognition();
                    // var speechRecognitionList = new SpeechGrammarList();
                    // speechRecognitionList.addFromString(grammar, 1);
                    // recognition.grammars = speechRecognitionList;
                    // recognition.lang = 'en-US';
                    // recognition.interimResults = false;
                    // recognition.maxAlternatives = 1;

                    micButton.click();

                    document.addEventListener(eventCount, () => {

                        console.log("transcribeFinish Event :: text : ", transcribed_text)
                        var speechResult = transcribed_text;
                        if (speechResult.match('subject') == "subject") {
                            // if(speechResult == 'subject list' || speechResult == 'list the subjects' || speechResult == 'list the subject' || speechResult == 'subject' || speechResult == 'subjects' || speechResult == 'the subject') {
                            flag = 1;
                            console.log("speech result: " + speechResult);
                            resultPara.textContent = 'Speech received: ' + speechResult + '.';
                            // var syn = window.speechSynthesis;
                            var testhead = document.getElementById("spresult").textContent;
                            console.log("speak : ", testhead);

                            synthesize(testhead); //changed 

                            // document.addEventListener('synthesisFinish', ()=>{
                            audio.onended = function(event) {
                                isPlaying = false;
                                console.log("gej");
                                window.location = "sublist.php";
                            }
                            // });

                            // audio.onended = function(event) {
                            //     console.log("gej");
                            //     window.location = "sublist.php";
                            // }

                            //var testThis = new SpeechSynthesisUtterance(testhead);
                            // syn.speak(testThis);
                            //console.log(testThis);
                            //testThis.onend = function(event) {
                            //       console.log("gej");
                            //       window.location = "sublist.php";
                            // }
                        } else {
                            flag = 2;
                            resultPara.textContent = 'Speech received: ' + speechResult + '. No such operation.';
                            // var syn = window.speechSynthesis;
                            var noOper = document.getElementById("spresult").textContent;
                            console.log("speak : ", noOper);

                            synthesize(noOper); //changed 

                            // document.addEventListener('synthesisFinish', ()=>{
                            audio.onended = function(event) {
                                isPlaying = false;
                                console.log('No such operation over');
                                speakSub();
                            }
                            // });

                            // audio = synthesize(noOper);                  //changed 

                            // audio.onended = function(event) {
                            //     console.log('No such operation over. speak time:');
                            //     speakSub();
                            // }

                            // var noOpr = new SpeechSynthesisUtterance(noOper);
                            //syn.speak(noOpr);
                            // console.log("-else");
                            // console.log(noOpr);
                            // noOpr.onend = function(event) {
                            //     console.log('No such operation over. speak time:' + event.elapsedTime + ' milliseconds.');
                            //     speakSub();
                            // }
                        }

                    });


                    //recognition.start();
                    //     recognition.onresult = function(event) {
                    //         var speechResult = event.results[0][0].transcript;
                    //         if(speechResult == 'list the subjects' || speechResult == 'list the subject' || speechResult == 'subject' || speechResult == 'subjects' || speechResult == 'the subject') {
                    //             flag = 1;
                    //             console.log("speech result: "+speechResult);
                    //             resultPara.textContent = 'Speech received: ' + speechResult + '.';
                    //             var syn = window.speechSynthesis;
                    //             var testhead = document.getElementById("spresult").textContent;
                    //             var testThis = new SpeechSynthesisUtterance(testhead);
                    //             syn.speak(testThis);
                    //             console.log(testThis);
                    //             testThis.onend = function(event) {
                    //                    console.log("gej");
                    //                    window.location = "sublist.php";
                    //             }
                    //         }else {
                    //             flag = 2;
                    //             resultPara.textContent = 'Speech received: ' + speechResult + '. No such operation.';
                    //             var syn = window.speechSynthesis;
                    //             var noOper = document.getElementById("spresult").textContent;


                    //             var noOpr = new SpeechSynthesisUtterance(noOper);
                    //             syn.speak(noOpr);


                    //             console.log("-else");
                    //             console.log(noOpr);
                    //             noOpr.onend = function(event) {
                    //                 console.log('No such operation over. speak time:' + event.elapsedTime + ' milliseconds.');
                    //                 speakSub();
                    //             }
                    //         }
                    //     }
                    //     recognition.onend = function() {
                    //         console.log("onend");
                    //         if(flag == 1 || flag == 2){
                    //             console.log("onend if");
                    //             console.log("flag :"+flag);
                    //             recognition.stop();
                    //         }
                    //         else{
                    //             if(flag == 4){
                    //                 console.log("flag :"+flag);
                    //                 recognition.stop();
                    //             }
                    //             else{
                    //                 console.log("onend else else");
                    //                 recognition.stop();
                    //                 console.log("flag :"+flag);
                    //                 speakSub();
                    //             }
                    //         }
                    //     }

                    //     recognition.onerror = function(event) {
                    //         flag = 4;
                    //         console.log("flag :"+flag);
                    //         speakSub();
                    //     }
                }


                function speakSub() {
                    console.log("in speakSub");
                    var lsub = document.getElementById("oq-subjectsList").textContent;


                    // synthesize(noOper);                  //changed 

                    //         document.addEventListener('transcribeFinish', ()=>{
                    //             audio.onended = function(event) {
                    //             console.log('No such operation over');
                    //             speakSub();
                    //         }
                    //         });

                    synthesize(lsub);
                    // document.addEventListener('synthesisFinish', ()=>{
                    audio.onended = function(event) {
                        isPlaying = false;
                        console.log("end");
                        subjects();
                    }
                    // });

                    //  var slsub = new SpeechSynthesisUtterance(lsub);
                    // syn.speak(slsub);
                    // console.log("in speakSub");
                    // slsub.onend = function(event) {
                    //     console.log("speekSubend");
                    //     subjects();
                    // }
                }

                // function stop() {
                //     console.log("pause audio!");
                //     audio.pause();
                // }

                function next(){
                    window.location = "sublist.php";
                }

            </script>
        </body>

        </html>

<?php
    }
}
?>