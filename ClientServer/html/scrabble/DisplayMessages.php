<?php
session_start();

include "chat.php";

$chat = new chat();

$chat->setChatUsername($_SESSION['Username']);

$chat->setChatGameId($_SESSION['GameId']);

$chat->DisplayMessage();

?>
