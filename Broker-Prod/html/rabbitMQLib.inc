<?php

require_once('get_host_info.inc');
error_reporting(E_ALL);
ini_set('display_errors','On');
class rabbitMQServer
{
	private $machine = "";
	public  $BROKER_HOST;
	private $BROKER_PORT;
	private $USER;
	private $PASSWORD;
	private $VHOST;
	private $exchange;
	private $queue;
	private $routing_key = '*';
	private $exchange_type = "topic";
	private $auto_delete = false;

	function __construct($machine, $server = "rabbitMQ")
	{
		$this->machine = getHostInfo(array($machine));
		$this->BROKER_HOST   = $this->machine[$server]["BROKER_HOST"];
		$this->BROKER_PORT   = $this->machine[$server]["BROKER_PORT"];
		$this->USER     = $this->machine[$server]["USER"];
		$this->PASSWORD = $this->machine[$server]["PASSWORD"];
		$this->VHOST = $this->machine[$server]["VHOST"];
		if (isset( $this->machine[$server]["EXCHANGE_TYPE"]))
		{
			$this->exchange_type = $this->machine[$server]["EXCHANGE_TYPE"];
		}
		if (isset( $this->machine[$server]["AUTO_DELETE"]))
		{
			$this->auto_delete = $this->machine[$server]["AUTO_DELETE"];
		}
		$this->exchange = $this->machine[$server]["EXCHANGE"];
		$this->queue = $this->machine[$server]["QUEUE"];
	}

	function process_message($msg)
	{
		// send the ack to clear the item from the queue
		if ($msg->getRoutingKey() !== "*")
    {
      return;
    }
    $this->conn_queue->ack($msg->getDeliveryTag());
		try
		{
			if ($msg->getReplyTo())
			{
				// message wants a response
				// process request
				$body = $msg->getBody();
				$payload = json_decode($body, true);
				$response;
				if (isset($this->callback))
				{
					$response = call_user_func($this->callback,$payload);
				}

      $params = array();
      $params['host'] = $this->BROKER_HOST;
      $params['port'] = $this->BROKER_PORT;
      $params['login'] = $this->USER;
      $params['password'] = $this->PASSWORD;
      $params['vhost'] = $this->VHOST;
			$conn = new AMQPConnection($params);
			$conn->connect();
			$channel = new AMQPChannel($conn);
			$exchange = new AMQPExchange($channel);
      $exchange->setName($this->exchange);
      $exchange->setType($this->exchange_type);

			$conn_queue = new AMQPQueue($channel);
			$conn_queue->setName($msg->getReplyTo());
			$replykey = $this->routing_key.".response";
			$conn_queue->bind($exchange->getName(),$replykey);
			$exchange->publish(json_encode($response),$replykey,AMQP_NOPARAM,array('correlation_id'=>$msg->getCorrelationId()));

				return;
			}
		}
		catch(Exception $e)
		{
			// ampq throws exception if get fails...
			$message = "error: rabbitMQServer : process_message: exception caught: ";
			echo $message.$e;
			error_log($message);
		}
		// message does not require a response, send ack immediately
		$body = $msg->getBody();
		$payload = json_decode($body, true);
		if (isset($this->callback))
		{
			call_user_func($this->callback,$payload);
		}
		echo "processed one-way message\n";
	}

	function process_requests($callback)
	{
		try
		{
			$this->callback = $callback;
      $params = array();
      $params['host'] = $this->BROKER_HOST;
      $params['port'] = $this->BROKER_PORT;
      $params['login'] = $this->USER;
      $params['password'] = $this->PASSWORD;
      $params['vhost'] = $this->VHOST;
			$conn = new AMQPConnection($params);
			$conn->connect();

			$channel = new AMQPChannel($conn);

			$exchange = new AMQPExchange($channel);
      $exchange->setName($this->exchange);
      $exchange->setType($this->exchange_type);

			$this->conn_queue = new AMQPQueue($channel);
			$this->conn_queue->setName($this->queue);
			$this->conn_queue->bind($exchange->getName(),$this->routing_key);

			$this->conn_queue->consume(array($this,'process_message'));

			// Loop as long as the channel has callbacks registered
			while (count($channel->callbacks))
			{
				$channel->wait();
			}
		}
		catch (Exception $e)
		{
		
			$message = "Failed to start request processor";
			error_log($message);
			trigger_error($message.$e,E_USER_ERROR);
		       	
		}
	}
}

class rabbitMQClient
{
	private $machine = "";
	public  $BROKER_HOST;
	private $BROKER_PORT;
	private $USER;
	private $PASSWORD;
	private $VHOST;
	private $exchange;
	private $queue;
	private $routing_key = '*';
	private $response_queue = array();
	private $exchange_type = "topic";

	function __construct($machine, $server = "rabbitMQ")
	{
		$this->machine = getHostInfo(array($machine));
		$this->BROKER_HOST   = $this->machine[$server]["BROKER_HOST"];
		$this->BROKER_PORT   = $this->machine[$server]["BROKER_PORT"];
		$this->USER     = $this->machine[$server]["USER"];
		$this->PASSWORD = $this->machine[$server]["PASSWORD"];
		$this->VHOST = $this->machine[$server]["VHOST"];
		if (isset( $this->machine[$server]["EXCHANGE_TYPE"]))
		{
			$this->exchange_type = $this->machine[$server]["EXCHANGE_TYPE"];
		}
		if (isset( $this->machine[$server]["AUTO_DELETE"]))
		{
			$this->auto_delete = $this->machine[$server]["AUTO_DELETE"];
		}
		$this->exchange = $this->machine[$server]["EXCHANGE"];
		$this->queue = $this->machine[$server]["QUEUE"];
	}

	function process_response($response)
	{
		$uid = $response->getCorrelationId();
		if (!isset($this->response_queue[$uid]))
		{
		  $message = "unknown uid\n";	
		  echo  $message;
		  return true;
		}
    $this->conn_queue->ack($response->getDeliveryTag());
		$body = $response->getBody();
		$payload = json_decode($body, true);
		if (!(isset($payload)))
		{
			$payload = "[empty response]";
		}
		$this->response_queue[$uid] = $payload;
		return false;
	}

	function send_request($message)
	{
		$uid = uniqid();

		$json_message = json_encode($message);
		try
		{
      $params = array();
      $params['host'] = $this->BROKER_HOST;
      $params['port'] = $this->BROKER_PORT;
      $params['login'] = $this->USER;
      $params['password'] = $this->PASSWORD;
      $params['vhost'] = $this->VHOST;

			$conn = new AMQPConnection($params);
			$conn->connect();

			$channel = new AMQPChannel($conn);

			$exchange = new AMQPExchange($channel);
      $exchange->setName($this->exchange);
      $exchange->setType($this->exchange_type);

      $callback_queue = new AMQPQueue($channel);
      $callback_queue->setName($this->queue."_response");
      $callback_queue->declare();
			$callback_queue->bind($exchange->getName(),$this->routing_key.".response");

			$this->conn_queue = new AMQPQueue($channel);
			$this->conn_queue->setName($this->queue);
			$this->conn_queue->bind($exchange->getName(),$this->routing_key);

			$exchange->publish($json_message,$this->routing_key,AMQP_NOPARAM,array('reply_to'=>$callback_queue->getName(),'correlation_id'=>$uid));
      $this->response_queue[$uid] = "waiting";
			$callback_queue->consume(array($this,'process_response'));

			$response = $this->response_queue[$uid];
			unset($this->response_queue[$uid]);
			return $response;
		}
		catch(Exception $e)
		{
			$message = "Failed to send message to exchange, please check your info: " . $params['host'] . "\n" . $params['port'] . "\n" . $params['login'] . "\n" . $params['password'] . "\n". $params['vhost'];
			error_log($messsage);
			die($message. $e->getMessage()."\n");
		}
	}

	/**
	  @brief send a one-way message to the server.  These are
	  auto-acknowledged and give no response.

	  @param message the body of the request.  This must make sense to the
	  server
	 */
	function publish($message)
	{
		$json_message = json_encode($message);
		try
		{
      $params = array();
      $params['host'] = $this->BROKER_HOST;
      $params['port'] = $this->BROKER_PORT;
      $params['login'] = $this->USER;
      $params['password'] = $this->PASSWORD;
      $params['vhost'] = $this->VHOST;
			$conn = new AMQPConnection($params);
			$conn->connect();
			$channel = new AMQPChannel($conn);
			$exchange = new AMQPExchange($channel);
      $exchange->setName($this->exchange);
      $exchange->setType($this->exchange_type);
			$this->conn_queue = new AMQPQueue($channel);
			$this->conn_queue->setName($this->queue);
			$this->conn_queue->bind($exchange->getName(),$this->routing_key);
			return $exchange->publish($json_message,$this->routing_key);
		}
		catch(Exception $e)
		{$message = "Failed to send message to exchange, please check your info: " . $params['host'] . "\n" . $params['port'] . "\n" . $params['login'] . "\n" . $params['password'] . "\n". $params['vhost'];
                        error_log($messsage);
                        die($message. $e->getMessage()."\n");
			//die("failed to send message to exchange: ". $e->getMessage()."\n");
		}
	}
}
?>

