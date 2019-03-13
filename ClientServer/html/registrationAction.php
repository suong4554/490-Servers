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

require_once('rabbit/path.inc');
require_once('rabbit/get_host_info.inc');
require_once('rabbit/rabbitMQLib.inc');

$client = new rabbitMQClient('rabbit/MYSQLRabbit.ini', 'MySQLRabbit');

$msg = "Sending login request";

$request = array();
$request['type'] = "Registration";
$request['user'] = $user;
$request['password'] = $pass;
$request['password2'] = $pass2;
$request['email'] = $email;


#testing set response to false or true
$response = $client->send_request($request);





$_SESSION["registration"] = $response["result"];


#echo $response["message"];

print_r($response);
print("<br>");
echo $_SESSION["registration"];

if($_SESSION["registration"] == True){
 redirect("Invalid email, password or username", "registration.html", 3);
 exit();
}
elseif($_SESSION["registration"] == False){
	$_SESSION["login"] = True;
	redirect("", "index.php", 0);
	exit();
}





#put register into the server if statement for registration with the following var
#register ($user, $pass, $pass2,$email);
#verification($user, $pass);



redirect("", "index.php", 0);

?>
