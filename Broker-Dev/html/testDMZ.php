<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
	$client = new rabbitMQClient("DMZ.ini", "DMZ");
	$words = json_encode(["hello"]);
	$msg = "this sucks";
	$request = array();
	$request['type'] = "checkWords";
	$request['words'] = $words;
	$request['message'] = $msg;
	$response = $client->send_request($request);
	$response = $response["result"];
    return $response;
	
	
?>