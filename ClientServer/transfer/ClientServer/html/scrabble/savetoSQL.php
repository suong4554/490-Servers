<?php


$playerOne = $_POST['user1'];
$playerTwo = $_POST['user2'];
$winner = $_POST['winner'];
$playerOneScore = $_POST['score1'];
$playerTwoScore = $_POST['score2'];
$turns = $_POST['turns'];


#deletes file for user 1
$filename = $_POST['filename1'];
if(file_exists($filename)){
    unlink($filename);
}


#deletes file for user 2
$filename = $_POST['filename2'];
if(file_exists($filename)){
    unlink($filename);
}
######################################3
########### Rabbit MQ #################
########################################

require_once('../path.inc');
require_once('../get_host_info.inc');
require_once('../rabbitMQLib.inc');

$client = new rabbitMQClient('../MySQLRabbit.ini', 'MySQLRabbit');

$msg = "Sending login request";

$request = array();
$request['type'] = "recordGame";
$request['user1'] = $playerOne;
$request['user2'] = $playerTwo;
$request['winner'] = $winner;
$request['score1'] = $playerOneScore;
$request["score2"] = $playerTwoScore;
$request["turns"] = $turns;


#testing set response to false or true
$response = $client->send_request($request);
if($response){
	print("{}");
}
else{
	print("Failed to update sql");
}


?>
