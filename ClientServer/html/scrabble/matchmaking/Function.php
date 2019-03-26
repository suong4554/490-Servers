<?php


function initiateSearch($user) { 
  global $db;
  #Looking is set to 1 when looking and 0 when match is in progress
  $s = "insert into matches (Username, Looking, currentTurn) values ('$user',1,1)";
  $t = mysqli_query ( $db , $s );
;}

//if findMatch returns false that means that the user is the only one looking in the queue and therefore he should have control
//those who find a match that return true on the first run means that they should give up control to the 1st who joined
function findMatch(){
	//sleeps for 1 seconds so that the webpage doesnt kill the main browser
	//usleep(1000000);
	global $db;
	$t = false;
	$s = "SELECT * FROM matches where Looking = 1";
	$t = mysqli_query ( $db , $s );
	if($t == false){
		$t = false;
	}
	else{
		$num =  mysqli_num_rows($t); 
		if ( $num > 1 ) {
			$t = true  ;} 
		else {   
			$t = false  ;}
	}
	return $t;
}

#use user instead of game id since initial log in will kick you out when gameID is set to null
function checkGameState($user){
	global $db;
	$s = "SELECT * FROM matches where Username = '$user'";
	$t = mysqli_query($db, $s);
	$num =  mysqli_num_rows($t); 
	if ( $num > 0 ) {
		$t = true  ;} 
	else {   
		$t = false  ;}
	return $t;
}


function getOtherUser($user){
	global $db;
	$s = "SELECT Username FROM matches where Looking = 1 AND Username != '$user'";
	$t = mysqli_query ( $db , $s );
	$row = mysqli_fetch_array($t);
	$temp = $row['Username'];
	return $temp;
	
}


function getOtherUserinGame($user){
	global $db;
	$s = "SELECT Username FROM matches where Username != '$user'";
	$t = mysqli_query ( $db , $s );
	$row = mysqli_fetch_array($t);
	$temp = $row['Username'];
	return $temp;
}

//When user connects in second window it helps to prevent an infinite loop where users have a state change before they can detect
function getLooking($user){
	global $db;
	$s = "SELECT Looking FROM matches where Username = '$user'";
	$t = mysqli_query ( $db , $s );
	$row = mysqli_fetch_array($t);
	$temp = $row['Looking'];
	return $temp;
}



function initiateMatch($user1, $user2){
	//get from post
	global $db;
	$s = "SELECT MAX(Matchid) AS mostRecentMatch FROM matches";
	$t = mysqli_query ( $db , $s );
	$row = mysqli_fetch_array($t);
	$maxID = $row['mostRecentMatch'];
	$ID = $maxID + 1;
	
	#turn priority will be given to whoever has currentTurn of 0
	$s = "UPDATE matches SET turn = 0, Matchid = $ID, currentTurn = 0, Looking = 0, score = 0 WHERE Username = '$user1' AND Looking = 1";
	$t = mysqli_query ( $db , $s );
	$s = "UPDATE matches SET turn = 0, Matchid = $ID, currentTurn = 1, Looking = 0, score = 0 WHERE Username = '$user2' AND Looking = 1";
	$t = mysqli_query ( $db , $s );
	
}

function discoverPriority($user){
	global $db;
	//usleep(800000);
	$s = "SELECT currentTurn FROM matches WHERE Username = '$user'";
	$t = mysqli_query($db, $s);
	$row = mysqli_fetch_array($t);
	$temp = $row['currentTurn'];
	return ($temp);
	
}


function getUserScore($user){
	global $db;
	$s = "SELECT score FROM matches WHERE Username = '$user'";
	$t = mysqli_query($db, $s);
	$row = mysqli_fetch_array($t);
	$temp = $row["score"];
	return $temp;
	
}


function updateUserScore($user, $score){
	global $db;
	$s = "UPDATE matches SET score = $score WHERE Username = '$user'";
	$t = mysqli_query($db, $s);
}


function findInfo($user, $information){
	global $db;
	$s = "SELECT $information FROM matches WHERE Username = '$user'";
	$t = mysqli_query($db, $s);
	$row = mysqli_fetch_array($t);
	$temp = $row[$information];
	return	$temp;
	
}


function switchTurn($user1, $user2){
	global $db;
	#user1 should be user with the current turn priority, user 2 will be user that will be given priority
	$s = "UPDATE matches SET currentTurn = 1 WHERE Username = '$user1'";
	$t = mysqli_query( $db , $s );
	$s = "UPDATE matches SET currentTurn = 0 WHERE Username = '$user2'";
	$t = mysqli_query ( $db , $s );
}


function updateMatch($user1, $turn){
	global $db;
	$s = "UPDATE matches SET turn = $turn WHERE Username = '$user1' OR Username = '$user2'";
	$t = mysqli_query ( $db , $s );
	
	
}

function endMatch($user1, $user2){
	global $db;
	
	$s = "DELETE FROM matches WHERE Username = '$user1'";
	$t = mysqli_query ( $db , $s );
	$s = "DELETE FROM matches WHERE Username = '$user2'";
	$t = mysqli_query ( $db , $s );
	
	$s = "DELETE FROM chats WHERE ChatUsername = '$user1' OR ChatUsername = '$user2'";
	$t = mysqli_query ( $db , $s );
}

function updateScore($user1, $score){
	global $db; 
	$s = "SELECT score FROM matches WHERE Username = '$user1'";
	$t = mysqli_query( $db , $s );
	$row = mysqli_fetch_array($t);
	$scoreAppend = $row['score'] + $score;
	$s = "UPDATE matches SET score = $scoreAppend WHERE Username = '$user1'";
	$t = mysqli_query( $db , $s );
}


function cancelSearch($user1){
	global $db;
	$s = "DELETE FROM matches WHERE Username = '$user1'";
	$t = mysqli_query ( $db , $s );
}





function redirect ($message, $url, $delay){
	echo $message;
	header("refresh: $delay; url = '$url'");
	exit();
}

?>