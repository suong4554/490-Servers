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
<title>Scrabble Home</title>
<script defer src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>


<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</header>

<style>
#HomeMenu{
	max-width: 30%;
	text-align:center;
	position: absolute;
	margin-left: 40%;
	
	
}



</style>





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
