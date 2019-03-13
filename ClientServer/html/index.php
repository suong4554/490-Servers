<?php
date_default_timezone_set("America/New_York");
session_set_cookie_params(0, "/var/www/html", "localhost");
session_start();
include("Function.php");


if(!isset($_SESSION["login"])){
   $_SESSION["login"] = False;
}

elseif($_SESSION["login"] == True){
    redirect("", "home.php", "0");
}


?>
<html>
<body>

 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<head></head>
<body>
	<br>
	<div class="container">
  <div class="panel-group">
    <div class="panel panel-default">
		<div class="panel-heading" class="col-xs-1" align="center"> <h2>Login</h2> </div>
			<div class="panel-body">
    
 	<!--<form action = "customLogin.php" class = "form-horizontal">
	-->
	<form action = "setLoginCookie.php" class = "form-horizontal" method="post">


	<fieldset id_"fieldset" > <br>
		<div class="form-group">
			<label for="user" class = "col-sm-4 control-label" >User</label>
				<div class="col-sm-5">
					<input type=text class="form-control" name = "user" id="user" placeholder="Enter User" autocomplete = off required>
				</div>
		</div>
		
		<div class="form-group">
			<label for="password" class = "col-sm-4 control-label">Password</label>
				<div class="col-sm-5">
					<input type=text class="form-control" name = "password" id="password" placeholder="Enter Password" autocomplete = off required>
				</div>
		</div>
		
  
		<div class="form-group">
			<div class="col-sm-offset-5 col-sm-5">
				<button type="submit" class="btn btn-default">Sign in</button>
			</div>
			<br>
			<div class="col-sm-5 col-sm-offset-4">
				Don't have an account? <a href = "registration.html">Register Here</a>
			</div>
		</div>
	

</fieldset>

</form>
</div>
</div>


</body> 
</html>
