#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

include("wordCheck/wordcheck.php");

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }

  $temp = $request['type'];
  if($temp == 'Login'){
	$result = doLogin($request['username'],$request['password']);
	  #return $result;
	# echo "hello"; 
   }
  else if($temp == "checkWords"){
	$result = wordcheck($request["words"]);
	print $result;
  }
  
  return array("returnCode" => '0', 'message'=>'Server acknowledged', 'result' => $result);

}

$server = new rabbitMQServer("DMZ.ini","DMZ");


echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>

