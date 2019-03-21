<?php


function lookForMatch($user) { 
  global $db;
  #$pass = sha1($pass);
  $s = "insert into matches (Username, Looking) values ('$user',1)";;
  //echo "<br> $s <br> <br>";
  $t = mysqli_query ( $db , $s );
;}

function matchMake(){
	global $db;
	$s = "SELECT FROM matches where Looking = 1";
	$t = mysqli_query ( $db , $s );
	$num =  mysqli_num_rows($t); 

	if ( $num > 1 ) {
		$t = true  ;} 
	else {   
		$t = false  ;}
		
	return $t;
	 
}


function initiateMatch($user1, $user2){
	//get from post
	global $db;
	$s = "SELECT MAX(Matchid) AS mostRecentMatch FROM matches";
	$t = mysqli_query ( $db , $s );
	$row = mysql_fetch_array($t);
	$maxID = $row['mostRecentMatch'];
	$ID = $maxID + 1;
	$user1 = "matchID"
	
	#turn priority will be given to whoever has currentTurn of 0
	$s = "UPDATE matches SET turn, 0, Matchid = $ID, currentTurn = 0  WHERE Username = '$user1'";
	$t = mysqli_query ( $db , $s );
	$s = "UPDATE matches SET turn, 0, Matchid = $ID, currentTurn = 1 WHERE Username = '$user2'";
	$t = mysqli_query ( $db , $s );
	
}

function switchTurn($user1, $user2){
	global $db;
	#user1 should be user with the current turn priority, user 2 will be user that will be given priority
	$s = "UPDATE matches SET currentTurn = 1 WHERE Username = '$user1'";
	$t = mysqli_query( $db , $s );
	$s = "UPDATE matches SET currentTurn = 0 WHERE Username = '$user2'";
	$t = mysqli_query ( $db , $s );
}


function updateMatch($user1, $user2){
	global $db;
	$s = "SELECT turn AS turn WHERE Username = '$user1' FROM matches";
	$t = mysqli_query( $db , $s );
	$row = mysql_fetch_array($t);
	$turn = $row['turn'];
	$turn = $turn +=1;
	$s = "UPDATE matches SET turn = $turn WHERE Username = '$user1' OR Username = '$user2'"
	$t = mysqli_query ( $db , $s );
	
	
}

function endMatch($user1, $user2){
	global $db;
	
	$s = "DELETE FROM matches WHERE Username = '$user1'";
	$t = mysqli_query ( $db , $s );
	$s = "DELETE FROM matches WHERE Username = '$user2'";
	$t = mysqli_query ( $db , $s );
	
}










function redirect ($message, $url, $delay){
	echo $message;
	header("refresh: $delay; url = '$url'");
	exit();
}

?>