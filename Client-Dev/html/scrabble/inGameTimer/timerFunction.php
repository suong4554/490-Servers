<?php


#$gameID = $_POST['gameID'];
#$user1 = $_POST['user1'];
#$user2 = $_POST['user2'];

$gameID = $argv[1];
$user1 = $argv[2];
$user2 = $argv[3];
$turn = $argv[4];
include("../../account.php");
include("../matchmaking/Function.php");
session_start();

error_reporting(E_ALL);
#ini_set('display_errors',on);

$db = mysqli_connect($servername, $username, $password , $project);
if (mysqli_connect_errno())
  {
	  $message = "Failed to connect to MySQL: " . mysqli_connect_error();
	  echo $message;
	  error_log($message);
	  exit();
  }

mysqli_select_db( $db, $project );
#returns the ajax call
print("{}");

#Create sql table with "time" and "gameID" and "marker"

$time = 120;

#create the instance on the initiate match/matchmaking page
#different script for that

$s = "SELECT marker, user, currentTurn FROM timer WHERE gameID = '$gameID'";
$t = mysqli_query ( $db , $s );
$t = mysqli_query($db, $s);
$row = mysqli_fetch_array($t);
$marker = $row["marker"];


if($marker == 0){
	global $db;

	$s = "UPDATE timer SET marker = '1', currentTurn = $turn, user = '$user1' WHERE gameID = $gameID AND marker = '0'";
	$t = mysqli_query ( $db , $s );
	print_r($t);
	for($i = $time; $i >= 0; $i --){
		#print_r($i);
		$s = "UPDATE timer SET time = $i WHERE gameID = $gameID AND marker = '1' AND currentTurn = $turn AND user = '$user1'";
		$t = mysqli_query ( $db , $s );
		#print_r($t);
		sleep(1);
		if($i == 0){
			#Switches to user2 turn
			$s = "UPDATE timer SET marker = '0' WHERE gameID = $gameID AND marker = '1' AND currentTurn = $turn AND user = '$user1'";
			$t = mysqli_query ( $db , $s );
			#switchTurn($user1, $user2);
			#should write an if statement in code instead where if time =0 then it executes pass turn function
		}
		
	}
}








?>