#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

include('account.php');
include('Function.php');



function doLogin($username,$password)
{
	#$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");
	$result = auth($username, $password, $result);
	#$request = array();
	#$request['type'] = "LoginVerification";
	#$request['success'] = $result;
	#This is only needed if it does not return a true or false
	#$response = $client->send_request($request);
    	return $result;
    //return false if not valid
}

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "Login":
	    return $result = doLogin($request['username'],$request['password']);
    case "LoginVerification":
	    return $request['success'];
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed", 'result' => $result);
}

$server = new rabbitMQServer("MySQLRabbit.ini","MySQLRabbit");

echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>

