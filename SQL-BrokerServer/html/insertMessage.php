<?php
session_start();
//session_set_cookie_params(0, "/var/www/html", "localhost");
include "chat.php";
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);


//$_SESSION['user'] = 'sam';
//$_SESSION['gameId'] = 0;
//$_POST['ChatText'] = 'testttt';

	if(isset($_POST['ChatText'])){
		$chat = new chat();
		$chat->setChatUsername($_SESSION['user']);
		$chat->setChatGameId($_SESSION['gameId']);
		$chat->setChatText($_POST['ChatText']);
		$chat->InsertChatMessage();
		
		}
?>
