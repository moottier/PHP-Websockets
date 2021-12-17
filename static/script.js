var socket;

window.onload = init;

function init() {
	//var ip = "<?php echo $_SERVER['SERVER_ADDR']; ?>"
    var ip = "167.114.169.184"
	var port = "9002"
	//var dir = "/wp-content/themes/JointsWP-CSS/template-parts/widget/chat/chatserver.php"
	var dir = "/wss/"
	
	var host = "wss://dev.andremottier.com/wss/"; 

	try {
		socket = new WebSocket(host);
		log('WebSocket - status '+socket.readyState);
		socket.onopen    = function(msg) { 
							   log("Welcome - status "+this.readyState); 
						   };
		socket.onmessage = function(msg) { 
							   log("Received: "+msg.data); 
						   };
		socket.onclose   = function(msg) { 
							   log("Disconnected - status "+this.readyState); 
                               console.log("Disconnected - status ");
                               console.log(event); 
						   };
        socket.onerror   = function(msg) {
                                log("Socket error - "+event);
                                console.log(event);
                                console.log(event.code);
                           };
	}
	catch(ex){ 
		log(ex); 
	}
	$("msg").focus();
}

function send(){
	var txt,msg;
	txt = $("msg");
	msg = txt.value;
	if(!msg) { 
		alert("Message can not be empty"); 
		return; 
	}
	txt.value="";
	txt.focus();
	try { 
		socket.send(msg); 
		log('Sent: '+msg); 
	} catch(ex) { 
		log(ex); 
	}
}
function quit(){
	if (socket != null) {
		log("Goodbye!");
		socket.close();
		socket=null;
	}
}

function reconnect() {
	quit();
	init();
}

// Utilities
function $(id){ return document.getElementById(id); }
function log(msg){ $("log").innerHTML+="<br>"+msg; }
function onkey(event){ if(event.keyCode==13){ send(); } }