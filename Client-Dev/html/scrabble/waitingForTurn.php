<?php
//Wanted to note that SQL is called locally since this is basically used as a cache for only matchmaking. 
//If we continuously call rabbitmq in a while loop to send and receive message, we end up "clogging" the queue causing it to freeze
//Thus we call SQL locally by design choice, not because we did not know how to code this step

session_start();

include("../account.php");
include("matchmaking/Function.php");


error_reporting(E_ALL);
ini_set('display_errors',on);




//testing
//$_SESSION["login"] = True;
//$_SESSION["user"]= "Bill";


#################################Initiates Connection to SQL SERVER################################
$db = mysqli_connect($servername, $username, $password , $project);
if (mysqli_connect_errno())
  {
	  $message = "Failed to connect to MySQL: " . mysqli_connect_error();
	  echo $message;
	  error_log($message);
	  exit();
  }

mysqli_select_db( $db, $project );
###################################################################################################




if((!isset($_SESSION["login"])) or (!$_SESSION["login"])){
	redirect("", "index.php", 0);
	exit();
}
else{
	$user = $_SESSION["user"];
	$gameID = findInfo($user, "Matchid");
	while($gameID == null){
		$gameID = findInfo($user, "Matchid");
	}
	$_SESSION["gameID"] = $gameID;
	//print($user);
}


?>
<html>

<header>
<title>Scrabble Home</title>

<script src="../libraries/jquery-3.3.1.min.js"></script>

<script defer src="../libraries/jquery-3.3.1.min.js"></script>
<link rel="stylesheet" href="../libraries/bootstrap/css/bootstrap.min.css">
<script src="../libraries/bootstrap/js/bootstrap.min.js"></script>

</header>

<style>

#Turn{
	max-width: 30%;
	text-align:center;
	position: absolute;
	margin-left: 40%;
	
	
}

.loader {
  padding-right: 25px;
  border: 4px solid #f3f3f3;
  border-radius: 50%;
  border-top: 4px solid #18bc9c;
  width: 50px;
  height: 50px;
  -webkit-animation: spin 1s linear infinite; /* Safari */
  animation: spin 1s linear infinite;
}
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.tooltip {
  position: relative;
  display: inline-block;
  border-bottom: 1px dotted black; /* If you want dots under the hoverable text */
}

/* Tooltip text */
.tooltip .tooltiptext {
  visibility: hidden;
  width: 120px;
  bottom: 100%;
  left: 50%;
  margin-left: -20px;
  background-color: gray;
  color: #fff;
  text-align: center;
  padding: 5px 0;
  border-radius: 6px;
 
  /* Position the tooltip text - see examples below! */
  position: absolute;
  z-index: 1;
}

/* Show the tooltip text when you mouse over the tooltip container */
.tooltip:hover .tooltiptext {
  visibility: visible;
}
#ScrabbleContainer{
	min-width: 610px;
	min-height: 610px;
	z-index:100;
}

#ScrabbleBoard input{
	width:40px;
	height:40pxpx;
	outline: 2px solid #808080;
    font-family: arial;
    font-size: 26px;
    
    letter-spacing: 6px;
	text-transform:uppercase;
}

.normalText{
	background-color: #F5F5DC;

}
.DWS{
	background-color: #FFB6C1;
}

.TWS{
	background-color: #CD5C5C;

}
.TLS{
	background-color: #1E90FF;

}
.DLS{
	background-color: #87CEFA;

}

#pieceContainer{
	min-width: 300px;
	min-height: 85px;
	
}

#pieceContainer input{
	width:40px;
	height:40pxpx;
	outline: 2px solid #808080;
    font-family: arial;
    font-size: 26px;
    
    letter-spacing: 6px;
	text-transform:uppercase;    
}

</style>


<body onload="init()">
	<div id="Turn">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">Waiting for Player to finish his turn</h5>
				<p id="someText" class="card-text">There will eventually be a spinning circle here</p>
					<div id="centerloader" class="loader"></div>
			</div>
		</div>
	</div>
	<br>
	<label>Time Remaining:</label><input type="text" id="timer" readonly></input>

	<div id="ScrabbleContainer"></div>
	<div id="ChatMessages"></div>
	<div id="ChatBig"> 
		<span style="color:green">Chat</span><br>
		<textarea id="ChatText" name="ChatText"></textarea>
	</div>

</body>

<script>
function init(){
	//Draws board
	gameID = "<?php  print $gameID; ?>"
	var result = fetchFile("python/" + gameID + "old.json")
	board = result["board"]
	htmlBoard = redrawBoard(board)
	document.getElementById("ScrabbleContainer").innerHTML = htmlBoard
	
	//Should be a boolean so it will return true or false 
	user = "<?php print $user; ?>"
	//InitiateSearch was executed in php segment of code
	interval = setInterval(checkFinish, 1000)
	setInterval(timeCheck, 999);
	
	
}
function timeCheck(){
	$.ajax({
		url: 'matchmaking/executeFunction.php',
		type: 'POST',
		async: false,
		data:{fName:"getTime", gameID:gameID},
		beforeSend: function() {
			console.log("Getting Time")
		},
		fail: function(xhr, status, error) {
			alert("Error Message:  \r\nNumeric code is: " + xhr.status + " \r\nError is " + error);
		},
		success: function(result) {
			timer = (result);
			document.getElementById("timer").value = timer;
			console.log("Retrieved Time")
		}
	});
	
}

function fetchFile(filename){
	console.log("fetchFile Function")
	
	var temp = ""
	$.ajax({
	type:'POST',
	async: false,
	url: "fetchStats.php",
	data: {file:filename},
	dataType: "json"
	})
	.done(function(msg){
		console.log("succesfully retrieved User data");
		//console.log(msg);
		temp = msg;
	})
	.fail(function(msg){
		console.log("failed to retrieve User data");
		console.log(msg);
		
	});
	return temp
}
function redrawBoard(board){
	console.log("redrawBoard Function")
	var temp = ""
	$.ajax({
		type:'POST',
		async: false,	
		url: "redrawBoard.php",
		data: {board1: board},
		dataType: "text"
		
	})
	.done(function(msg){
		console.log("succefully drew board" + msg);
		document.getElementById("ScrabbleContainer").innerHTML = msg;
		temp = msg;
		
	})
	.fail(function(msg){
		console.log("failed to draw board");
		console.log(msg);
	});
	return temp;
}
function checkFinish(){
	temp = checkTurnPriority()
	console.log(temp)
	if(temp == true){
		clearInterval(interval);
		window.location.replace("scrabbleGame.php");
	}
}

function checkTurnPriority(){
	var turnPriority = false
	$.ajax({
		url: 'matchmaking/executeFunction.php',
		type: 'POST',
		async: false,
		data:{fName:"discoverPriority", user1:user},
		beforeSend: function() {
			//$("#centerloader").addClass("loader");
			console.log("Checking turn priority")
		},
		fail: function(xhr, status, error) {
			alert("Error Message:  \r\nNumeric code is: " + xhr.status + " \r\nError is " + error);
		},
	
		success: function(result) {
			//$("#centerloader").removeClass("loader");
			console.log("turn priority: " + result )
			turnPriority = (result == 'true');
		}
	});	
	//returns true if a match is found, otherwise returns false
	console.log("turn priority: " + turnPriority)
	return turnPriority
}




$(document).ready(function() {
	$("#ChatText").keyup(function(e){
			if(e.keyCode == 13) {
					
				var ChatText = $("#ChatText").val();
				$.ajax({
					type:'POST',
					url:'chat/insertMessage.php',
					data:{ChatText:ChatText},
					success:function()
					{
						$("#ChatText").val("");
					
					},
					fail:function()
					{
					alert('request failed');
					}

				})
			}
	})
	
	setInterval(function(){
			$("#ChatMessages").load("chat/DisplayMessages.php");
	},1500)
	
	$("#ChatMessages").load("chat/DisplayMessages.php");
	
});


</script>







</html>
