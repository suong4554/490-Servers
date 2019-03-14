<?php


#date_default_timezone_set("America/New_York");
#session_set_cookie_params(0, "/var/www/html", "localhost");
session_start();


include("Function.php");

$words = $_POST['wordsArr'];
##############################################
####### RABBITMQ CODE ########################
##############################################

#session_start();

$user = $_SESSION["user"];
$pass = $_SESSION["password"];

require_once('rabbit/path.inc');
require_once('rabbit/get_host_info.inc');
require_once('rabbit/MQLib.inc');

$client = new rabbitMQClient('/rabbit/MYSQLRabbit.ini', 'MySQLRabbit';

$msg = "Code faster";

$request = array();
$request['type'] = "checkWords";
$request['words'] = $words;
$request['message'] = $msg;

$response = $client->send_request($request);

//$_SESSION["login"] = $response["result"];
#echo $response["message"];

print_r($response);
print("<br>");
//echo $_SESSION["login"];

?>

