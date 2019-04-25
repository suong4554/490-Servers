<?php

include("../../account.php");
include("Function.php");
session_start();

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
	$temp = findMatch();
	print(json_encode($temp));
	#it prints out 1 if there's not matches available and 2 if there are
}
elseif($functionName == "getLooking"){
	$user = $_POST['user1'];
	$temp = getLooking($user);
	print(json_encode(!$temp));
}
elseif($functionName == "checkGameState"){
	$user = $_POST["user1"];
	$temp = checkGameState($user);
	print(json_encode($temp));
}
elseif($functionName == "getOtherUser"){
	$user = $_POST['user1'];
	$temp = getOtherUser($user);
	#temp should be equal to username of other player looking for match
	print($temp);
}
elseif($functionName == "getOtherUserinGame"){
	$user = $_POST['user1'];
	$temp = getOtherUserinGame($user);
	#temp should be equal to username of other player looking for match
	print($temp);
}
elseif($functionName == "initiateMatch"){
	$user1 = $_POST["user1"];
	$user2 = $_POST["user2"];
	initiateMatch($user1, $user2);
}
elseif($functionName == "discoverPriority"){
	$user1 = $_POST["user1"];
	$temp = discoverPriority($user1);
	print(json_encode(!$temp));
}
elseif($functionName == "getUserScore"){
	$user1 = $_POST["user1"];
	$temp = getUserScore($user1);
	print ($temp);
}
elseif($functionName == "updateUserScore"){
	$user1 = $_POST["user1"];
	$score = $_POST["score1"];
	updateUserScore($user1, $score);
}
elseif($functionName == "switchTurn"){
	#user1 will switch from his turn to next player's turn
	$user1 = $_POST["user1"];
	$user2 = $_POST["user2"];
	switchTurn($user1, $user2);
}
elseif($functionName == "updateMatch"){
	#Should be executed at the end of every round/turn
	#updates turn per each user, not an issue since user's wont be able to end their turn again until other player end's their turn
	$user1 = $_POST["user1"];
	$user2 = $_POST["turn"];
	updateMatch($user1, $turn);
}
elseif($functionName == "endMatch"){
	$user1 = $_POST["user1"];
	$user2 = $_POST["user2"];
	$gameID = $_POST["gameID"];
	endMatch($user1, $user2, $gameID);
}
#created for timer
elseif($functionName == "getTime"){
	$gameID = $_POST["gameID"];
	$temp = getTime($gameID);
	print($temp);
}
elseif($functionName == "cancelSearch"){
	$user = $_POST["user1"];
	cancelSearch($user);
}
elseif($functionName == "resetTime"){
	$gameID = $_POST["gameID"];
	resetTime($gameID);
	#print("reset time");
}
elseif($functionName == "findInfo"){
	$user = $_POST["user"];
	$information = $_POST["info"];
	$temp = findInfo($user, $information);
	print($temp);
	
}
else{
	print("Function not Found");
}





?>