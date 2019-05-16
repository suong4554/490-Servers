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


function createTable($array){
	$tableHeader = "<table class='table'> <thead class='thead-dark'> <tr>";
	$tableBody = "";
	$tempArr = array();

	foreach($array as $dataS){
		$tempBody = "";
		foreach($dataS as $key => $data){
			$data = (string)$data;
			#print_r($data);
			$tempHead = "<th scope='col'>$key</th>";
			if(!in_array($tempHead, $tempArr)){
				array_push($tempArr, $tempHead);
				$tableHeader = $tableHeader . $tempHead;
			}
			$tempBody .= "<td>$data</td>";
		}
		
		$tempBody = "<tr> $tempBody </tr>";
		$tableBody .= $tempBody;
	}
	$tableHeader = $tableHeader . "</tr> </thread> <tbody>";
	$tableTail = "</tbody></table>";
	$table = $tableHeader . $tableBody . $tableTail;
	#print($table);
	
	
	return($table);
	
}
$tableHTML = createTable($response);
?>
<html>

<header>
<title>Match History</title>

<script src="libraries/jquery-3.3.1.min.js"></script>


<link rel="stylesheet" href="libraries/bootstrap/css/bootstrap.min.css">
<script src="libraries/bootstrap/js/bootstrap.min.js"></script>

<header>


<body>
<br>
<div style="padding-left: 10px;">
<button type="button" id="back"  onClick="location.href = 'home.php'" class="btn btn-primary">Go Back to Home Page</button>
<button type="button" id="logout" onClick="location.href = 'logout.php'" class="btn btn-danger">Logout</button>
</div>
<br>
<br>
<div id="history" onload="init()" <div style="padding-left: 10px;padding-right:10px">>
		
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













































