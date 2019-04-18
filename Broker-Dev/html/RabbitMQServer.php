#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

include('account.php');
include('Function.php');
include('MatchmakeFunction.php');

//include "chat.php";

error_reporting(E_ALL);
ini_set('display_errors',on);

$db = mysqli_connect($servername, $username, $password , $project);
if (mysqli_connect_errno())
  {
	  $message = "Failed to connect to MySQL: " . mysqli_connect_error();
	  echo $message;
	  error_log($message);
	  exit();
  }

mysqli_select_db( $db, $project );

$logName = "RabbitReceived_Sent.txt";
function rabbitLog($array, $filename){
	if(file_exists($filename)){
		$myfile = fopen($filename, "a");
		$text = serialize($array);
		$date = date('Y-m-d');
		fwrite($myfile, $date);
		fwrite($myfile, "\n");
		fwrite($myfile, $text);
		fwrite($myfile, "\n");
	}
	else{
		$myfile = fopen($filename, "w");
		$text = serialize($array);
		$date = date('Y-m-d');
		fwrite($myfile, $date);
		fwrite($myfile, "\n");
		fwrite($myfile, $text);
		fwrite($myfile, "\n");
	}
}


function wordCheck($request){
	//sends a message to the DMZ server
	$client = new rabbitMQClient("DMZ.ini", "DMZ");
	$response = $client->send_request($request);
	$response = $response["result"];
    return $response;
	
}


function requestProcessor($request){
$logName = "RabbitReceived_Sent.txt";
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
	  $message = "Error: unsupported message type";
	  error_log($message);
	  return $message;
	 
  }
  rabbitLog($request, $logName);
  $result = "";
  $temp = $request['type'];
  if($temp == 'Login'){
	  $result = auth($request['username'],$request['password']);
  }
  else if ($temp == 'Registration'){
	  $result = register($request['username'],$request['password'],$request['password2'],$request['email']);
	  print($result);
  }
   else if ($temp == 'recordGame'){
	  $result = recordGame($request['user1'],$request['user2'],$request['winner'],$request['score1'],$request['score2'],$request['turns']);
	  print($result);
  }
   else if ($temp == 'showMatchHistory'){
	  $result = [];
	  $result = show($request['username'], $result);
	  print_r($result);
  }
  else if($temp == "checkWords"){
	$result = wordCheck($request);
	print($result);
  }
  else if($temp == "toControl"){
	$cmd = "bash /home/transfer/scripts/initiateTransfer.txt";
	$result = shell_exec($cmd);
	print($result);
  }
  else if($temp == "fromControl"){
	$version = $request['versionFile'];
	$location = $request['location'];
	
	$cmd = "bash /home/transfer/scripts/initiateRetrieval.txt " . $location . " " . $version . "  > /dev/null &";
	$result = shell_exec($cmd);
	$result = "rollback acknowledged";
	print($result);
  }
  
  rabbitLog(array("returnCode" => '0', 'message'=>'Server acknowledged', 'result' => $result), $logName);
  return array("returnCode" => '0', 'message'=> "Server received request and processed", 'result' => $result);
  #needs to be after as it also reinstall rabbitmq
  
}

$server = new rabbitMQServer("MySQLRabbit.ini","MySQLRabbit");

echo "MySQLRabbitServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "MySQLRabbitServer END".PHP_EOL;
exit();
?>

