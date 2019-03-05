#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

include('account.php');
include('Function.php');

$db = mysqli_connect($servername, $username, $password , $project);
if (mysqli_connect_errno())
  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  exit();
  }

mysqli_select_db( $db, $project ); 


function doLogin($username,$password)
{
#	$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");
	auth($username, $password, $result);
	#$request = array();
	#$request['type'] = "LoginVerification";
	#$request['success'] = $result;
	#This is only needed if it does not return a true or false
	#$response = $client->send_request($request);
	#$response = true;
	#return $response;
	print($result);
	#return array("returnCode" => '0', 'message'=> $result, 'result' => $result);
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
  /*switch ($request['type'])
  {
    case "Login":{
	    $result = doLogin($request['user'],$request['password']);
	    print($result);
	    return $result;
    }
    case "validate_session":
	    return doValidate($request['sessionId']);
	    
  }*/
  $result = "";
  $temp = $request['type'];
  if($request['type'] == 'Login')
  {
   $result = doLogin($request['user'],$request['password']);
    print($result);
  }

  else if ($temp == 'Registration')
  {
	  $result = register($request['user'],$request['password'],$request['password2'],$request['email']);
	  print($result);
  }
  
  return array("returnCode" => '0', 'message'=> "Server received request and processed", 'result' => $result);
  
}

$server = new rabbitMQServer("MySQLRabbit.ini","MySQLRabbit");

echo "MySQLRabbitServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "MySQLRabbitServer END".PHP_EOL;
exit();
?>

