// included in test.php

// var resume_question = localStorage['resume'] || -1;
var resume_question;

var user = localStorage["user"] || null;
if (user == null || user != userID){
    localStorage["user"] = userID
    resume_question = -1
    localStorage['resume'] = -1
}else{
    resume_question = localStorage['resume'] || -1;
}

console.log("previous user ", user, "current user", userID);
console.log("Resumed question : ", resume_question);

// to translate the variables as per user selected language
let translate;
var langcode = document.getElementById("langCode").textContent;
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

// *****************************


var count = document.getElementById("rowscount").value;
console.log("No of question : ", count);
console.log("Question IDs :", question_ids_nos)
var i = 1;

var testname = document.getElementById("testHead").textContent;

// synthesize(testname);
// audio.onended = function (event) {
//     isPlaying = false;
//     speakerButton.classList.remove('speakering');
//     readTimerQuestion();
// }

// *** for testing *****

// setTimeout(function () { readQuestion(1); }, 2000);
// navigateExam(2, 0)

startExam();

function startExam() {
    if (resume_question != -1) {
        synthesize("your were in question number " + Object.values(question_ids_nos)[resume_question-1] + ", do you want to resume say yes or no", "en");
        audio.onended = function (event) {
            isPlaying = false;
            speakerButton.classList.remove('speakering');

            micButton.click();
            document.addEventListener(eventCount, () => {
                console.log(eventCount, " :: text : ", transcribed_text)

                var transcribed_text_tmp = transcribed_text.replace(/[.,]/g, '');
                var speechResult = (transcribed_text_tmp.toLowerCase()).trim();
                // console.log(speechResult, translate.wordInstruction  )
                console.log(speechResult)

                if (speechResult.includes("yes")) {
                    readQuestion(resume_question)
                } else if (speechResult.includes("no")) {
                    // clear all the answers [not in db]
                    for (index = 1; index <= Object.values(question_ids_nos).length; index++) {

                        var qtype = document.getElementById("typeId" + index).value;
                        if(qtype == "descriptive"){
                            texarea_id = "answer" + index
                            document.getElementById(texarea_id).value = ""
                        }
                        if(qtype == "objective"){
                            const radioButtons = document.getElementsByName('qus'+index);
                            for (let i = 0; i < radioButtons.length; i++) 
                                radioButtons[i].checked = false;

                        }
                        
                        
                    }
                    synthesize(testname);
                    audio.onended = function (event) {
                        isPlaying = false;
                        speakerButton.classList.remove('speakering');

                        readTimerQuestion();
                    }
                } else {
                    synthesize("sorry we are not able to understand you. ");
                    audio.onended = function (event) {
                        isPlaying = false;
                        speakerButton.classList.remove('speakering');
                        startExam();
                    }
                }
            });
        }
    } else {
        synthesize(testname);
        audio.onended = function (event) {
            isPlaying = false;
            speakerButton.classList.remove('speakering');
            readTimerQuestion();
        }
    }
}



function readIntro() {
    var instructHead = document.getElementById("sayInstructions").textContent;
    synthesize(instructHead);

    audio.onended = function (event) {
        isPlaying = false;
        speakerButton.classList.remove('speakering');
        instructORstart();
    }
}

var resultPara = document.querySelector('.spresult');
var resultPara7 = document.querySelector('.spresult7');
var resultPara8 = document.querySelector('.spresult8');

function instructORstart() {

    console.log("in testspeech");
    // quizTimer();

    micButton.click();
    document.addEventListener(eventCount, () => {
        console.log(eventCount, " :: text : ", transcribed_text)

        var transcribed_text_tmp = transcribed_text.replace(/[.,]/g, '');
        var speechResult = (transcribed_text_tmp.toLowerCase()).trim();
        // console.log(speechResult, translate.wordInstruction  )
        console.log(speechResult)

        if (speechResult == "instruction" || speechResult == "instructions") {

            console.log("speech result: " + speechResult);
            resultPara.textContent = translate.speech_recieved + speechResult + '.';

            var testhead2 = document.getElementById("instructions").textContent;

            synthesize(testhead2);

            audio.onended = function (event) {
                isPlaying = false;
                speakerButton.classList.remove('speakering');
                // startTimer();
                readIntro();
            }

        }
        else if (speechResult == "exam rules") {

            console.log("speech result: " + speechResult);
            resultPara.textContent = translate.speech_recieved + speechResult + '.';

            var testhead2 = document.getElementById("pattern").textContent;

            synthesize(testhead2);

            audio.onended = function (event) {
                isPlaying = false;
                speakerButton.classList.remove('speakering');
                // startTimer();
                readIntro();
            }

        }

        else if (speechResult.includes("start exam")) {

            // readTimerQuestion();
            readQuestion(1);

        }
        else {

            console.log("speech result: " + speechResult);
            // resultPara.textContent = translate.speech_recieved + speechResult + '. No such operation.';
            resultPara.textContent = translate.speech_recieved + speechResult;

            var testhead = translate.sorryMsg;

            synthesize(testhead);
            audio.onended = function (event) {
                isPlaying = false;
                speakerButton.classList.remove('speakering');
                instructORstart();

            }
        }
    });
}


var resultPara1 = document.querySelector('.spresult1');

var minutes = document.getElementById("timer").innerHTML;
var intNumber = parseInt(minutes);
var time = 60000 * intNumber;

//timer and exam

function quizTimer() {

    var expDate = new Date().getTime();
    var countDownDate = expDate + time;
    var x = setInterval(function () {
        // console.log("timer called")
        var now = new Date().getTime();
        var distance = countDownDate - now;
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        document.getElementById("timer").innerHTML = minutes + " minutes " + seconds + " seconds ";
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("oqsubmit").click();
        }
    }, 1000);
}

function readTimerQuestion() {
    var speakTimer = translate.time + minutes;
    var speakMarks = translate.totalMarks + document.getElementById("total_marks").innerHTML;

    var merge = speakTimer + speakMarks;
    synthesize(merge);
    console.log(merge);

    audio.onended = function (event) {
        isPlaying = false;
        speakerButton.classList.remove('speakering');
        // startTimer();
        readIntro();
    }
}

function readQuestion(qNo) {
    var ques_element = document.getElementById("ques" + qNo);
    var qtype = document.getElementById("typeId" + qNo).value;

    //scroll to question
    var ques = ques_element.textContent
    ques_element.scrollIntoView();

    localStorage['resume'] = qNo;
    console.log("Current question : ", localStorage['resume']);

    console.log("Type of Question : ", qtype);
    var addSynth = "";
    if (qtype.trim() == "descriptive") {
        console.log("inside desc");
        addSynth = translate.sayHelp;
        // if (document.getElementById("answer" + qNo).value != "")
        //     addSynth += translate.sayReadAns;
    } else if (qtype.trim() == "none") {
        console.log("inside none");
        addSynth = translate.sayNext + translate.sayRepeatQuestion;
    } else if (qtype.trim() == "objective") {
        console.log("inside objecive");
        addSynth = ", say option number to select the option or say repeat to repeat question";
    } else {
        addSynth = translate.sorryMsg;
    }

    // for last question
    if (count == qNo) {
        addSynth = translate.finalQuestion + translate.sayPreviousQuestion + translate.saySubmit + translate.sorryMsg;
    }

    console.log("CHECK : ", ques + addSynth);
    synthesize(ques + addSynth);

    audio.onended = function (event) {
        isPlaying = false;
        speakerButton.classList.remove('speakering');
        navigateExam(qNo, 0);
    }
}


var resultPara2 = document.querySelector('.spresult2');
var navQus = 0;
// core engine 
function navigateExam(qNo, navQus) {

    console.log("speechQuestion :: qNo : " + qNo);
    micButton.style.backgroundColor = "yellow"
    max_seconds = 2

    micButton.click();
    document.addEventListener(eventCount, () => {

        var transcribed_text_tmp = transcribed_text.replace(/[.,]/g, '');
        var speechResult = (transcribed_text_tmp.toLowerCase()).trim();

        var q_type = document.getElementById("typeId" + qNo).value;

        console.log(eventCount, " :: speech text : ", speechResult)
        console.log("Q no :", qNo, " Q Type :", q_type);


        if (speechResult == "repeat") {
            console.log("inside repeat question");
            reapeatQuestion(qNo);
        }
        else if (speechResult.includes("option") && q_type == "objective") {
            console.log("inside objective option");
            writeObjectiveAnswer(qNo, speechResult);

        }
        else if (speechResult.includes("right") || speechResult.includes("write") && q_type == "descriptive") {
            console.log("Inside descriptive write answer!");
            writeAnswer(qNo);
        }
        else if (speechResult == "exam status") {
            console.log("inside exam status");
            examStatus(qNo);
        }
        else if (speechResult.includes("status") && q_type == "descriptive") {
            console.log("Inside answer status!");
            updateQuestionStatus(qNo, speechResult);
        }
        else if (speechResult.includes("next question") || speechResult.includes("next")) {
            console.log("inside next question");
            moveNextQuestion(qNo);
        }
        else if (speechResult.includes("previous question")) {
            console.log("inside previous question");
            movePreviousQuestion(qNo);
        }
        else if (speechResult.includes("question")) {
            console.log("inside move using question no");
            navigateQuestion(qNo, speechResult);
        }
        else if (speechResult.includes("read answer")) {
            console.log("inside read answer");
            readAnswer(qNo);
        }
        else if (speechResult == "instruction" || speechResult == "instructions") {
            console.log("inside instuctions")
            readInstructions(qNo);
        }
        else if (speechResult == "time remaining") {
            console.log("inside time");
            readTimeRemaining(qNo);

        }
        else if (speechResult == "help") {
            console.log("inside help");
            helpSection(qNo);
        }
        else if (speechResult.includes("delete") && q_type == "descriptive") {
            console.log("Inside delete!");
            deleteAnswer(qNo, speechResult);
        }
        else if (speechResult == "submit" || speechResult == "सब्मिट") {
            console.log("inside submit")
            submitExam(qNo);
        }
        else {
            console.log("inside fallbacked..");
            fallbackCase(qNo);
        }
    });


    function moveNextQuestion(qNo) {
        qNo++;
        readQuestion(qNo);
    }

    function movePreviousQuestion(qNo) {
        qNo--;
        readQuestion(qNo);
    }

    function reapeatQuestion(qNo) {
        readQuestion(qNo);
    }

    function readTimeRemaining(qNo) {
        var time_remaining = document.getElementById("timer").innerHTML;

        synthesize(time_remaining);
        audio.onended = function (event) {
            isPlaying = false;
            speakerButton.classList.remove('speakering');
            readQuestion(qNo);
        }
    }


    function writeObjectiveAnswer(qNo, speechResult) {
        optionKeyword_optionNo = speechResult.split(" ");
        var optionNo = optionKeyword_optionNo[1].replace(/[.,]/g, '').toLowerCase().trim();

        if (optionNo == 1 || optionNo == "one")
            saveAnswer(qNo, 0, speechResult);
        else if (optionNo == 2 || optionNo == "tow" || optionNo == "to")
            saveAnswer(qNo, 1, speechResult);
        else if (optionNo == 3 || optionNo == "three")
            saveAnswer(qNo, 2, speechResult);
        else if (optionNo == 4 || optionNo == "four" || optionNo == "for")
            saveAnswer(qNo, 3, speechResult);
        else if (optionNo == 5 || optionNo == "five")
            saveAnswer(qNo, 4, speechResult);
        else {
            synthesize("no such option");
            audio.onended = function (event) {
                isPlaying = false;
                speakerButton.classList.remove('speakering');
                readQuestion(qNo);
            }
        }
    }

    function writeAnswer(qNo) {
        synthesize(translate.writeYourAns);
        audio.onended = function (event) {
            isPlaying = false;
            speakerButton.classList.remove('speakering');
            micButton.style.backgroundColor = "green"
            max_seconds = 5
            // call language specific asr
            micButtonClicked(langcode)
            document.addEventListener(eventCount, () => {

                console.log("ID: ", "answer" + qNo);
                document.getElementById("answer" + qNo).value +=  transcribed_text + "\n" ;

                saveAnswer(qNo, -1, transcribed_text);
            });
        }
    }

    function updateQuestionStatus(qNo, speechResult) {
        statusKeyword_status = speechResult.split(" ");

        answer_status = statusKeyword_status[1].replace(/[.,]/g, '').toLowerCase().trim();
        if (answer_status == "completed") {
            updateAnswerStatus(qNo, answer_status)
        } else if (answer_status == "review") {
            updateAnswerStatus(qNo, answer_status)
        } else if (answer_status == "skip") {
            updateAnswerStatus(qNo, answer_status)
        } else {
            synthesize("no such operation");
            audio.onended = function (event) {
                isPlaying = false;
                speakerButton.classList.remove('speakering');
                readQuestion(qNo);
            }
        }
    }

    function navigateQuestion(qNo, speechResult) {
        //TODO : check for exception in split 
        questionKeyword_questionId = speechResult.split(" ");

        var questionId = questionKeyword_questionId[1].replace(/[.,]/g, '').toLowerCase().trim();

        question_no = Object.values(question_ids_nos);
        // console.log(question_no.length, question_no)
        for (index = 0; index < question_no.length; index++) {
            var question_id_trimmed = question_no[index].replace(/[.,]/g, '').toLowerCase().trim();
            if (question_id_trimmed == questionId) {
                console.log("move to :: index, value :", index, question_id_trimmed);
                readQuestion(index + 1);
                break;
            }
            else if (index + 1 == question_no.length) {
                synthesize("no such question");
                audio.onended = function (event) {
                    isPlaying = false;
                    speakerButton.classList.remove('speakering');
                    navigateExam(qNo);
                }
            }
        }
    }

    function readAnswer(qNo) {
        var answer = document.getElementById("answer" + qNo).value + translate.sayHelp;
        console.log("answer", answer)
        synthesize(answer);
        audio.onended = function (event) {
            isPlaying = false;
            speakerButton.classList.remove('speakering');
            navigateExam(qNo);
        }
    }

    function readInstructions(qNo) {
        var instructions = document.getElementById("instructions").textContent;

        synthesize(instructions);
        audio.onended = function (event) {
            isPlaying = false;
            speakerButton.classList.remove('speakering');
            readQuestion(qNo);
        }
    }

    function helpSection(qNo) {
        synthesize(translate.sayInstructions);
        audio.onended = function (event) {
            isPlaying = false;
            speakerButton.classList.remove('speakering');

            micButton.click();
            document.addEventListener(eventCount, () => {

                var transcribed_text_tmp = transcribed_text.replace(/[.,]/g, '');
                var speechResult = (transcribed_text_tmp.toLowerCase()).trim();
                console.log(speechResult)

                if (speechResult == "instruction" || speechResult == "instructions") {
                    console.log("inside Instructions")
                    var testhead1 = translate.instructions;

                    synthesize(testhead1);
                    audio.onended = function (event) {
                        isPlaying = false;
                        speakerButton.classList.remove('speakering');
                        readQuestion(qNo)
                    }
                }
                else if (speechResult == "exam rules") {
                    console.log("inside exam rules")
                    var testhead1 = document.getElementById("pattern").textContent;

                    synthesize(testhead1);
                    audio.onended = function (event) {
                        isPlaying = false;
                        speakerButton.classList.remove('speakering');
                        readQuestion(qNo)
                    }
                }
                else {
                    synthesize(translate.sorryMsg);
                    return;
                }

            });
        }
    }


    function examStatus(qNo) {
        function findMatchingPairs(arr1, arr2) {
            if (arr2 === null || arr2 === undefined) {
                return null;
            }
            const allKeysPresent = arr2.every(key => arr1.hasOwnProperty(key));

            if (allKeysPresent) {
                const matchingPairs = {};
                arr2.forEach(key => {
                    matchingPairs[key] = arr1[key];
                });
                return matchingPairs;
            } else {
                return null;
            }
        }

        var postData = {
            "count": count,
        }

        $.ajax({
            url: "examStatus.php",
            type: 'POST',
            dataType: "json",
            // contentType: 'application/json',
            data: {
                myData: postData
            },
            beforeSend: function () {

            },
            success: function (data, textStatus) {
                var review = data.review;
                const reviewQue = findMatchingPairs(question_ids_nos, review);
                var review_questions = " ";

                for (var key in reviewQue) {
                    review_questions += "question " + reviewQue[key] + ",";
                }

                var skip = data.skip;
                const skipQue = findMatchingPairs(question_ids_nos, skip);
                var skip_questions = " ";

                for (var key in skipQue) {
                    skip_questions += "question " + skipQue[key] + ",";
                }

                var attempted = data.attempted;
                const attemptedQue = findMatchingPairs(question_ids_nos, attempted);
                var attempted_questions = " ";

                for (var key in attemptedQue) {
                    attempted_questions += "question " + attemptedQue[key] + ",";
                }

                var completed = data.completed;
                const completedQue = findMatchingPairs(question_ids_nos, completed);
                var completed_questions = " ";

                for (var key in completedQue) {
                    completed_questions += "question " + completedQue[key] + ",";
                }

                var examStatus = "Attempted questions are " + attempted_questions + ". Completed questions are " + completed_questions + ". Question for review are " + review_questions + ". Skipped questions are " + skip_questions;
                console.log("examStatus: ", examStatus);
                synthesize(examStatus);
                audio.onended = function (event) {
                    isPlaying = false;
                    speakerButton.classList.remove('speakering');
                    navigateExam(qNo, 0);
                }
            },
            error: function (errorMessage) {
                console.log('Error ' + errorMessage);
            }
        });
    }

    function submitExam(qNo) {
        var testhead = translate.confirmSubmit;
        synthesize(testhead);

        audio.onended = function (event) {
            isPlaying = false;
            speakerButton.classList.remove('speakering');

            micButton.click();
            document.addEventListener(eventCount, () => {

                var transcribed_text_tmp = transcribed_text.replace(/[.,]/g, '');
                var speechResult = (transcribed_text_tmp.toLowerCase()).trim();
                console.log(speechResult)

                if (speechResult.includes("yes")) {

                    console.log("Inside yes")
                    localStorage.clear();
                    document.getElementById("oqsubmit").click();
                }
                else if (speechResult.includes("no")) {
                    readQuestion(qNo);
                }
                else {
                    synthesize(translate.sorryMsg);
                    return;
                }
            });
        }
    }

    function fallbackCase() {
        synthesize(translate.sorryMsg);
        audio.onended = function (event) {
            isPlaying = false;
            speakerButton.classList.remove('speakering');
            navigateExam(qNo, 0);
        }
    }
}


// var a = ['', 'one ', 'two ', 'three ', 'four ', 'five ', 'six ', 'seven ', 'eight ', 'nine ', 'ten ', 'eleven ', 'twelve ', 'thirteen ', 'fourteen ', 'fifteen ', 'sixteen ', 'seventeen ', 'eighteen ', 'nineteen '];
// var b = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

// function inWords(num) {
//     if ((num = num.toString()).length > 9) return 'overflow';
//     n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
//     if (!n) return; var str = '';
//     str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
//     str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
//     str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
//     str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
//     str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) : '';
//     return str;
// }


// function speakNav(navQustions) {
//     console.log("navQustion:" + navQustions);
//     syn.speak(navQustions);
//     navQustions.onend = function (event) {
//         console.log("question no: " + qNo);
//         navigateExam(qNo, navQus);
//     }
// }

function updateAnswerStatus(qNo, status) {

    question_id = document.getElementById("quesId" + qNo).value;

    var data = {
        question_id: question_id,
        status: status,
    };

    console.log("result save: ", data);

    $.post("save_response.php", data);

    synthesize("saved");
    audio.onended = function (event) {
        isPlaying = false;
        speakerButton.classList.remove('speakering');
        readQuestion(qNo + 1, 0);
    }
}

function deleteAnswer(qNo, speechResult) {
    delete_string = speechResult.split(" ");

    deleteKeyword = delete_string[1].replace(/[.,]/g, '').toLowerCase().trim();
    if (deleteKeyword == "answer") {
        document.getElementById("answer" + qNo).value = " ";
        saveAnswer(qNo, -2, "your answer is deleted")
    } else if (deleteKeyword == "point") {
        textarea = document.getElementById("answer" + qNo).value;
        var lines = textarea.split('\n');
        lines.pop();
        lines.pop();
        var newText = lines.join('\n') + "\n";
        document.getElementById("answer" + qNo).value = newText;
        saveAnswer(qNo, -3, newText)
    } else {
        synthesize("no such operation");
        audio.onended = function (event) {
            isPlaying = false;
            speakerButton.classList.remove('speakering');
            navigateExam(qNo, 0);
        }
    }


}


function saveAnswer(qNo, qType, speechResult) {
    var navQ;
    console.log("question no " + qNo + " qestion type " + qType);

    save_question = document.getElementById("ques" + qNo).textContent;
    // if qType = -1 the question is descriptive otherwise objective
    if (qType == -1) {
        // for descriptive
        save_answer = document.getElementById("answer" + qNo).value;
        save_marks = null;
    }
    else if (qType == -2) {
        // for deletion
        save_answer = document.getElementById("answer" + qNo).value;
        save_marks = null;
    }
    else if (qType == -3) {
        // for deletion
        save_answer = speechResult;
        save_marks = null;
    }
    else {
        //for objective
        save_answer = document.getElementById("oq-options" + qNo + "" + (qType + 1)).innerHTML;
        save_marks = document.getElementById("marksId" + qNo).value;
    }

    question_id = document.getElementById("quesId" + qNo).value;
    // save the result
    var data = {
        question_id: question_id,
        question: save_question,
        option: save_answer,
        obj_marks: save_marks,
        filename: audio_filename
    };

    console.log("result save: ", data);

    $.post("save_response.php", data);

    // resultPara2.textContent = 'Speech received: ' + speechResult + '.';
    var your_answer = ""
    if (qType == -1) {
        your_answer = translate.your_answer + speechResult;
        console.log("here ", your_answer)
    } else if (qType == -2) {
        your_answer = "your answer is deleted";
    } else if (qType == -3) {
        your_answer = "your last point from answer is deleted";
    } else {
        // for objective
        your_answer = "you selected option " + (qType + 1) + document.getElementById("oq-options" + qNo + "" + (qType + 1)).innerHTML;
    }
    synthesize(your_answer);
    audio.onended = function (event) {
        isPlaying = false;
        speakerButton.classList.remove('speakering');

        if (qType >= 0) {
            //for objective
            radios = document.getElementsByName('qus' + qNo);
            radios[qType].checked = "true";
            console.log("oq-options" + qNo + "" + (qType + 1));
            ans = document.getElementById("oq-options" + qNo + "" + (qType + 1)).innerHTML;
        }

        var show = translate.sayHelp;
        synthesize(show);
        audio.onended = function (event) {
            isPlaying = false;
            speakerButton.classList.remove('speakering');
            console.log("question no. ", qNo)
            navigateExam(qNo, 0);
        }
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
