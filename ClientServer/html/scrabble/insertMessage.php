<?php

session_start();

include "chat.php";

if(isset($_POST['ChatText']))
{
	$chat = new chat();
	$chat->setChatUsername($_SESSION['user'];
	$chat->setChatGameId($_SESSION['gameID'];
	$chat->setChatText($_POST['ChatText']);
	$chat->InsertChatMessage();
}
?>
