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
	print($user);
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
</style>


<body>
<div id="Turn">
	<div class="card">
		<div class="card-body">
			<h5 class="card-title">Waiting for Player to finish his turn</h5>
			<p id="someText" class="card-text">There will eventually be a spinning circle here</p>

				<div id="centerloader" class="loader"></div>
		</div>
	</div>
</div>

</body>

<script>
function init(){
	//Should be a boolean so it will return true or false 
	
	user = "<?php print $user; ?>"
	//InitiateSearch was executed in php segment of code
	interval = setInterval(checkFinish, 1000)
	
	
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
$(document).ready(function(){
init()
});

</script>





</html>
