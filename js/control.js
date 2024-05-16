var response_wait_audio = new Audio();
response_wait_audio.src = "audio/wait_msg.mp3"
response_wait_audio.loop=true;

function StopResume() {
    // console.log("isRecording", isRecording, "isPlaying", isPlaying);
    audio_wait.pause();

    if (isPlaying == false && isRecording == false) {
        return;
    }

    state = document.getElementById("stop_resume").innerHTML
    if (state == "Pause") {

        console.log("Paused");
        if (isPlaying == true) {
            audio.pause()
            speakerButton.classList.remove('speakering');
        } if (isRecording == true) {
            pauseResumeRecording()
        }
        document.getElementById("stop_resume").innerHTML = "Resume"
        // synthesize("we are waiting");
        synthesize(translate.waitingMsg);

    } if (state == "Resume") {
        console.log("Resumed");

        if (isPlaying == true) {
            audio.play()
            speakerButton.classList.add('speakering');
        } if (isRecording == true) {
            pauseResumeRecording()
        }

        document.getElementById("stop_resume").innerHTML = "Pause"
    }
}


function interrupt(){
    console.log("interrupted")
    if (isPlaying == true ) {
        audio.pause();
        isPlaying == false
        speakerButton.classList.remove('speakering');
    }
    if(isRecording == true){
        recorder.mic.stop();
        window.stop();
        isRecording = false;
        micButton.classList.remove('recording');

    }
    synthesize("speak the command" , "en");
    state = document.getElementById("stop_resume").innerHTML
            
    if (state == "Resume"){
        audio_wait.onended = function (event) {
            isPlaying = false;
            speakerButton.classList.remove('speakering');
            console.log("question no. reasume", resume_question)
            StopResume()
            if(resume_question != -1)
                navigateExam(resume_question, 0);
            else
                navigateExam(1, 0);
        }

    }else{
        audio.onended = function (event) {
            isPlaying = false;
            speakerButton.classList.remove('speakering');
            console.log("question no. reasume", resume_question)
    
            if(resume_question != -1)
                navigateExam(resume_question, 0);
            else
                navigateExam(1, 0);
        }
    }
}


function play_wait_msg(){
    console.log("play wait message!")
    response_wait_audio.src = "audio/wait_msg.mp3"
    response_wait_audio.load();
    response_wait_audio.play();
}