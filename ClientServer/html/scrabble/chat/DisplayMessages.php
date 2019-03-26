<?php
	session_start();
	include "chat.php";
	$chat = new chat();
	$chat->setChatUsername($_SESSION['user']);
	$chat->setChatGameId($_SESSION['gameID']);
	$chat->DisplayMessage();
?>