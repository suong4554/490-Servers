#!/usr/bin/php
<?php
date_default_timezone_set("America/New_York");
$now = date(DATE_RFC2822);
print($now . "\n");
#Call this script with php request.php command package
$logVersion = "/home/transfer/logs/versions.json";

#returns as array instead of displaying
$commands = print_r($argv, true);
if(!isset($argv[1])){
	print("Please enter a command:
	#getDev
	#toDev
	#getQA
	#toQA
	#getProd
	#toProd
	#showVersion
	#deleteVersion
");
$command = "";
}
else{
	$command = $argv[1];
}

######COMMANDS#########
#getDev
#toDev
#getQA
#toQA
#getProd
#toProd
#showVersion
#deleteVersion


#####RabbitMQ##########
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');


if(!file_exists($logVersion)){
	$myfile = fopen($logVersion, "w");
	$temp1 = array();
	$temp = array("version"=>"current", "location"=>"/home/transfer/versions/current.tar.gz", "iteration"=>0);
	array_push($temp1, $temp);
	fwrite($myfile, json_encode($temp1));
	fclose($myfile);
}


function saveVersion($directory, $logVersion){
	#Log Version is the filename
	$myfile = fopen($logVersion, "r");
	$contents = fread($myfile, filesize($logVersion));
	$versionArr = json_decode($contents);
	$mostRecentVer = max(array_column($versionArr, 'iteration'));
	$newVer = (int)$mostRecentVer + 1;
	$newVerStr = "version" . (string)$newVer;
	$directory = $directory . "/" .  $newVerStr . ".tar.gz";
	$tempArr = array("version"=>$newVerStr, "location"=>$directory, "iteration"=>$newVer, "timeStamp"=>$now);
	array_push($versionArr, $tempArr);
	fclose($myfile);
	
	
	$myfile = fopen($logVersion, "w");
	fwrite($myfile, json_encode($versionArr));
	fclose($myfile);
	return($newVerStr);
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


function getVersionLocation($logVersion, $version){
	$myfile = fopen($logVersion, "r");
	$contents = fread($myfile, filesize($logVersion));
	$versionArr = json_decode($contents);
	foreach($versionArr as $versionData){
		$versionData=(array)$versionData;
		if($versionData["version"] == $version){
			return($versionData["location"]);
		}
	}
	#defaults to current if version is not found
	echo ($versionArr[0]["location"]);
	return($versionArr[0]["location"]);
}


function showVersion($logVersion){
	$myfile = fopen($logVersion, "r");
	$contents = fread($myfile, filesize($logVersion));
	fclose($myfile);
	$versionArr = json_decode($contents);
	foreach($versionArr as $versionData){
		$versionData=(array)$versionData;
		foreach($versionData as $key => $data){
			print("$key: $data \n");
		}
		print("\n");

	}
}
#code a delete function

function deleteVersion($logVersion, $version){
	$myfile = fopen($logVersion, "r");
	$contents = fread($myfile, filesize($logVersion));
	fclose($myfile);
	$versionArr = json_decode($contents);
	$tempArr = array();
	
	foreach($versionArr as $versionData){
		$versionData = (array)$versionData;
		if($versionData["version"] != $version){
			array_push($tempArr, $versionData);
		}
		else{
			unlink($versionData["location"]) or die ("\n file does not exist \n");
		}
	}
	$myfile = fopen($logVersion, "w");
	fwrite($myfile, json_encode($tempArr));
	fclose($myfile);
	print("$version has been deleted \n");
	print("Tar of $version has been deleted \n");
	
}


if(isset($argv[2])){
	$version = $argv[2];
}
else{
	$version = "current";
}


$versionDirectory = "/home/transfer/versions";
$uploadDirectory = "/home/transfer/packages";
if($command == "getDev"){
	$client = new rabbitMQClient('Dev.ini', 'MySQLRabbit');
	$msg = "I want all of you";
	$request = array();
	$request['type'] = "toControl";
	$request['version'] = $version;
	$request['message'] = $msg;
	
	#sends request
	$response = $client->send_request($request);
	print("Pull Finished");
	rename($uploadDirectory . "/servers.tar.gz", $versionDirectory . "/current.tar.gz");
	$temp = saveVersion($versionDirectory, $logVersion);
	copy($versionDirectory . "/current.tar.gz", $versionDirectory . "/" . $temp . ".tar.gz");
	print("saved as" . $temp);
	#when dev server receives this it will push files to /transfer/connect
}
elseif($command == "toDev"){
	$client = new rabbitMQClient('Dev.ini', 'MySQLRabbit');
	$msg = "I want to insert myself into you";
	$location = getVersionLocation($logVersion, $version);
	$request = array();
	$request['type'] = "fromControl";
	$request['versionFile'] = $version . ".tar.gz";
	$request['location'] = $location;
	$request['message'] = $msg;
	
	#sends request
	$response = $client->send_request($request);
	print("Dev has successfully rolled back to " . $version);
	#when dev broker retrieves this it will connect to service server
	#will then transfer files to itself and extract files
}

#Do this later. it doesn't matter as much
elseif($command == "getQA"){
	$client = new rabbitMQClient('QA.ini', 'MySQLRabbit');
	$msg = "I want all of you";
	$request = array();
	$request['type'] = "toControl";
	$request['version'] = $version;
	$request['message'] = $msg;
	
	#sends request
	$response = $client->send_request($request);
	print("Pull Finished");
	rename($uploadDirectory . "/servers.tar.gz", $versionDirectory . "/current.tar.gz");
	$temp = saveVersion($versionDirectory, $logVersion);
	copy($versionDirectory . "/current.tar.gz", $versionDirectory . "/" . $temp . ".tar.gz");
	print("saved as" . $temp);
	#when dev server receives this it will push files to /transfer/connect
}
elseif($command == "toQA"){
	$client = new rabbitMQClient('QA.ini', 'MySQLRabbit');
	$msg = "I want to insert myself into you";
	$location = getVersionLocation($logVersion, $version);
	$request = array();
	$request['type'] = "fromControl";
	$request['versionFile'] = $version . ".tar.gz";
	$request['location'] = $location;
	$request['message'] = $msg;
	
	#sends request
	$response = $client->send_request($request);
	print("Dev has successfully rolled back to " . $version);
	#when dev broker retrieves this it will connect to service server
	#will then transfer files to itself and extract files
}
elseif($command == "getProd"){
	$client = new rabbitMQClient('Prod.ini', 'MySQLRabbit');
	$msg = "I want all of you";
	$request = array();
	$request['type'] = "toControl";
	$request['version'] = $version;
	$request['message'] = $msg;
	
	#sends request
	$response = $client->send_request($request);
	print("Pull Finished");
	rename($uploadDirectory . "/servers.tar.gz", $versionDirectory . "/current.tar.gz");
	$temp = saveVersion($versionDirectory, $logVersion);
	copy($versionDirectory . "/current.tar.gz", $versionDirectory . "/" . $temp . ".tar.gz");
	print("saved as" . $temp);
	#when dev server receives this it will push files to /transfer/connect
}
elseif($command == "toProd"){
	$client = new rabbitMQClient('Prod.ini', 'MySQLRabbit');
	$msg = "I want to insert myself into you";
	$location = getVersionLocation($logVersion, $version);
	$request = array();
	$request['type'] = "fromControl";
	$request['versionFile'] = $version . ".tar.gz";
	$request['location'] = $location;
	$request['message'] = $msg;
	
	#sends request
	$response = $client->send_request($request);
	print("Dev has successfully rolled back to " . $version);
	#when dev broker retrieves this it will connect to service server
	#will then transfer files to itself and extract files
}
elseif($command == "showVersion"){
	showVersion($logVersion);
}
elseif($command == "deleteVersion"){
	if($version == "current"){
		print("\n please enter a version other than 'current' \n");
	}
	else{
		deleteVersion($logVersion, $version);
	}
}

?>