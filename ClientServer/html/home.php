<?php


session_start();

include ("Function.php");

print($_SESSION["login"]);

if((!isset($_SESSION["login"])) or (!$_SESSION["login"])){
	redirect("", "index.php", 0);
	exit();
}



?>
<html>

<header>
<script src="libraries/bootstrap/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="libraries/bootstrap/css/bootstrap.min.css" />


<style>
#HomeMenu{
	max-width: 30%;
	text-align:center;
	position: absolute;
	margin-left: 40%;
	
	
}



</style>


</header>


<body>

<div class="card">
	<div class="card-body" id="HomeMenu">
	
		<h2>Home Menu</h2>
		<br>
		<div class="card bg-light text-dark">
			<button onClick="location.href = 'scrabble/scrabbleGame.php'" id='playScrabble'>
				<div class="card-body">Play Scrabble</div>
			</button>
		</div>
		<br>
		<div class="card bg-light text-dark">
			<button onClick="location.href = 'showGameHistory.php'" id='showHistory'>
				<div class="card-body">View Match History</div>
			</button>
		</div>
		<div class="card bg-light text-dark">
			<button onClick="location.href = 'logout.php'" id='logout'>
				<div class="card-body">Logout</div>
			</button>
		</div>
		<br>
		
	</div>
</div>






</body>







</html>
