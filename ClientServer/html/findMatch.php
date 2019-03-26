<?php
//Wanted to note that SQL is called locally since this is basically used as a cache for only matchmaking. 
//If we continuously call rabbitmq in a while loop to send and receive message, we end up "clogging" the queue causing it to freeze
//Thus we call SQL locally by design choice, not because we did not know how to code this step
//date_default_timezone_set("America/New_York");
//session_set_cookie_params(0, "/var/www/html");
session_start();
//$_SESSION = array();
include("account.php");
//include("scrabble/matchmaking/Function.php");


error_reporting(E_ALL);
ini_set('display_errors',on);




//testing
//$_SESSION["login"] = true;
//$_SESSION["user"]= "Bill";
//print($_SESSION["user"]);


#################################Initiates Connection to SQL SERVER################################
/*
$db = mysqli_connect($servername, $username, $password , $project);
if (mysqli_connect_errno())
  {
	  $message = "Failed to connect to MySQL: " . mysqli_connect_error();
	  echo $message;
	  error_log($message);
	  exit();
  }

mysqli_select_db( $db, $project );
*/
###################################################################################################


require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient('MySQLRabbit.ini', 'MySQLRabbit');

if((!isset($_SESSION["login"])) or (!$_SESSION["login"])){
	redirect("", "index.php", 0);
	//exit();
}
elseif(file_exists("scrabble/gameState/" . $_SESSION["user"] . "gameState.txt")){
	redirect("", "scrabble/scrabbleGame.php", 0);
}
else{
	$user = $_SESSION["user"];
	//Puts player into sql table for matchmaking
	
	//Initiates search
	$request = array();
	$request['type'] = "initiateSearch";
	$request['user1'] = $user;
	$request["message"] = "ugh";
	$response = $client->send_request($request);
	
	//Finds if someone else is looking for a match
	sleep(1);
	$request = array();
	$request['type'] = "findMatch";
	$request["message"] = "ugh";
	$response = $client->send_request($request);
	$peasant = $response["result"];
	$peasant = findMatch();
	//$peasant = true;
	if(!$peasant){
		print("false");
	}
	else{
		print("true");
	}
	
	if($peasant){
		$dominance = false;
		redirect("", "scrabble/scrabbleGame.php", 0);
	}
	else{
		$dominance = true;
	}
}


?>
<html>

<header>
<title>Scrabble Home</title>

<!--<script src="libraries/jquery-3.3.1.min.js"></script>-->
<script src="libraries/jquery.js"></script>
<script defer src="libraries/jquery-3.3.1.min.js"></script>
<link rel="stylesheet" href="libraries/bootstrap/css/bootstrap.min.css">
<script src="libraries/bootstrap/js/bootstrap.min.js"></script>

</header>

<style>

#MatchMake{
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
</style>


<body>
<div id="MatchMake">
	<div class="card">
		<div class="card-body">
			<h5 class="card-title">Finding You a Match</h5>
			<p id="someText" class="card-text">There will eventually be a spinning circle here</p>

				<div id="centerloader" class="loader"></div>

			<button onclick="cancel()" class="btn btn-primary">Cancel</button>
		</div>
	</div>
</div>

</body>

<script>
function init(){
	//Should be a boolean so it will return true or false 
	dominance = <?php print $dominance; ?>
	
	user = "<?php print $user; ?>"
	//InitiateSearch was executed in php segment of code
	if(dominance){
		interval = setInterval(checkFinish, 1000)
		
	}
	
}

function checkFinish(){
	temp = searchForMatches()
	temp2 = getLooking()
	console.log(temp + temp2)
	if(temp == true || temp2 == true){
		clearInterval(interval);
		$("#centerloader").removeClass("loader");
		otherUser = getOtherUser();
		console.log(otherUser);
		
		initiateMatch()
		window.location.replace("scrabble/scrabbleGame.php");
	}
}

function getLooking(){
	var inMatch = ""
	$.ajax({
		url: 'scrabble/matchmaking/executeFunction.php',
		type: 'POST',
		async: false,
		data:{fName:"getLooking", user1:user},
		beforeSend: function() {
			console.log("Getting Looking stat")
		},
		fail: function(xhr, status, error) {
			alert("Error Message:  \r\nNumeric code is: " + xhr.status + " \r\nError is " + error);
		},
		success: function(result) {
			inMatch = (result == 'true');
			console.log("user currently in match:" + inMatch)
		}
	});	
	//returns the username of the other user looking for a match
	return inMatch
}

function searchForMatches(){
	var matchAvailable = false
	$.ajax({
		url: 'scrabble/matchmaking/executeFunction.php',
		type: 'POST',
		async: false,
		data:{fName:"findMatch"},
		beforeSend: function() {
			//$("#centerloader").addClass("loader");
			console.log("Searching for matches")
		},
		fail: function(xhr, status, error) {
			alert("Error Message:  \r\nNumeric code is: " + xhr.status + " \r\nError is " + error);
		},
	
		success: function(result) {
			//$("#centerloader").removeClass("loader");
			console.log("findmatch: " + result)
			//matchAvailable = (result == 'true');
			matchAvailable = (result == 'true');
		}
	});	
	//returns true if a match is found, otherwise returns false
	console.log("matchesAvailable: " + matchAvailable)
	return matchAvailable
}

function getOtherUser(){
	var otherUser = ""
	$.ajax({
		url: 'scrabble/matchmaking/executeFunction.php',
		type: 'POST',
		async: false,
		data:{fName:"getOtherUser", user1:user},
		beforeSend: function() {
			console.log("Getting other User")
			//$("#centerloader").addClass("loader");
		},
		fail: function(xhr, status, error) {
			alert("Error Message:  \r\nNumeric code is: " + xhr.status + " \r\nError is " + error);
		},
	
		success: function(result) {
			//$("#centerloader").removeClass("loader");
			console.log("other user is:" + result)
			otherUser = result;
		}
	});	
	//returns the username of the other user looking for a match
	return otherUser
}




function initiateMatch(){
	$.ajax({
		url: 'scrabble/matchmaking/executeFunction.php',
		type: 'POST',
		async: false,
		data:{fName:"initiateMatch", user1:user, user2:otherUser},
		beforeSend: function() {
			console.log("Initiating match on SQL Database")
			//$("#centerloader").addClass("loader");
		},
		fail: function(xhr, status, error) {
			alert("Error Message:  \r\nNumeric code is: " + xhr.status + " \r\nError is " + error);
		},
	
		success: function(result) {
			//$("#centerloader").removeClass("loader");
			otherUser = result;
		}
	});	
}


function cancel(){
	$.ajax({
		url: 'scrabble/matchmaking/executeFunction.php',
		type: 'POST',
		async: false,
		data:{fName:"cancelSearch", user1:user},
		fail: function(xhr, status, error) {
			alert("Error Message:  \r\nNumeric code is: " + xhr.status + " \r\nError is " + error);
		},
		success: function(result) {
			console.log("cancelled search")
		}
	});	
	window.location.replace("home.php");
}


init()

</script>

</html>
