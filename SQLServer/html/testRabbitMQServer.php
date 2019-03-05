#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doLogin($username,$password)
{
	$client = new rabbitMQClient("MySQLRabbit.ini", "MySQLRabbit");
	$request = array();
	$request['type'] = "Login";
	$request['user'] = $username;
	$request['password'] = $password;
	$response = $client->send_request($request);
    return $response;
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
	   doLogin($request['username'],$request['password']);
	   return $result;
    case "LoginVerification":
	    return $request['success'];
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed", 'result' => $result["result"]);
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>

