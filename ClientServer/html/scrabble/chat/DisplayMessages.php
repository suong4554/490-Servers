<?php
/*
	session_start();
	include "chat.php";
	$chat = new chat();
	$chat->setChatUsername($_SESSION['user']);
	$chat->setChatGameId($_SESSION['gameID']);
	$chat->DisplayMessage();
	*/
session_start();
require_once('../../path.inc');
require_once('../../get_host_info.inc');
require_once('../../rabbitMQLib.inc');
session_start();
$client = new rabbitMQClient('../../MySQLRabbit.ini', 'MySQLRabbit');

$request = array();
$request['type'] = "displayMsg";
$request['user1'] = $_SESSION['user'];
$request['gameID'] = $_SESSION['gameID'];
$response = $client->send_request($request);

$response = $response["result"];
print($response);

?>