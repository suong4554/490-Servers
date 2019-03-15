<?php

error_reporting(E_ALL);
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
echo $user;

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

foreach($response as $match){
	#print_r()
	print($match["playerOneUser"]);
	print($match["playerTwoUser"]);
	print("<br>");
}
//print_r($response);
print("<br>");
//echo $_SESSION["login"];

?>

