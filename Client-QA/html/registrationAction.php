<?php
session_start();
date_default_timezone_set("America/New_York");
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors' , 1);

include ("Function.php");


#Gets data from header
$user = $_GET["user"];
$pass = $_GET["password"];
$pass2 = $_GET["password2"];
$email = $_GET["email"];



######################################3
########### Rabbit MQ #################
########################################

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient('MySQLRabbit.ini', 'MySQLRabbit');
#$client = new rabbitMQClient('testRabbitMQ.ini', 'testServer');

$msg = "Sending login request";

$request = array();
$request['type'] = "Registration";
$request['username'] = $user;
#$request['password'] = sha1($pass);
#$request['password2'] = sha1($pass2);
$request['password'] = $pass;
$request['password2'] = $pass2;
$request['email'] = $email;


#testing set response to false or true
$response = $client->send_request($request);





$_SESSION["registration"] = $response["result"];


#echo $response["message"];

//print_r($response);
//print("<br>");
//echo $_SESSION["registration"];

if($_SESSION["registration"] == True){
 redirect("Invalid email, password or username <br> you will be shortly redirected", "registration.html", 5);
 exit();
}
elseif($_SESSION["registration"] == False){
	$_SESSION["login"] = True;
	$_SESSION["user"] = $user;
	redirect("", "index.php", 0);
	exit();
}





#put register into the server if statement for registration with the following var
#register ($user, $pass, $pass2,$email);
#verification($user, $pass);



redirect("", "index.php", 0);

?>
