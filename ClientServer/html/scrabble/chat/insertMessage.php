
<?php
session_start();
/*
//session_set_cookie_params(0, "/var/www/html", "localhost");
include "chat.php";
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
print($_SESSION['user'] . $_SESSION['gameID']);

	if(isset($_POST['ChatText'])){
		$chat = new chat();
		$chat->setChatUsername($_SESSION['user']);
		$chat->setChatGameId($_SESSION['gameID']);
		$chat->setChatText($_POST['ChatText']);
		$chat->InsertChatMessage();
		
		}
		
		*/
		
session_start();
require_once('../../path.inc');
require_once('../../get_host_info.inc');
require_once('../../rabbitMQLib.inc');
session_start();
$client = new rabbitMQClient('../../MySQLRabbit.ini', 'MySQLRabbit');

$request = array();
$request['type'] = "insertChatMsg";
$request['user1'] = $_SESSION['user'];
$request['gameID'] = $_SESSION['gameID'];
$request['text'] = $_SESSION['text'];
$response = $client->send_request($request);
?>