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
	$request['message'] = "hello";
	$response = $client->send_request($request);
#	$response = true;
    return $response;
    //return false if not valid
}

function doRegistration($username, $password, $password2, $email){
        $client = new rabbitMQClient("MySQLRabbit.ini", "MySQLRabbit");
        $request = array();
        $request['type'] = "Registration";
        $request['user'] = $username;
	$request['password'] = $password;
	$request['password2'] = $password2;
        $request['email'] = $email;
        $response = $client->send_request($request);

    return $response;


}

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
  elseif($temp == "Registration"){
	$result = doRegistration($request['user'], $request['password'], $request['password2'], $request['email']);
  }
  
  #print($result["message"]);
  return array("returnCode" => '0', 'message'=>'Server acknowledged', 'result' => $result['result']);
  #return array("returnCode" => '0', 'message'=>"hi");
  
  #print($result["message"]);
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>

