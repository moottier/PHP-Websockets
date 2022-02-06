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
        echo "Processing!";
        if ($user->alias == "Unknown")
        {
            echo "Set alias to " . $message . "\n"; 
            $user->alias = $message;
        }
        else
        {
            echo "Disperse message from " . $user->alias . " :: " . $user->id . " !\n";
            foreach ($this->users as $connectedUser){
                echo "Send to " . $connectedUser->alias . " :: " . $connectedUser->id . " ?\n";
                if ($connectedUser != $user)
                {
                    echo "New message!\n";
                    $messageObj = new $this->messageClass($message, $user, $connectedUser);
                    $this->send($messageObj);
                }
        
            }
        }

    }


    protected function connected ($user) {
        // AMM 2021-11-06 DEBUG
        $this->stdout("User connected. Messaging: " . $user->id . '\r\n');
        $message = new $this->messageClass("Hello!\r\n", $this->serverUser(), $user);
        $this->send($message);
    }

    protected function closed ($user) {
    // Do nothing: This is where cleanup would go, in case the user had any sort of
    // open files or other objects associated with them.  This runs after the socket 
    // has been closed, so there is no need to clean up the socket itself here.
    }
}

//echo phpversion();
$echo = new broadcastServer("127.0.0.1", "9002", 1048576);

try {
  $echo->run();
}
catch (Exception $e) {
  $echo->stdout("ERR: " . $e->getMessage());
}