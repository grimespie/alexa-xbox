<?php

class AlexaSession {
    
    private $session;
    
    public function __construct($session) {
        $this->setSession($session);
    }
    
    private function setSession($session) {
        $this->session = $session;
    }
    
    public function isNewSession() {
        return($this->session->new);
    }
    
    public function getSessionID() {
        return($this->session->sessionId);
    }
    
    public function getApplicationID() {
        return($this->session->application->applicationId);
    }
    
    public function getUserID() {
        return($this->session->user->userId);
    }
    
    public function getUserAccessToken() {
        return($this->session->user->accessToken);
    }
    
}

class AlexaRequest {
    
    private $request;
    
    public function __construct($request) {
        $this->setRequest($request);
    }
    
    private function setRequest($request) {
        $this->request = $request;
    }
    
    public function getType() {
        return($this->request->type);
    }
    
    public function getRequestID() {
        return($this->request->requestId);
    }
    
    public function getTimestamp() {
        return($this->request->timestamp);
    }
    
    public function getLocale() {
        return($this->request->locale);
    }
    
    public function getIntent() {
        return($this->request->intent->name);
    }
    
}

class Alexa {
    
    private $input;
    private $session;
    private $request;
    
    private $application_id = "";
    private $card           = "";
    private $reprompt       = "";
    private $outputspeech   = "";
    
    public function __construct() {
        $this->getInput();
    }
    
    private function getInput() {
        $this->input = json_decode(file_get_contents("php://input"));
        
        if(isset($this->input->session)) {
            $this->session = new AlexaSession($this->input->session);
        }
        
        if(isset($this->input->request)) {
            $this->request = new AlexaRequest($this->input->request);
        }
    }
    
    public function setApplicationID($application_id) {
        $this->application_id = $application_id;
    }
    
    public function auth() {
        if($this->input != "") {
            if($this->application_id == $this->getSession()->getApplicationID()) {
                return(true);
            }
        }
        
        return(false);
    }
    
    public function getVersion() {
        return($this->input->version);
    }
    
    public function getSession() {
        return($this->session);
    }
    
    public function getRequest() {
        return($this->request);
    }
    
    public function setApplicationName($app_name) {
        $this->app_name = $app_name;
    }
    
    public function setCard($card) {
        $this->card = $card;
    }
    
    public function setReprompt($reprompt) {
        $this->reprompt = $reprompt;
    }
    
    public function setOutputSpeech($outputspeech) {
        $this->outputspeech = $outputspeech;
    }
    
    public function displayOutput() {
    ?>
    
        {
            "version": "1.0",
            "response": {
                "outputSpeech": {
                    "type": "PlainText",
                    "text": "<?php print($this->outputspeech); ?>"
                },
                "card": {
                    "type": "Simple",
                    "title": "<?php print($this->app_name); ?>",
                    "content": "<?php print($this->card); ?>"
                },
                "reprompt": {
                    "outputSpeech": {
                        "type": "PlainText",
                        "text": "<?php print($this->reprompt); ?>"
                    }
                },
                "shouldEndSession": true
            }
        }
    
    <?php
    }
    
}

?>