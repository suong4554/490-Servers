<?php

//include("../../account.php");
//include("Function.php");
session_start();

error_reporting(E_ALL);
ini_set('display_errors',on);




#require_once('../../path.inc');
#require_once('../../get_host_info.inc');
#require_once('../../rabbitMQLib.inc');


require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient('MySQLRabbit.ini', 'MySQLRabbit');
#$client = new rabbitMQClient('../../MySQLRabbit.ini', 'MySQLRabbit');

if(isset($_POST["fName"])){
	$functionName = $_POST['fName'];
}


if($functionName == "initiateSearch"){
	$user = $_POST['user1'];
	$request = array();
	$request['type'] = "initiateSearch";
	$request['user1'] = $user;
	$request["message"] = "ugh";
	$response = $client->send_request($request);
}
elseif($functionName == "findMatch"){
	$request = array();
	$request['type'] = "findMatch";
	$response = $client->send_request($request);
	$temp = $response["result"];
	$request["message"] = "ugh";
	print(json_encode($temp));
	#it prints out 1 if there's not matches available and 2 if there are
}
elseif($functionName == "getLooking"){
	$user = $_POST['user1'];
	$temp = getLooking($user);
	$request = array();
	$request['type'] = "getLooking";
	$request['user1'] = $user;
	$request["message"] = "ugh";
	$response = $client->send_request($request);
	$temp = $response["result"];
	print(json_encode(!$temp));
}
elseif($functionName == "checkGameState"){
	$user = $_POST['user1'];
	$request = array();
	$request['type'] = "checkGameState";
	$request['user1'] = $user;
	$request["message"] = "ugh";
	$response = $client->send_request($request);
	$temp = $response["result"];
	print(json_encode($temp));
}
elseif($functionName == "getOtherUser"){
	$user = $_POST['user1'];
	$request = array();
	$request['type'] = "getOtherUser";
	$request['user1'] = $user;
	$request["message"] = "ugh";
	$response = $client->send_request($request);
	$temp = $response["result"];
	#temp should be equal to username of other player looking for match
	print($temp);
}
elseif($functionName == "getOtherUserinGame"){
	$user = $_POST['user1'];
	$request = array();
	$request['type'] = "getOtherUserinGame";
	$request['user1'] = $user;
	$request["message"] = "ugh";
	$response = $client->send_request($request);
	$temp = $response["result"];
	#temp should be equal to username of other player looking for match
	print($temp);
}
elseif($functionName == "initiateMatch"){
	$user1 = $_POST["user1"];
	$user2 = $_POST["user2"];
	$request = array();
	$request['type'] = "initiateMatch";
	$request['user1'] = $user1;
	$request['user2'] = $user2;
	$request["message"] = "ugh";
	$response = $client->send_request($request);
}
elseif($functionName == "discoverPriority"){
	$user = $_POST['user1'];
	$request = array();
	$request['type'] = "discoverPriority";
	$request['user1'] = $user;
	$request["message"] = "ugh";
	$response = $client->send_request($request);
	$temp = $response["result"];
	print(json_encode(!$temp));
}
elseif($functionName == "getUserScore"){
	$user = $_POST['user1'];
	$request = array();
	$request['type'] = "getUserScore";
	$request['user1'] = $user;
	$request["message"] = "ugh";
	$response = $client->send_request($request);
	$temp = $response["result"];
	print ($temp);
}
elseif($functionName == "updateUserScore"){
	$user = $_POST["user1"];
	$score = $_POST["score1"];
	$request = array();
	$request['type'] = "updateUserScore";
	$request['user1'] = $user;
	$request['score1'] = $score;
	$request["message"] = "ugh";
	$response = $client->send_request($request);
}
elseif($functionName == "switchTurn"){
	#user1 will switch from his turn to next player's turn
	$user1 = $_POST["user1"];
	$user2 = $_POST["user2"];
	$request = array();
	$request['type'] = "switchTurn";
	$request['user1'] = $user1;
	$request['user2'] = $user2;
	$request["message"] = "ugh";
	$response = $client->send_request($request);
}
elseif($functionName == "updateMatch"){
	#Should be executed at the end of every round/turn
	#updates turn per each user, not an issue since user's wont be able to end their turn again until other player end's their turn
	$user1 = $_POST["user1"];
	$turn = $_POST["turn"];
	$request = array();
	$request['type'] = "updateMatch";
	$request['user1'] = $user1;
	$request['turn'] = $turn;
	$request["message"] = "ugh";
	$response = $client->send_request($request);
}
elseif($functionName == "endMatch"){
	$user1 = $_POST["user1"];
	$user2 = $_POST["user2"];
	$request = array();
	$request['type'] = "endMatch";
	$request['user1'] = $user1;
	$request['user2'] = $user2;
	$request["message"] = "ugh";
	$response = $client->send_request($request);
}
elseif($functionName == "cancelSearch"){
	$user = $_POST["user1"];
	$request = array();
	$request['type'] = "cancelSearch";
	$request['user1'] = $user;
	$request["message"] = "ugh";
	$response = $client->send_request($request);
}
else{
	print("Function not Found");
}





?>