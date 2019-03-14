#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

include('account.php');
include('Function.php');

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



function wordCheck($request){
	//sends a message to the DMZ server
	$client = new rabbitMQClient("DMZ.ini", "DMZ");
	$response = $client->send_request($request);
	$response = $response["result"];
    return $response;
	
}


function requestProcessor($request){
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
	  $message = "Error: unsupported message type";
	  error_log($message);
	  return $message;
	 
  }
  $result = "";
  $temp = $request['type'];
  if($temp == 'Login'){
   $result = auth($request['user'],$request['password'], $result);
    print($result);
  }

  else if ($temp == 'Registration'){
	  $result = register($request['user'],$request['password'],$request['password2'],$request['email']);
	  print($result);
  }
  
   else if ($temp == 'recordGame'){
	  $result = recordGame($request['user1'],$request['user2'],$request['winner'],$request['score1'],$request['score2'],$request['turns']);
	  print($result);
  }
   else if ($temp == 'showMatchHistory'){
	  $result = [];
	  $result = show($request['user'], $result);
	  print($result);
  }
  else if($temp == "checkWords"){
	$result = wordCheck($request);
	print($result;)
  }
  
  
  return array("returnCode" => '0', 'message'=> "Server received request and processed", 'result' => $result);
  
}

$server = new rabbitMQServer("MySQLRabbit.ini","MySQLRabbit");

echo "MySQLRabbitServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "MySQLRabbitServer END".PHP_EOL;
exit();
?>

