#!/usr/bin/php
<?php

#Call this script with php request.php command package
$logVersion = "logs/versions.txt";

#returns as array instead of displaying
$commands = print_r($argv, true);
$command = $argv[1];

######COMMANDS#########
#getDev
#toDev
#getQA
#toQA
#getProd
#toProd


#####RabbitMQ##########
#require_once('path.inc');
#require_once('get_host_info.inc');
#require_once('rabbitMQLib.inc');


if(!file_exists($logVersion)){
	$myfile = fopen($logVersion, "w");
	$temp = "0\n";
	fwrite($myfile, $temp);
	fclose($myfile);
}

function checkVersion($logVersion){
	$currentVersion = 0;
	if(file_exists($logVersion)){
		$myfile = fopen($logVersion, "r");
		$temp = fread($myfile, filesize($logVersion));
		fclose($myfile);
		
		
		$temp = explode("\n", $temp);
		$recentVersion = $temp[count($temp) -3];
		$recentVersion = intval($recentVersion);
		$currentVersion = $recentVersion +=1;
	}
	return $currentVersion;
}



if(isset($argv[2])){
	$version = $argv[2];
}
else{
	$version = "current";
}




if($command == "getDev"){
	$client = new rabbitMQClient('Dev.ini', 'MySQLRabbit');
	$msg = "I want all of you";
	$request = array();
	$request['type'] = "retrieve";
	$request['version'] = $version;
	$request['message'] = $msg;
	#when dev server receives this it will push files to /transfer/connect
}
elseif($command == "toDev"){
	$client = new rabbitMQClient('Dev.ini', 'MySQLRabbit');
	$msg = "I want to insert myself into you";
	$request = array();
	$request['type'] = "push";
	$request['version'] = $version;
	$request['message'] = $msg;
	#when dev broker retrieves this it will connect to service server
	#will then transfer files to itself and extract files
}

#Do this later. it doesn't matter as much
elseif($command == "getQA"){
	#$client = new rabbitMQClient('QA.ini', 'MySQLRabbit');
	$msg = "I want all of you";
	$request = array();
	$request['type'] = "retrieve";
	$request['version'] = $version;
	$request['message'] = $msg;
}
elseif($command == "getProd"){
	
}


?>