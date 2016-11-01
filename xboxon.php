<?php

class XboxOn {
    
    private $pingval    = "dd00000a000000000000000400000002";
    private $port       = 5050;
    private $ipaddress  = "";
    private $xboxliveid = "";
    private $retries    = 3;
    
    public function __construct() {
        
    }
    
    public function setPort($port) {
        $this->port = $port;
    }
    
    public function setIPAddress($ipaddress) {
        $this->ipaddress = $ipaddress;
    }
    
    public function setXboxLiveID($xboxliveid) {
        $this->xboxliveid = $xboxliveid;
    }
    
    public function setRetryCount($retries) {
        $this->retries = $retries;
    }
    
    private function getSocket() {
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        
        socket_set_block($socket);
        socket_connect($socket, $this->ipaddress, $this->port);
        
        return($socket);
    }
    
    private function hex2str($hex) {
        $str = '';
        
        for($i=0; $i<strlen($hex); $i+=2) {
            $str .= chr(hexdec(substr($hex,$i,2)));
        }
        
        return($str);
    }
    
    private function on() {
        $socket = $this->getSocket();
        
        $data   = "\x00" . chr(strlen($this->xboxliveid)) . $this->xboxliveid . "\x00";
        $header = "\xdd\x02\x00" . chr(strlen($data)) . "\x00\x00";
        
        $status = socket_send($socket, $header . $data, strlen($header . $data), MSG_EOR);

        sleep(1);
        
        if($status) {
            return(true);
        }
        
        return(false);
    }
    
    public function switchOn() {
        for($i=0; $i<$this->retries; $i++) {
            if($this->on()) {
                if($this->ping()) {
                    return(true);
                }
            }
        }
        
        sleep(1);
        
        // One last check!
        if($this->ping()) {
            return(true);
        }
        
        return(false);
    }
    
    public function ping() {
        $socket = $this->getSocket();
        
        $data = $this->hex2str($this->pingval);
        
        socket_write($socket, $data, strlen($data));
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 1, "usec" => 0));
        
        $buffer = "";
        
        $bytes = socket_recv($socket, $buffer, 2048, MSG_WAITALL);
        
        if(($bytes) && ($buffer != null)) {
            return(true);
        }
        
        return(false);
    }
}

?>