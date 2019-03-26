#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

include('account.php');
include('Function.php');
include('MatchmakeFunction.php');

include "chat.php";

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

function insertChatMsg($user, $gameID, $text){
	$chat = new chat();
	$chat->setChatUsername($user);
	$chat->setChatGameId($gameID);
	$chat->setChatText($text);
	$chat->InsertChatMessage();
}


function displayMsg($user, $gameID){
	$chat = new chat();
	$chat->setChatUsername($user);
	$chat->setChatGameId($gameID);
	$temp = $chat->DisplayMessage();
	return $temp;
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
  if($temp == 'Login')
  {
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
	  $result = show($request['user'], $result);
	  print($result);
  }
  else if($temp == "checkWords"){
	$result = wordCheck($request);
	print($result);
  }
   else if($temp == "initiateSearch"){
	$result = initiateSearch($request['user1']);
	print($result);
  }
   else if($temp == "findMatch"){
	$result = findMatch();
	print($result);
  }
   else if($temp == "getLooking"){
	$result = getLooking($request['user1']);
	print($result);
  }
  else if($temp == "checkGameState"){
	$result = checkGameState($request);
	print($result);
  }
  else if($temp == "getOtherUser"){
	$result = getOtherUser($request['user1']);
	print($result);
  }
  else if($temp == "getOtherUserinGame"){
	$result = getOtherUserinGame($request['user1']);
	print($result);
  }
  else if($temp == "initiateMatch"){
	$result = initiateMatch($request['user1'], $request['user2']);
	print($result);
  }
  else if($temp == "discoverPriority"){
	$result = discoverPriority($request['user1']);
	print($result);
  }
  else if($temp == "getUserScore"){
	$result = getUserScore($request['user1']);
	print($result);
  }
  else if($temp == "updateUserScore"){
	$result = updateUserScore($request['user1'], $request['score1']);
	print($result);
  }
  else if($temp == "switchTurn"){
	$result = switchTurn($request['user1'], request['user2']);
	print($result);
  }
  else if($temp == "updateMatch"){
	$result = updateMatch($request['user1'], $request['turn']);
	print($result);
  }  
   else if($temp == "endMatch"){
	$result = endMatch($request['user1'], $request['user2']);
	print($result);
  }  
   else if($temp == "cancelSearch"){
	$result = cancelSearch($request['user1']);
	print($result);
<<<<<<< HEAD
<<<<<<< HEAD
  } 
  else if($temp == "findInfo"){
	  $result = findInfo($request['user1'], $request['info']);
	  print($result);
  }
  else if($temp == "insertChatMsg"){
	  $result = insertChatMsg($request['user'], $request['gameID'], $request['text']);
  }
  else if($temp == "displayMsg"){
	  $result = displayMsg($request['user'], $request['gameID']);
	  print($result);
  }
=======
  }  
>>>>>>> parent of c0765e0... rabbitmq compat
=======
  }  
>>>>>>> parent of c0765e0... rabbitmq compat
  
  
  return array("returnCode" => '0', 'message'=> "Server received request and processed", 'result' => $result);
  
}

$server = new rabbitMQServer("MySQLRabbit.ini","MySQLRabbit");

echo "MySQLRabbitServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "MySQLRabbitServer END".PHP_EOL;
exit();
?>

