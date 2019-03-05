<?php
error_reporting(E_ERROR);
ini_set('display_errors',1);
//session_set_cookie_params(0,"/index.php")
session_set_cookie_params(0,"127.0.0.1/");
session_start();


$servername = "localhost";
$username = "edwinzhou";
$password = "NJITIT490";
$project = "testDB";

$conn = new mysqli($servername,$username,$password);

if($conn->connect_error)
{
	die("Connection failed: " . $conn->connect_error);

}
//echo "Connected successfully to database, authenticating...<br>";

mysqli_select_db ($conn,$project);

$user = $_GET["user"];
$password = $_GET["password"];
$_SESSION["user"] = $user;
$_SESSION["password"] = $password;
$_SESSION["logged"] = false;
//echo "username:" . $_SESSION["user"];
/*function auth ($user,$password,&$t)
{
	global $conn;
	$s = "select * from testTable where username = '$user' and password = '$password'";
	$t = mysqli_query($conn,$s) or die (mysql_error());
	$num = mysqli_num_rows($t);
	if ($num > 0 ) {return true;} else {return false;};
}
if (! auth($user,$password, $t) )
{
	echo "Please login correctly.";
	//redirect ($message, "login.html",$delay);
}
 */
?>
