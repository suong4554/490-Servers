
<?php
session_start();
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
?>