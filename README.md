# Speech Enabled Examination System

This examination system that uses ASR and TTS technologies.  The user interacts in speech form, system takes commands and answers through mic, and converts the audio in text form using ASR. For reading text or questions TTS is used. The primary objective is to remove the dependency on scribes. The system is equally useful for students having any physical or motor disability in hand.

## Features:
* Intractive 
* Supports objective and subjective type questions
* Multilingual
* Auto silence detection
* Auto save
* Resume option
* Admin login to set and evaluate test


## Configuration : 

* PHP 7.4.33 is used
* create database in mysql and use these credentails in *config.php* to connect the db
* Bhasnini API are used for TTS and ASR
follow [this](https://dibd-bhashini.gitbook.io/bhashini-apis) link to create API Key.
* Apache web server on Ubantu is used

You can find the demo [here](https://speechindia.in/quizforblind/) 


Modifications is done on [quizforblind](https://github.com/cycosad/quizforblind)