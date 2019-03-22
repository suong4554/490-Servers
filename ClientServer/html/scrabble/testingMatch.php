<?php
error_reporting(E_ALL);
ini_set('display_errors',on);
include("../account.php");
include("matchmaking/Function.php");




$db = mysqli_connect($servername, $username, $password , $project);
if (mysqli_connect_errno())
  {
	  $message = "Failed to connect to MySQL: " . mysqli_connect_error();
	  echo $message;
	  error_log($message);
	  exit();
  }

mysqli_select_db( $db, $project );

print("hello");
$temp = getOtherUserinGame('Bill');
print($temp);


?>