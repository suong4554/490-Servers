<?php
session_start();
$user = $_POST['user'];
$gameId = 0;

$_SESSION['user'] = $user;
$_SESSION['gameId'] = $gameId;
	
	
?>
<!DOCTYPE html>
<html lang="en">
	<META HTTP-EQUIV=Refresh; 
	<head>
	<link rel="stylesheet" href="Styles.css">
		<title>Chat Application Home</title>
		
	
	</head>
	<body>
	<h2>Welcome <span style="color:green"><?php
echo $_SESSION['user'];
	?></span></h2>
	
	
		
		<div id="ChatMessages">
		</div>
	<div id="ChatBig"> 
		<span style="color:green">Chat</span><br/>
		<textarea id="ChatText" name="ChatText"></textarea>
	</div>
	
	<script src="jquery.js"></script>	
	<script src="chatbox.js"></script>		
	</body>
</html>


		
	
	
	

