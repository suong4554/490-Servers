#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

include("wordCheck/wordcheck.php");

$logName = "RabbitReceived_Sent.txt";
function rabbitLog($array, $filename){
	if(file_exists($filename)){
		$myfile = fopen($filename, "a");
		$text = serialize($array);
		$date = date('Y-m-d');
		fwrite($myfile, $date);
		fwrite($myfile, "\n");
		fwrite($myfile, $text);
		fwrite($myfile, "\n");
	}
	else{
		$myfile = fopen($filename, "w");
		$text = serialize($array);
		$date = date('Y-m-d');
		fwrite($myfile, $date);
		fwrite($myfile, "\n");
		fwrite($myfile, $text);
		fwrite($myfile, "\n");
	}
}



function requestProcessor($request){
	echo "received request".PHP_EOL;
	var_dump($request);
	if(!isset($request['type'])){
		return "ERROR: unsupported message type";
	}
	rabbitLog($request, $logName);
	$temp = $request['type'];
	if($temp == 'Login'){
		$result = doLogin($request['username'],$request['password']);
	}
	else if($temp == "checkWords"){
		$result = wordcheck($request["words"]);
		print $result;
	}
	rabbitLog(array("returnCode" => '0', 'message'=>'Server acknowledged', 'result' => $result), $logName);
	return array("returnCode" => '0', 'message'=>'Server acknowledged', 'result' => $result);
}

$server = new rabbitMQServer("DMZ.ini","DMZ");


echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>

