<?php
include_once 'translator.php';

// Assuming language selection is done elsewhere (e.g., user preference stored in session)
$selectedLanguage = "en";

?>
<?php
session_start();
require_once('dbconfig.php');
$id = $_SESSION['adminsession'];
$lang = $_SESSION['lang'];
$test = $_SESSION['test'];
$medium = $_SESSION['medium'];

if ($id == null) {
	header('Location: index.php');
}
$testtitle = $lang . ' ' . $test . ' ' . $medium;
$translator = new Translator($medium);
$langcode = $translator->getLanguageCode($medium);
$_SESSION['adminsession'] = $id;
$_SESSION['test'] = $test;
$userid = $_SESSION['usersession'];


// if($result = mysqli_query($conn,"SELECT answer FROM `$testtitle`")){
//     $quscount = mysqli_num_rows($result);
//     $answer = array();
//     $i=1;
//     while($row1 = mysqli_fetch_assoc($result)){
//         $answer[$i] = $row1['answer'];
//         $i++;
//     }
// } 
// error_log("userid:".$userid);
// error_log("test:".$test);
 error_log("langcode:".$langcode);


?>
<!DOCTYPE html>
<html lang="<?php echo $selectedLanguage; ?>">

<head>
	<title>Welcome Online Exam</title>
	<link rel='stylesheet' type='text/css' href='css/bootstrap.css'>
	<link rel='stylesheet' type='text/css' href='main.css'>
	<link rel="stylesheet" type="text/css" href="css/mic.css">
	<link rel='stylesheet' type='text/css' href='css/font/flaticon.css'>
	<link href='https://fonts.googleapis.com/css?family=Fira+Sans|Josefin+Sans' rel='stylesheet'>
	<meta charset='UTF-8'>
	<meta name='description' content='Online Exam'>
	<meta name='author' content='Akhil Regonda'>
	<meta name='viewport' content='width=device-width, initial-scale=1.0'>
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

					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="oq-scoreBoardBody">
		<div class="oq-scoreBoard">
			<div id="scoreboard">

				<?php
				if (isset($_POST['submit'])) {
					$radio = array();
					$tQus = $_POST['actual_question'];

					if ($qatm = mysqli_query($conn, "SELECT q_id FROM result WHERE user_id = '$userid' AND test = '$test' ")) {
						$qatmcount = mysqli_num_rows($qatm);
					}

					error_log($testtitle);
					$row2 = mysqli_query($conn, "SELECT DISTINCT t.q_id, t.questions, t.q_type, t.marks, r.user_id, r.q_id FROM `$testtitle` t LEFT JOIN result r ON t.q_id = r.q_id AND r.user_id = '$userid' WHERE r.q_id IS NULL");
					$rowcount = mysqli_num_rows($row2);
					error_log("questions count:" . $rowcount);

					$unatm = $tQus - $qatmcount;
					echo" <b> <span>Language: </span> <span id='langcode'>  " . $langcode . " </span></br>";
					echo"  <span>Userid: </span> <span id='user_id'>  " . $id . " </span></b></br></br>";
					echo "<p id='summary'>". $translator->translate("totalQuestions")  . $tQus . "<br>". $translator->translate("answeredQuestions")  . $qatmcount . "</br>". $translator->translate("unansweredQuestion")  . $unatm . "</br> ". $translator->translate("sayYesorNo") ;"  </p>";

					if ($row2) {
						$q = 1;
						$unans = array();
						while ($unans2 = mysqli_fetch_assoc($row2)) {
							$unans[$q] = $unans2['questions'];
							$qtype[$q] = $unans2['q_type'];
							$qmarks[$q] = $unans2['marks'];
							
							if ($qtype[$q] != "none")
								echo "<p id='unanswered_ques" . $q . "'>" .$translator->translate("question")   .$q.   ": " . $unans[$q] . $translator->translate("marks") . $qmarks[$q] . " </p>";
								
							$q++;
						}
					}
				}

				// session_destroy();

				?>
			</div>
			<div>
				<!-- <p id="retake">To take another test say the word "Menu" or say the word "STOP" to stop.</p> -->
				
				<!-- <button class="record_btn float2" id="micButton" style="font-size: 17px; vertical-align: middle; margin-left: -30px;"></button> -->

				<button class="float3" style="font-size: 14px; vertical-align: middle; margin-bottom: 300px;" onclick="interrupt()" id="interrupt">Interrupt</button>
                            <button class="float2" style="font-size: 14px; vertical-align: middle; margin-bottom: 200px;" onclick="StopResume()" id="stop_resume">Pause</button>
                            <button class="speaker_btn float" id="speakerButton" style="font-size: 17px; vertical-align: middle;  margin-bottom: 100px;"></button>
                            <button class="record_btn float" id="micButton" onclick="micButtonClicked()" style="font-size: 17px; vertical-align: middle; margin-left: -30px;"></button>
                           
				<br><br>
				
				
				<p id="spresult"></p>
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
	<script src="js/js-loading-overlay.js"></script>
	<script src="js/synthesize.js"></script>
	<script src="js/transcribe.js"></script>
	<script src="js/RecordRTC.js"></script>
	<script src="js/control.js"></script>
	<script src="js/hark.js"></script>
	<script type="text/javascript">
		var resultPara = document.querySelector('#spresult');
		var test;
		var testThis;
		let translate;
		var langcode = (document.getElementById("langcode").textContent).trim();
		// var langcode = document.getElementById("langCode").textContent;
		async function loadLanguageFile(language) {
    		const response = await fetch(`./language/${language}.json`);
    		const data = await response.json();
    		return data;
		}

		// Function to get language text
		async function getLanguageText(language) {
    		const languageData = await loadLanguageFile(language);
    		return languageData;
		}
		async function getTranslation() {
    	translate = await getLanguageText(langcode);

		}

		getTranslation();

		speakScore();
		var i = 1;
		const j =<?php echo $q; ?>;
		
		function speakScore() {
			test = document.getElementById("summary").textContent;
			// testThis = new SpeechSynthesisUtterance(test);

			synthesize(test);

			speechResult = ""
			audio.onended = function(event) {
				isPlaying = false;
				speakerButton.classList.remove('speakering');


				if (speechResult.includes("no")) {
					console.log("RETURN---------")
					return;
				}
				micButton.click();
				console.log("mic button clicked!")

				document.addEventListener(eventCount, () => {

					var transcribed_text_tmp = transcribed_text.replace(/[.,]/g, '');
        			var speechResult = (transcribed_text_tmp.toLowerCase()).trim();
					console.log(speechResult)
					// speechResult = (transcribed_text.toLowerCase()).trim()

					if (speechResult.includes("yes")) {

						// unanswered_ques_text = document.getElementById("unanswered_ques" + i).textContent;
						// synthesize(unanswered_ques_text);
						console.log("Inside yes")
						speak_unans(1);

					} else if (speechResult.includes("no")) {
						synthesize(translate.testSubmitted);
						// document.getElementById("summary").innerHTML = translate.testSubmitted
						audio.onended = function(event) {
							window.location.replace("logout.php");
						}
					} else {
						synthesize("no such operation");
						speakScore();
					}
				});


			}
			function speak_unans(i){
				
				unanswered_ques_text = document.getElementById("unanswered_ques" + i).textContent;

				synthesize(unanswered_ques_text);

                    audio.onended = function(event) {
						isPlaying = false;
						i++;
						if(i<j){ 
						speak_unans(i);
						}else{
							synthesize(translate.testSubmitted);
							audio.onended = function(event) {
								window.location.replace("logout.php");
							}
						}
                    }
			}
		}

	</script>
</body>

</html>