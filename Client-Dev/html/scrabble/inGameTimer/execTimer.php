#!/usr/bin/php
<?php


$gameID = $_POST['gameID'];
$user1 = $_POST['user1'];
$user2 = $_POST['user2'];
$turn = $_POST['turn'];
#need to check for own turn

#$gameID = 1;
#$user1 = "test";
#$user2 = "edwin";
#$turn = 0;


shell_exec("php /var/www/html/scrabble/inGameTimer/timerFunction.php $gameID $user1 $user2 $turn > /dev/null 2>/dev/null &");
print("{}");


?>