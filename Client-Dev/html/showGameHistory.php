<?php

#error_reporting(E_ALL);
ini_set('display_errors', 1);	
#date_default_timezone_set("America/New_York");
#session_set_cookie_params(0, "/var/www/html", "localhost");
session_start();


include("Function.php");

if((!isset($_SESSION["login"])) or (!$_SESSION["login"])){
	redirect("", "index.php", 0);
	exit();
}






##############################################
####### RABBITMQ CODE ########################
##############################################

#session_start();

$user = $_SESSION["user"];

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient('MySQLRabbit.ini', 'MySQLRabbit');

$msg = "Schnitzel";

$request = array();
$request['type'] = "showMatchHistory";
$request['username'] = $user;
$request['message'] = $msg;

$response = $client->send_request($request);

$response = $response["result"];
//$_SESSION["login"] = $response["result"];
#echo $response["message"];

/*
$response = [];

$match = array(
"playerOneUser" => "One",
"playerTwoUser" => "Two",
"winner" => "One",
"playerOneScore" => 10,
"playerTwoScore" => 5,
"turnsUsed" => 2,
"gameDate" => "1/5/2010",
);

array_push($response, $match);
array_push($response, $match);
*/
################################################
##########Processing Result#####################
################################################

$tableHTML = "
<table class='table'>
	<thread class='thead-dark'>
		<tr>
			<th scope='col'>Game Date</th>
			<th scope='col'>Player One</th>
			<th scope='col'>Player Two</th>
			<th scope='col'>Winner</th>
			<th scope='col'>Player One Score</th>
			<th scope='col'>Player Two Score</th>
			<th scope='col'>Game Length (turns)</th>
		</tr>
	</thread>
	<tbody>

";#End of String

foreach($response as $match){
	$tempStr = "";
	#GameId
	#playerOneUser
	#playerTwoUser
	#winner
	#playerOneScore
	#playerTwoScore
	#turnsUsed
	#gameDate
	
	#print_r()
	$p1=$match["playerOneUser"];
	$p2=$match["playerTwoUser"];
	$w=$match["winner"];
	$p1S=$match["playerOneScore"];
	$p2S=$match["playerTwoScore"];
	$tU=$match["turnsUsed"];
	$gD=$match["gameDate"];
	
	
	
	$tempStr = "
	<tr>
		<th scope='row'>$gD</th>
		<td>$p1</td>
		<td>$p2</td>
		<td>$w</td>
		<td>$p1S</td>
		<td>$p2S</td>
		<td>$tU</td>
	</tr>
	
	";
	
	
	$tableHTML = $tableHTML . $tempStr;
}


$tableHTML = $tableHTML . "</tbody></table>";
$tableHTML = str_replace("\n", "", $tableHTML);

?>
<html>

<header>
<title>Match History</title>

<script defer src="libraries/jquery-3.3.1.min.js"></script>
<link rel="stylesheet" href="libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css">
<script src="libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
<header>


<body>
<button type="button" id="back" onClick="location.href = 'home.php'">Go Back to Home Page</button>
<br>
<button type="button" id="logout" onClick="location.href = 'logout.php'">Logout</button>
<br>
<div id="history" onload="init()">
		
</div>







</body>


<script>

//Get php variable and create function that fetches it.

function init(){
	
	var table = "<?php print $tableHTML; ?>";
	document.getElementById("history").innerHTML = table;
	console.log(table)
}
init();

</script>








</html>













































