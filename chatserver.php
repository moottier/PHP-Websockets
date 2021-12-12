#!/usr/bin/env php
<?php

require_once('./websockets.php');

class broadcastServer extends WebSocketServer {

    function __construct($addr, $port, $bufferLength) {
        parent::__construct($addr, $port, $bufferLength);
        $this->userClass = 'MyUser';
    }

    //protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.

    protected function process ($user, $message) {
        foreach ($this->users as $user){
            $this->send($user,$message);
        }
    }


    protected function connected ($user) {
        // AMM 2021-11-06 DEBUG
        $this->stdout("Connected: " . $user->id);
        // AMM 2021-11-06 DEBUG
        $this->stdout("Messaging: " . $user->id);
        $message = "Hello!\r\n";
        $this->send($user,$message);
    }

    protected function closed ($user) {
    // Do nothing: This is where cleanup would go, in case the user had any sort of
    // open files or other objects associated with them.  This runs after the socket 
    // has been closed, so there is no need to clean up the socket itself here.
    }
}

//echo phpversion();
$ip = $_SERVER['SERVER_ADDR'];
print_r("IP: $ip");
$echo = new broadcastServer("127.0.0.1", "9002", 1048576);

try {
  $echo->run();
}
catch (Exception $e) {
  $echo->stdout("ERR: " . $e->getMessage());
}