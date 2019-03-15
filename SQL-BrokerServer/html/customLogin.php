<?php
print "Hello <br>"	;
date_default_timezone_set("America/New_York");
session_set_cookie_params(0, "/~sfu5/", "web.njit.edu");

session_start();
$_SESSION["delay"] = 3; //makes default 3 seconds

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors' , 1);

include ("account.php");
include ("Function.php");
$db = mysqli_connect($hostname, $username, $password , $project);
if (mysqli_connect_errno())
  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  exit();
  }
print "<br>Successfully connected to MySQL.<br>";

mysqli_select_db( $db, $project ); 
$bad = false;


echo "<br> User: "; getdata("user", $user);
echo "<br> Pass: "; getdata("password", $pass);
#echo "<br> Delay "; getdata("delay", $delay);
auth ($user, $pass, $t);

if(!($t)){
	unset($_SESSION["logged"]);
}
#$_SESSION["delay"] = $delay;
if($t){
	$_SESSION["logged"]= true;
}

echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';
gatekeeper();

if (isset($_SESSION["logged"])){
			$message = "<p class = 'green'> credentials have been verified, you will be redirected in a few seconds </p>";
			echo $message;
				#redirect($message, "Proto6-Action.php", $delay);
}		


?>
