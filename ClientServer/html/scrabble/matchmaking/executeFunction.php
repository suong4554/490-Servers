<?php

include("account.php");
include("scrabble/matchmaking/Function.php");


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

$functionName = $_POST['fName'];

if($functionName == "initiateSearch"){
	$user = $_POST['user1'];
	initiateSearch($user);
}
elseif($functionName == "findMatch"){
	findMatch();
}
elseif($functionName == "getOtherUser"){
	$user = $_POST['user1'];
	$temp = getOtherUser($user);
	#temp should be equal to username of other player looking for match
	print($temp);
}
elseif($functionName == "initiateMatch"){
	$user1 = $_POST["user1"];
	$user2 = $_POST["user2"];
	initiateMatch($user1, $user2);
}
elseif($functionName == "switchTurn"){
	#user1 will switch from his turn to next player's turn
	$user1 = $_POST["user1"];
	$user2 = $_POST["user2"];
	switchTurn($user1, $user2);
}
elseif($functionName == "updateMatch"){
	#Should be executed at the end of every round/turn
	$user1 = $_POST["user1"];
	$user2 = $_POST["user2"];
	updateMatch($user1, $user2);
}
elseif($functionName == "endMatch"){
	$user1 = $_POST["user1"];
	$user2 = $_POST["user2"];
	endMatch($user1, $user2);
}
elseif($functionName == "cancelSearch"){
	$user = $_POST["user1"];
	cancelSearch($user);
}
else{
	print("Function not Found")
}





?>