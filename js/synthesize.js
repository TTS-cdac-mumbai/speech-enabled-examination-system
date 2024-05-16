const SPEECH_INDIA_TTS = "https://speechindia.in/tts4/";
const TTS_BHASHINI_API = "https://dhruva-api.bhashini.gov.in/services/inference/pipeline"
const TTS_API_BHASHINI_PROXY = "../../../asr/synthesis_bhasini";

const TTS_BHASHINI_API_SERVICE_ID_HI = "ai4bharat/indic-tts-coqui-indo_aryan-gpu--t4"
const TTS_BHASHINI_API_SERVICE_ID_EN = "ai4bharat/indic-tts-coqui-misc-gpu--t4"
const TTS_BHASHINI_API_SERVICE_ID_MR = "ai4bharat/indic-tts-coqui-indo_aryan-gpu--t4"
const TTS_BHASHINI_API_SERVICE_ID_IITM = "Bhashini/IITM/TTS"

var audio = new Audio();
var audio_wait = new Audio();

var isPlaying = false;

var speakerButton = document.getElementById("speakerButton");


// const synthesisFinishEvent = new Event("synthesisFinish")
const BhashiniTTS = true

function synthesize(text, lang = langcode) {

    if (BhashiniTTS) {

        var service_id = TTS_BHASHINI_API_SERVICE_ID_IITM;
        // if(lang == "hi")
        //     var service_id = TTS_BHASHINI_API_SERVICE_ID_HI;
    
        // else if(lang == "mr")
        //     var service_id = TTS_BHASHINI_API_SERVICE_ID_MR;
        // else
        
        //     var service_id = TTS_BHASHINI_API_SERVICE_ID_EN;
    
        synthesisBhashiniAudioProxy(text, service_id, lang);

    } else {
        synthesizeSpeechIndia(text);
    }
}

function synthesisBhashiniAudioProxy(text, serviceId, lang) {

    text_filename = userID.trim() + "_tts_" + getDateTime() + ".txt"

    const TTSFormData = new FormData();
    TTSFormData.append("language", lang);
    TTSFormData.append("text", text);
    TTSFormData.append("service_id", serviceId);
    TTSFormData.append("file_name", text_filename);
    // TTSFormData.append("user_id", userID);
    
 
    var oReq = new XMLHttpRequest();
    oReq.open("POST", TTS_API_BHASHINI_PROXY, true);
    oReq.onload = function (oEvent) {
        if (oReq.status == 200) {
            // console.log("TTS Response: ", oReq.response);
            tts_response = oReq.response;
            const obj = JSON.parse(tts_response);

            audio_base_64 = obj.audio
            state = document.getElementById("stop_resume").innerHTML
            
            if (state == "Resume") {
                audio_wait.src = "data:audio/wav;base64," + audio_base_64;
                audio_wait.load();
                audio_wait.play();
            }else{       
                audio.src = "data:audio/wav;base64," + audio_base_64;
                audio.load();
                audio.play();
                isPlaying = true;
                speakerButton.classList.add("speakering");
            }
        }
        if (oReq.status === 500) {
            transcribed_text = "not able to synthesis"
    
        }
        response_wait_audio.pause();
        JsLoadingOverlay.hide();
    };
    console.log("Sending text... ");
    JsLoadingOverlay.show({
        'overlayBackgroundColor': '#ffffff',
        'spinnerIcon': 'ball-beat'
    });
    response_wait_audio.load();
    response_wait_audio.play();
    
    oReq.send(TTSFormData);
}

function synthesisBhashiniText(text, serviceId, lang) {

    var postData = {
        "pipelineTasks": [
            {
                "taskType": "tts",
                "config": {
                    "language": {
                        "sourceLanguage": lang
                    },
                    "serviceId": serviceId,
                    // "modelId":"6576a17e00d64169e2f8f43d",
                    "gender": "female"
                }
            }
        ],
        "inputData": {
            "input": [
                {
                    "source": text
                }
            ],
            "audio": [
                {
                    "audioContent": null
                }
            ]
        }
    }

    console.log("Sending audio request ...")

    var xhr = new XMLHttpRequest();
    // var SPEECH_INDIA_TTS = TTS_BHASHINI_API;
    xhr.open("POST", TTS_BHASHINI_API, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader("Authorization", "YOUR_KEY");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log("pause wait message!")
            var json = JSON.parse(xhr.responseText);
            // console.log("ASR Response: ",oReq.response);
            audio_base_64 = json["pipelineResponse"][0]["audio"][0]["audioContent"]

            state = document.getElementById("stop_resume").innerHTML
            
            if (state == "Resume") {
                audio_wait.src = "data:audio/wav;base64," + audio_base_64;
                audio_wait.load();
                audio_wait.play();
            }else{       
                audio.src = "data:audio/wav;base64," + audio_base_64;
                audio.load();
                audio.play();
                isPlaying = true;
                speakerButton.classList.add("speakering");
            }

        }
        if (xhr.status === 500) {
            console.log("no response from tts")
        }
        response_wait_audio.pause();
        JsLoadingOverlay.hide();
    };
    var data = JSON.stringify(postData);
    // console.log(data)
    JsLoadingOverlay.show({
        'overlayBackgroundColor': '#ffffff',
        'spinnerIcon': 'ball-beat'
    });
    response_wait_audio.load();
    response_wait_audio.play();
    
    xhr.send(data);
}

// for our TTS
function synthesizeSpeechIndia(text) {

    const count = Date.now();
    const lang = "hindi"
    const speed = "1"
    //console.log("Inside sythesis.js : ", text)
    //var selText = document.getElementById("ip").value;

    var params = "Languages=" + lang + "&ex=execute&op=" + text + "&count=" + count + "&speed=" + speed;

    http = new XMLHttpRequest();
    http.open("POST", SPEECH_INDIA_TTS + "synthesis.php", true);

    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // http.setRequestHeader("Access-Control-Allow-Origin", "*");
    // http.setRequestHeader("Content-length", params.length);
    // http.setRequestHeader("Connection", "close");


    http.onreadystatechange = function () {
        if (http.readyState == 4) {
            var resTxt = http.responseText;
            var startTxt = resTxt.indexOf("temp/");
            var endTxt = resTxt.indexOf(".mp3", startTxt);
            var spCode = resTxt.substring(startTxt, endTxt);
            //for testing
            // audio.src = "https://10.210.5.4/tts4/wav_output/fest_out1682397404624.mp3";
            // for synthesis
            audio.src = SPEECH_INDIA_TTS + "wav_output/fest_out" + count + ".mp3";
            audio.load();
            audio.play();
            isPlaying = true;


            // document.dispatchEvent(synthesisFinishEvent);
        }
    }
    http.send(params);

}
