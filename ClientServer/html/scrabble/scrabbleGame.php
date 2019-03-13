<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);	
#date_default_timezone_set("America/New_York");
session_set_cookie_params(0, "/var/www/html", "localhost");
session_start();
/*
$_SESSION = array();
#clear session from disk
session_destroy();
session_set_cookie_params(0, "/var/www/html", "localhost");
session_start();
*/

$_SESSION["login"] = True;
$_SESSION["user"]= "Bill";
//$_SESSION["turn"] = 0;


//checks whether or not file exists to make sure that game was not quit intentionally or if a new game was started
$filename = "gameState/" . $_SESSION["user"] . "gameState.txt";


$newGame = "true";
if(file_exists($filename)){
	$newGame = "false";
}
//echo $newGame;

if(!isset($_SESSION["login"])){
   $_SESSION["login"] = False;
}
else{
	$user = $_SESSION["user"];
}

if(!$_SESSION["login"]){
	header('Location: localhost');
	exit;
}


?>
<html>
<head>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script src="https://requirejs.org/docs/release/2.3.5/minified/require.js"></script>

</head>

<style>
.tooltip {
  position: relative;
  display: inline-block;
  border-bottom: 1px dotted black; /* If you want dots under the hoverable text */
}

/* Tooltip text */
.tooltip .tooltiptext {
  visibility: hidden;
  width: 120px;
  bottom: 100%;
  left: 50%;
  margin-left: -20px;
  background-color: gray;
  color: #fff;
  text-align: center;
  padding: 5px 0;
  border-radius: 6px;
 
  /* Position the tooltip text - see examples below! */
  position: absolute;
  z-index: 1;
}

/* Show the tooltip text when you mouse over the tooltip container */
.tooltip:hover .tooltiptext {
  visibility: visible;
}
#ScrabbleContainer{
	min-width: 610px;
	min-height: 610px;
}

#ScrabbleBoard input{
	width:40px;
	height:40pxpx;
	outline: 2px solid #808080;
    font-family: arial;
    font-size: 26px;
    
    letter-spacing: 6px;
	text-transform:uppercase;
}

.normalText{
	background-color: #F5F5DC;

}
.DWS{
	background-color: #FFB6C1;
}

.TWS{
	background-color: #CD5C5C;

}
.TLS{
	background-color: #1E90FF;

}
.DLS{
	background-color: #87CEFA;

}

#pieceContainer{
	min-width: 300px;
	min-height: 85px;
	
}

#pieceContainer input{
	width:40px;
	height:40pxpx;
	outline: 2px solid #808080;
    font-family: arial;
    font-size: 26px;
    
    letter-spacing: 6px;
	text-transform:uppercase;
}

</style>
<body onload="init()">
<button type="button" id="clearCookies" onClick="logOut()">log out/Quit Game</button>
<br>
<div id="ScrabbleContainer">


</div>

<button type="button" id="turnEnd" onClick="turnEnd(board, origboard, turn, pieces, playerPieces)">End Turn</button>
<br>
<label>User:</label><input type="text" id="user" readonly></input>
<br>
<label>Turn:</label><input type="text" id="turnCount" readonly></input>
<br>
<label>Score:</label><input type="text" id="scoreHolder" readonly></input>
<br>

<div id="pieceContainer">

</div>
</body>


<script>

function init(){
	newGame = <?php print $newGame; ?>
//	console.log(newGame)
//	newGame = true
	user = "<?php print $user; ?>"
	console.log("New Game: " + newGame)
	if(newGame){
		turnZero()
	}
	else{
		var filename = "gameState/" + user + "gameState.txt"
		var stats = fetchFile(filename)
		user = stats["user"]
		score = stats["score"]
		playerPieces = stats["playerPieces"]
		pieces = stats["pieces"]
		turn = stats["turn"]
		console.log(stats)
		
		var result = fetchFile("python/old.json")
		board = result["board"]
		origboard = JSON.parse(JSON.stringify(board));
		
		
		
		//for the pieces

		temp = allotPieces(pieces, playerPieces)
		pieces = temp["pieces"]
		playerPieces = temp["newPieces"]
		htmlBoard = redrawBoard(board)
		
		document.getElementById("ScrabbleContainer").innerHTML = htmlBoard
		document.getElementById("user").value = user;
		document.getElementById("turnCount").value = turn.toString()
		document.getElementById("scoreHolder").value = score.toString()
		showPieces(playerPieces)
	}
	

}

function fetchFile(filename){
	console.log("fetchFile Function")
	
	var temp = ""
	$.ajax({
	type:'POST',
	async: false,
	url: "fetchStats.php",
	data: {file:filename},
	dataType: "json"
	})
	.done(function(msg){
		console.log("succesfully retrieved User data");
		//console.log(msg);
		temp = msg;
	})
	.fail(function(msg){
		console.log("failed to retrieve User data");
		console.log(msg);
		
	});
	return temp
}





function turnZero(){
	console.log("init Function")
	jsonBoard = executePythonScript("python/generateBoard.py");

	//console.log(jsonBoard)
	
	result = jsonBoard
	board = result["board"]
	//need to stringify first in order to get a non pointer object
	origboard = JSON.parse(JSON.stringify(board));
	//turn = result["turn"]
	turn = 0
	pieces = result["pieces"]
	score = 0
	
	document.getElementById("turnCount").value = user
	document.getElementById("turnCount").value = score.toString()
	
	
	
	writeBoardFile(board, turn, pieces, "python/old.json")
	console.log(board)
	
	htmlBoard = ""
	htmlBoard = redrawBoard(board)
	
	playerPieces = []
	temp = allotPieces(pieces, playerPieces)
	pieces = temp["pieces"]
	playerPieces = temp["newPieces"]
	//console.log(pieces)
	//console.log(playerPieces)
	
	
	document.getElementById("ScrabbleContainer").innerHTML = htmlBoard
	document.getElementById("user").value = user;
	document.getElementById("turnCount").value = turn.toString()
	document.getElementById("scoreHolder").value = score.toString()
	showPieces(playerPieces)
	
}


function executePythonScript(filename){
	console.log("executePythonScript Function")
	
	var temp = ""
	$.ajax({
	type:'POST',
	async: false,
	url: "executePython.php",
	data: {file:filename},
	dataType: "json"
	})
	.done(function(msg){
		console.log("succefully executed python");
		//console.log(msg);
		temp = msg;
	})
	.fail(function(msg){
		console.log("failed to execute python");
		console.log(msg);
		
	});
	return temp
	
}

function redrawBoard(board){
	console.log("redrawBoard Function")
	var temp = ""
	$.ajax({
		type:'POST',
		async: false,	
		url: "redrawBoard.php",
		data: {board1: board},
		dataType: "text"
		
	})
	.done(function(msg){
		console.log("succefully drew board");
		temp = msg;
		
	})
	.fail(function(msg){
		console.log("failed to draw board");
		console.log(msg);
	});
	return temp;
}

function writeBoardFile(board, turn, pieces, filename){
	console.log("writeBoardFile Function")
	var dict = {};
	dict["board"] = board
	dict["turn"] = turn
	dict["pieces"] = pieces
	
	
	jsonString = JSON.stringify(dict)
	//console.log(jsonString)
	console.log(filename)
	$.ajax({
		type:'POST',
		async: false,
		url: "writeToFile.php",
		data: {json: jsonString, file: filename},
		dataType: "json"
		
	})
	.done(function(msg){
		console.log("succefully wrote board to file");
		//console.log(msg);
	})
	.fail(function(msg){
		console.log("failed to write board to file");
		console.log(msg);
	});
}



function setImmute(HTMLid, event){
	document.getElementById(HTMLid).setAttribute('readonly', 'readonly');
	
}

function determineScore(letter){
	console.log("determineScore Function")
	if (letter == ""){
		return "0"
	}
	if ("AEIOULNSTR".includes(letter)){
		return "1"
	}
	if ("DG".includes(letter)){
		return "2"
	}
	if ("BCMP".includes(letter)){
		return "3"
	}
	if ("FHVWY".includes(letter)){
		return "4"
	}
	if ("K".includes(letter)){
		return "5"
	}
	if ("JX".includes(letter)){
		return "8"
	}
	if ("QZ".includes(letter)){
		return "10"
	}
	return "0"
	
	
}

function updateJson(HTMLid, event, playerPieces){
	console.log("updateJson Function")
	
	var contents = document.getElementById(HTMLid).value.toUpperCase();
	console.log(playerPieces)
	var score = determineScore(contents)
	console.log(playerPieces)
	var coord = HTMLid.split("-")
	var height = coord[0]
	var width = coord[1]
	//console.log(height, width)
	board[height][width][2] = contents;
	board[height][width][0] = score;
	
	document.getElementById(HTMLid + "-tooltip").innerHTML = "Value = " + score;

	
	
	console.log(playerPieces)
	showPieces(playerPieces)
	
	//console.log(board[height][width][2])
	//console.log(board[height][width][0])
	
	
	
	
}

function showPieces(playerPieces){
	console.log("showPieces Function")
	var piecesHTML = ""
	for (var i =0; i < playerPieces.length; i ++){
		piecesHTML += "<input type='text' id='" + i +  "showPieceChar' maxlength='1' class='showPieceChar' value='" + playerPieces[i][0] + "' readonly />";
	}
	piecesHTML += "<br>"
	for (var i =0; i < playerPieces.length; i ++){
		var score = determineScore(playerPieces[i][0])
		var tempSize = ""
		if (score === "10"){
			tempSize = "fontSize='10px'"
			
		}
		piecesHTML += "<input type='text' id='" + i +  "showPieceValue' maxlength='2' class='showPieceChar' value='" + score + "' " + tempSize + "readonly />";
	}
	
	document.getElementById("pieceContainer").innerHTML = piecesHTML
	
	
}


//will need to edit this later in order to make it actually call the words api
function callWordsAPI(words){
	console.log("callWordsAPI Function")
	return true
	
}


function allotPieces(pieces, playerPieces){
	console.log("allotPieces Function")
	var temp = ""
	pieces = JSON.stringify(pieces)
	playerPieces = JSON.stringify(playerPieces)
	//console.log(pieces)
	$.ajax({
		type:'POST',
		async: false,
		url: "allotPieces.php",
		data: {bagOfChar: pieces, currGoods: playerPieces},
		dataType: "json",
		success: function(data){
			temp = data;
			console.log("alloted pieces");
		},
		error: function(data){
			console.log(data);
			console.log("failed to allot pieces");
		}	});
	return temp

	
	
	
}


function checkPieces(playerPieces, origboard, board, firstTurnIdent){
	var usedChar = []
	
	var boardSize = origboard.length
	
	var tempPieces = playerPieces.slice(0);
	
	for(var i = 0; i < boardSize; i++){
		for(var j = 0; j < boardSize; j++){
			if(board[i][j][2] != origboard[i][j][2]){
				
				usedChar.push(board[i][j][2])
			}
			
		}
	}
	console.log("these are the used characters" + usedChar)
	console.log(playerPieces)
	//returns true if the characters are all valid
	var temp = false
	if(firstTurnIdent){
		for(var i = 0; i < usedChar.length; i++){
			temp = false
			for(var j = 0; j < playerPieces.length; j++){
				
				if(usedChar[i] === playerPieces[j][0]){
					playerPieces.splice(j, 1)
					temp = true
					//break for efficiency
					break
				}
			}
			if (temp === false){
				break
			}
		}
	}
	console.log(playerPieces)
	playerPieces = tempPieces.slice(0);
	console.log(playerPieces)
	return temp
	
	
	
}





function turnEndPHP(playerPieces, pieces, score, userName, turn, fileN){
	console.log("turnEndPHP Function")
	var dict = {};
	dict["playerPieces"] = playerPieces
	dict["score"] = score
	dict["user"] = userName
	dict["turn"] = turn
	dict["pieces"] = pieces
	jsonString = JSON.stringify(dict)
	//console.log(jsonString)
	console.log(fileN)
	$.ajax({
		type:'POST',
		async: false,
		url: "writeToFile.php",
		data: {json: jsonString, file: fileN},
		dataType: "json"
		
	})
	.done(function(msg){
		console.log("succefully wrote to file");
		//console.log(msg);
	})
	.fail(function(msg){
		console.log("failed to write to file");
		console.log(msg);
	});
}
	
	

function incrementTurn(turn){
	$.ajax({
		type:'POST',
		async: false,
		url: "setCookies.php",
		data: {turnD: turn},
		dataType: "json"
		
	})
	.done(function(msg){
		console.log("succefully set cookie");
		//console.log(msg);
	})
	.fail(function(msg){
		console.log("failed toset cookie");
		console.log(msg);
	});
}



function logOut(){
	
	filename = "gameState/" + user + "gameState.txt"
	$.ajax({
		type:'POST',
		async: false,
		url: "logout.php",
		data: {file: filename},
		dataType: "json"
		
	})
	.done(function(msg){
		console.log("succefully removed cookies and delete gamestate file");
		//console.log(msg);
	})
	.fail(function(msg){
		console.log("failed to remove cookies and delete gamestate file");
		console.log(msg);
	});
	
	location.reload();
	
}
	
	

function checkFirstTurn(){
	console.log("checkFirstTurn Function")
	var temp = true
	if(newGame){
		if(board[7][7][2] === ""){
			temp = false
		}
	}
	else{
		return temp
	}
	return temp
	
}


function turnEnd(board, origboard, turn, pieces, playerPieces){
	console.log("turnEnd Function")
	writeBoardFile(board, turn, pieces, "python/temp.json")

	var tempresult = executePythonScript("python/turnEnd.py")
	//tempresult = JSON.parse(result);
	var changedWords = tempresult["words"];
	var tempBoard = tempresult["board"];
	var tempscore = tempresult["score"];
	
	
	var firstTurnIdent = checkFirstTurn()
	console.log(firstTurnIdent)
	var charactersUsed = checkPieces(playerPieces, origboard, board, firstTurnIdent)
	
	console.log(charactersUsed)
	
	var wordExists = callWordsAPI(changedWords)
	
	if(wordExists && charactersUsed && firstTurnIdent){
	
		turn +=1
		board = tempBoard
		score = tempscore[0] + score;
		console.log("score: " + score)
		console.log("turn: " + turn)
		console.log("the score is:" + score)
		//origboard = JSON.parse(JSON.stringify(tempBoard));
		
		incrementTurn(turn, score)
		//console.log(user)
		var filename = "gameState/" + user + "gameState.txt"
		
		
		
		
		var dict = {};
		dict["playerPieces"] = playerPieces
		dict["score"] = score
		dict["user"] = user
		dict["turn"] = turn
		dict["pieces"] = pieces
		jsonString = JSON.stringify(dict)
		//console.log(jsonString)
		console.log(filename)
		console.log(dict["score"])
		$.ajax({
			type:'POST',
			async: false,
			url: "writeToFile.php",
			data: {json: jsonString, file: filename},
			dataType: "json"
			
		})
		.done(function(msg){
			console.log("succefully wrote to file");
			//console.log(msg);
		})
		.fail(function(msg){
			console.log("failed to write to file");
			console.log(msg);
		});
		

		writeBoardFile(board, turn, pieces, "python/old.json")
		location.reload();
		

	}
	else if(!firstTurnIdent){
		alert("Please place a piece in the middle of the board")
	}
	else if(!charactersUsed){
		alert("Please use characters that were alloted at beginning of turn")
	}

}
	



</script>


</html>


