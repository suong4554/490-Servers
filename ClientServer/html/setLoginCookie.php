<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);	
#date_default_timezone_set("America/New_York");
#session_set_cookie_params(0, "/var/www/html", "localhost");
session_start();


include("Function.php");


#echo "<br> User: "; getdata("user", $user);
#echo "<br> Pass: "; getdata("password", $pass);

$user = $_POST["user"];
$pass = $_POST["password"];

$_SESSION["user"] = $user;



#echo $_SESSION["user"];
#echo $_SESSION["password"];

##############################################
####### RABBITMQ CODE ########################
##############################################

#session_start();



require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient('MySQLRabbit.ini', 'MySQLRabbit');

$msg = "hi";

$request = array();
$request['type'] = "Login";
$request['username'] = $user;
#$request['password'] = sha1($pass);
$request['password'] = $pass;
$request['message'] = $msg;

$response = $client->send_request($request);

$_SESSION["login"] = $response["result"];
#echo $response["message"];

#print_r($response);
#print("<br>");
#echo $_SESSION["login"];
//$_SESSION["login"] = false;
if($_SESSION["login"]){
	redirect("", "home.php", 0);
	exit();
	print("hello");
}
else{
	redirect("", "index.php",0);
	exit();
}




?>

