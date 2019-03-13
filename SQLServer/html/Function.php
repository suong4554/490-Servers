<?php
function auth ($user, $pass, &$t) { 
  global $db;
  #$pass = sha1($pass);
  $s = "select * from testTable where Username = '$user' and Password = '$pass'" ;
  //echo "<br> $s <br> <br>";
  $t = mysqli_query ($db, $s );
  $num =  mysqli_num_rows($t); 
  if ( $num > 0 ) {
	  $t = true  ;} 
	else {   
	$t = false  ;}
;}

function isActive($user, &$active){
	global $db;
	 $s = "select * from testTable where Username = '$user'";
	 $t = mysqli_query ($db, $s);
	 $r = mysqli_fetch_array($t, MYSQLI_ASSOC);
	 $active = $r["Active"];
}

function getdata($user, &$result){
  global $db;
  global $bad;
   if (!isset($_GET[$user]))  {
	  $bad = true; 
	  echo " $user  is undefined data <br> "; 
	  return;	}
  if (($_GET[$user]) == "")  {
	  $bad = true; 
	  echo"$user empty data <br> "; 
	  return ; }
  else {
   echo "$_GET[$user] <br>";}
  $result = mysqli_real_escape_string ($db, $_GET [ $user ])  
 //$result = $_GET [ $user ];
;}
 	
function recordGame ($user1, $user2, $winner, $score1, $score2, $turns) {
	global $db;
	$s = "insert into playerHistory (playerOneUser, playerTwoUser, winner, playerOneScore, playerTwoScore, turnsUsed, gameDate) values ('$user1', '$user2' , '$winner' , $score1, $score2, $turns, NOW())";
	$t = mysqli_query($db, $s) or die (mysqli_error($db)); 
	//I'm assuming it returns true
	return $t
	}

	
	
	
	
function show($user, &$out){
	global $db;
	
	$s="select*from A where user = '$user' ";  
	$out .= "<br>SQL statement is: $s<br>";
	$t = mysqli_query($db, $s) or die (mysqli_error($db));
	while (   $r = mysqli_fetch_array($t, MYSQLI_ASSOC) ){	
	  $user = $r["user"];
	  $balance  = $r["cur_balance"];
     $out .= "<br> user is: $user <br>  current_balance is: $$balance <br><br>";
     }
	 
	$s="select*from T where user = '$user' order by date desc";  
	$t = mysqli_query($db, $s) or die (mysqli_error($db));
	while (   $r = mysqli_fetch_array($t, MYSQLI_ASSOC) ){	
	  $date = $r["date"];
	  $amount  = $r["amount"];
    $type = $r["type"];
	if ($type == "W"){
		$type = "Withdraw";
	}
	if ($type == "D"){
		$type = "Deposit";
	}
     $out .= 	
	 '<div class="col-md-8 col-sm-12 panel panel-default">' .
		'<div class="panel-body" style="padding: 8px">' .
			 '<h3>' . $type . '</h3>' .
				'<div class="form-group" >'.
					'<h4>' . $date . '</h4>'.
					'<h5>' . '$'.$amount . '</h5>'.
				 '</div>'.
		'</div>'.
	'</div>';
  }
  echo $out;
}

function mailer ($user, $out){
  global $db;
  echo "<br>Executing mailer<br>";
  $s = "select mail from A where user = '$user'";
  $t = mysqli_query ($db, $s) or die (mysqli_error($db));
  $r = mysqli_fetch_array($t, MYSQLI_ASSOC);
  $mailaddress = $r["mail"];
  print "'$mailaddress'";
  $to      = $mailaddress;
  $subject = "Transaction Statement ". date("Y/m/d"). "  ". date("h:i:sa");
  show($user, $out);
  $message = $out;
  mail($to, $subject, $message);
}


function redirect ($message, $url, $delay){
	echo $message;
	header("refresh: $delay; url = '$url'");
	exit();
}

function register ($user, $pass, $pass2, $email){
	global $db;
	
	$invalid = False;
	//Validate Email
	if (!(filter_var($email, FILTER_VALIDATE_EMAIL))) {
		echo "Invalid email<br>";
		$invalid=True;
		return $invalid;
	}
	
	if($pass != $pass2){
		echo "Your passwords do not match<br>";
		$invalid=True;
		return $invalid;
	}
	
	//Check if Username is in database
	$s = "select * from testTable where username = '$user'" ;
	$t = mysqli_query ( $db , $s );
 	$num =  mysqli_num_rows($t); 
	if ( $num > 0 ) {
		echo "Username is already taken<br>";
		$invalid=True;
		return $invalid;
	}
	
	#if($invalid){
	#	redirect("Invalid email, password or username", "registration.html", 3);
	#	exit();
	#}
	
	//Insert user into database
	//$passhash = sha1($pass);
	$insert = "insert into testTable (Username, Password, Email) values ('$user','$pass', '$email')";
	echo "<br> $insert <br> <br>";
	$t = mysqli_query ( $db , $insert );

	return $invalid;	
}



function verification ($user, $pass) {
  global $db;
  echo "<br>Executing mailer<br>";
  $s = "select*from A where user = '$user'";
  $t = mysqli_query ($db, $s) or die (mysqli_error($db));
  $r = mysqli_fetch_array($t, MYSQLI_ASSOC);
  $mailaddress = $r["mail"];
  print "'$mailaddress'";
  $to      = $mailaddress;
  $pass = sha1($pass);
  $subject = "Verification". date("Y/m/d"). "  ". date("h:i:sa");
 	$message = "
 
Thanks for signing up!
Your account has been created, you can login with the following credentials after you have activated your account by clicking the url below.
 
------------------------
Username: '$user'
Password: '$pass'
------------------------
 
Please click this link to activate your account:
https://web.njit.edu/~sfu5/Proto6-Verification.php?user=$user&pass=$pass
 
"; //message above including the link
                     
mail($to, $subject, $message); // Send the email
}

function verify ($user, $pass){
	global $db;
	$s = "update A set Active = 1 where user = '$user' and pass = '$pass'" ;
	$t = mysqli_query ($db, $s) or die (mysqli_error($db));
}


