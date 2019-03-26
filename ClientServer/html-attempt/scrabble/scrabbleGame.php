<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);	
session_start();

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

//testing

#$_SESSION["login"] = True;
#$_SESSION["user"]= "Bill";
#$_SESSION["turn"] = 0;
#$_SESSION["gameID"] = 1;



//checks whether or not file exists to make sure that game was not quit intentionally or if a new game was started
$filename = "gameState/" . $_SESSION["user"] . "gameState.txt";
$newGame = "true";



require_once('../path.inc');
require_once('../get_host_info.inc');
require_once('../rabbitMQLib.inc');

$client = new rabbitMQClient('../MySQLRabbit.ini', 'MySQLRabbit');





if(file_exists($filename)){
	$newGame = "false";
}


if(!isset($_SESSION["login"]) or !$_SESSION["login"]){
   $_SESSION["login"] = False;
   redirect("", "../index.php", 0);
}
else{
	$user = $_SESSION["user"];
	//$gameID = findInfo($user, "Matchid");
	
	
	//Gets game ID
	$request = array();
	$request['type'] = "findInfo";
	$request['user1'] = $user;
	$request['info'] = "Matchid";
	$request["message"] = "ugh";
	$response = $client->send_request($request);
	$gameID = $response["result"];
	$_SESSION["gameID"] = $gameID;
	
	
	//Gets turn priority
	//$turnPriority = !boolval(findInfo($user, "currentTurn"));
	$request = array();
	$request['type'] = "findInfo";
	$request['user1'] = $user;
	$request['info'] = "currentTurn";
	$request["message"] = "ugh";
	$response = $client->send_request($request);
	$turnPriority = !boolval($response["result"]);
	

	//Gets turn
	$request = array();
	$request['type'] = "findInfo";
	$request['user1'] = $user;
	$request['info'] = "turn";
	$request["message"] = "ugh";
	$response = $client->send_request($request);
	$turnPriority = $response["result"];
	//$turn = findInfo($user, "turn");
}






?>
<html>
<head>

<script defer src="../libraries/jquery-3.3.1.min.js"></script>
<!-- <script defer src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<!-- <script src="https://requirejs.org/docs/release/2.3.5/minified/require.js"></script> -->

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
	<button type="button" id="endGameRedirect" onClick="endGame()">End Game/Declare Winner</button>
	<br>
	<div id="ScrabbleContainer"></div>

	<button type="button" id="turnEnd" onClick="turnEnd(board, origboard, turn, pieces, playerPieces)">End Turn</button>
	<button type="button" id="pass" onClick="pass(board, origboard, turn, pieces, playerPieces)">Pass (skips your turn)</button>
	<br>
	<label>User:</label><input type="text" id="user" readonly></input>
	<label>Opponent:</label><input type="text" id="user2" readonly></input>
	<br>
	<label>User Score:</label><input type="text" id="scoreHolder" readonly></input>
	<label>Opponent Score:</label><input type="text" id="user2scoreHolder" readonly></input>
	<br>
	<label>Turn:</label><input type="text" id="turnCount" readonly></input>
	<br>

	<div id="pieceContainer"></div>
	<div id="ChatMessages"></div>
	<div id="ChatBig"> 
		<span style="color:green">Chat</span><br/>
		<textarea id="ChatText" name="ChatText"></textarea>
	</div>

</body>

<script src="chat/jquery.js"></script>
<script>
function checkFinish(){
	temp = checkGameState()
	console.log(temp)
	if(temp == false){
		clearInterval(interval);
		alert(user2 + " has ended or Quit the Game, you will be shortly redirected, to check match-history, click the show Match History Button on the home page")
		location.replace("../home.php")
	}
}
function init(){
	newGame = <?php print $newGame; ?>
//	console.log(newGame)
//	newGame = true
	user = "<?php print $user; ?>"
	gameID = "<?php  print $gameID; ?>"
	turnPriority = ("<?php print json_encode($turnPriority); ?>" == 'true')
	user2 = getOtherUser()
	score2 = getUserScore(user2)
	score = getUserScore(user)
	
	letters = /^[A-Za-z]+$/;
	console.log("New Game: " + newGame)
	gameState = checkGameState()
	console.log("gameState: " + gameState)
	
	//Checks game state
	interval = setInterval(checkFinish, 1000)
	
	/*
	if(!checkGameState()){
		//Gamestate will be true if game exists, false if not
		alert(user2 + " has ended or Quit the Game, you will be shortly redirected, to check match-history, click the show Match History Button on the home page")
		location.replace("../home.php")
		//endGame()
	}
	*/
	if(turnPriority== false){
		window.location.replace("waitingForTurn.php");
	}
	
	if(turnPriority){
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
			
			var result = fetchFile("python/" + gameID + "old.json")
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
			document.getElementById("user2scoreHolder").value = score2.toString()
			document.getElementById("user2").value = user2;
			showPieces(playerPieces)
			
			
			//writes to file to ensure that pieces are not reloaded
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
			

			writeBoardFile(board, turn, pieces, "python/" + gameID + "old.json")
					
					
		}
	}
	

}


function getTurnPriority(){
	var turnPriority = false
	$.ajax({
		url: 'matchmaking/executeFunction.php',
		type: 'POST',
		async: false,
		data:{fName:"discoverPriority"},
		beforeSend: function() {
			console.log("Getting turn Priority")
		},
		fail: function(xhr, status, error) {
			alert("Error Message:  \r\nNumeric code is: " + xhr.status + " \r\nError is " + error);
		},
	
		success: function(result) {
			console.log("turnPriority" + result)
			turnPriority = result;
		}
	});	
	console.log("turnPriority: " + turnPriority)
	return turnPriority
}


function getOtherUser(){
	var otherUser = ""
	$.ajax({
		url: 'matchmaking/executeFunction.php',
		type: 'POST',
		async: false,
		data:{fName:"getOtherUserinGame", user1:user},
		beforeSend: function() {
			console.log("Getting other User")
		},
		fail: function(xhr, status, error) {
			alert("Error Message:  \r\nNumeric code is: " + xhr.status + " \r\nError is " + error);
		},
	
		success: function(result) {
			console.log("other user is:" + result)
			otherUser = result;
		}
	});	
	//returns the username of the other user looking for a match
	return otherUser
}

function getUserScore(user){
	var score
	$.ajax({
		url: 'matchmaking/executeFunction.php',
		type: 'POST',
		async: false,
		data:{fName:"getUserScore", user1:user},
		beforeSend: function() {
			console.log("Getting User Score")
			//$("#centerloader").addClass("loader");
		},
		fail: function(xhr, status, error) {
			alert("Error Message:  \r\nNumeric code is: " + xhr.status + " \r\nError is " + error);
		},
	
		success: function(result) {
			//$("#centerloader").removeClass("loader");
			console.log( user + "'s score is:" + result)
			score = result;
		}
	});	
	//returns the username of the other user looking for a match
	return score
}




function turnZero(){
	console.log("init Function")
	//checks if user was first to go or not
	if(score2 == 0){
		jsonBoard = executePythonScript("python/" + "generateBoard.py " + gameID);
		result = jsonBoard
		console.log(result)
		board = result["board"]
		//need to stringify first in order to get a non pointer object
		origboard = JSON.parse(JSON.stringify(board));
		turn = 0
		pieces = result["pieces"]
	}
	else{
		var result = fetchFile("python/" + gameID + "old.json")
		board = result["board"]
		origboard = JSON.parse(JSON.stringify(board));
		turn = 0
		pieces = result["pieces"]
		
	}
	
	document.getElementById("turnCount").value = user
	document.getElementById("turnCount").value = score.toString()
	
	
	
	writeBoardFile(board, turn, pieces, "python/" + gameID + "old.json")
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
	document.getElementById("user2scoreHolder").value = score2.toString()
	document.getElementById("user2").value = user2;
	showPieces(playerPieces)
	
	//writes gamestate at start to ensure that reloading page does not allow for a refresh of pieces
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
	

	writeBoardFile(board, turn, pieces, "python/" + gameID + "old.json")
	
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
	var temp = ""
	words = JSON.stringify(words)
	console.log(words)
	$.ajax({
		type:'POST',
		async: false,
		url: "callWordCheck.php",
		data: {wordsArr: words},
		dataType: "text",
		success: function(data){
			temp = data;
			console.log("successfully checked words");
		},
		error: function(data){
			console.log(data);
			console.log("failed to check words");
		}	});
	//console.log("word check " + temp)

	//temp = JSON.parse(temp)
	temp = (/true/i).test(temp)
	//console.log("word check " + boolValue)
	return temp
	
	//return true
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
	if(origboard == board){
		temp = true
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
	endMatch()
	location.replace("../index.php")
	
}
	
	

function checkFirstTurn(){
	console.log("checkFirstTurn Function")
	var temp = true
	if(newGame){
		if(!board[7][7][2].match(letters)){
			temp = false
		}
	}
	else{
		return temp
	}
	return temp
	
}





function checkAdjacent(){
	var boardSize = board.length;
	
	for(var i = 0; i < boardSize; i++){
		for(var j = 0; j < boardSize; j++){
			if(board[i][j][2].match(letters)){
				if(i > 0 && i < 14){
					if (j > 0 && j < 14){
						if(board[i-1][j][2].match(letters) || board[i+1][j][2].match(letters) || board[i][j+1][2].match(letters) || board[i][j-1][2].match(letters)){
							return true
						}
					}
					else if(j == 0){
						if(board[i-1][j][2].match(letters) || board[i+1][j][2].match(letters) || board[i][j+1].match(letters)){
							return true
						}
					}
					else if(j==14){
						if(board[i-1][j][2].match(letters) || board[i+1][j][2].match(letters) || board[i][j-1].match(letters)){
							return true
						}
					}
				}
				else if(i == 0){
					if (j > 0 && j < 14){
						if(board[i+1][j][2].match(letters) || board[i][j+1][2].match(letters) || board[i][j-1][2].match(letters)){
							return true
						}
					}
					else if(j == 0){
						if(board[i+1][j][2].match(letters) || board[i][j+1][2].match(letters)){
							return true
						}
					}
					else if(j==14){
						if(board[i+1][j][2].match(letters) || board[i][j-1][2].match(letters)){
							return true
						}
					}
				}
				else if(i == 14){
					if (j > 0 && j < 14){
						if(board[i-1][j][2].match(letters) || board[i][j+1][2].match(letters) || board[i][j-1][2].match(letters)){
							return true
						}
					}
					else if(j == 0){
						if(board[i-1][j][2].match(letters) || board[i][j+1][2].match(letters)){
							return true
						}
					}
					else if(j==14){
						if(board[i-1][j][2].match(letters) || board[i][j-1][2].match(letters)){
							return true
						}
					}
				}

			}
			
		}
	}
	return false
}

function updateMatch(){
	$.ajax({
		url: 'matchmaking/executeFunction.php',
		type: 'POST',
		async: false,
		data:{fName:"updateMatch", user1:user, turn: turn},
		beforeSend: function() {
			console.log("Updating Match")
			//$("#centerloader").addClass("loader");
		},
		fail: function(xhr, status, error) {
			alert("Error Message:  \r\nNumeric code is: " + xhr.status + " \r\nError is " + error);
		},
	
		success: function(result) {
			console.log("match updated")
		}
	});	
}


function switchTurn(){
	$.ajax({
		url: 'matchmaking/executeFunction.php',
		type: 'POST',
		async: false,
		data:{fName:"switchTurn", user1:user, user2: user2},
		beforeSend: function() {
			console.log("Updating Turns")
			//$("#centerloader").addClass("loader");
		},
		fail: function(xhr, status, error) {
			alert("Error Message:  \r\nNumeric code is: " + xhr.status + " \r\nError is " + error);
		},
	
		success: function(result) {
			console.log("Turns updated")
		}
	});	
}

function updateUserScore(){
	$.ajax({
		url: 'matchmaking/executeFunction.php',
		type: 'POST',
		async: false,
		data:{fName:"updateUserScore", user1:user, score1:score},
		beforeSend: function() {
			console.log("Updating User Score")
			//$("#centerloader").addClass("loader");
		},
		fail: function(xhr, status, error) {
			alert("Error Message:  \r\nNumeric code is: " + xhr.status + " \r\nError is " + error);
		},
	
		success: function(result) {
			console.log(user + "'s Score updated")
		}
	});	
}

function checkGameState(){
	var matchStat
	$.ajax({
		url: 'matchmaking/executeFunction.php',
		type: 'POST',
		async: false,
		data:{fName:"checkGameState", user1: user},
		beforeSend: function() {
			console.log("Checking Match status")
		},
		fail: function(xhr, status, error) {
			alert("Error Message:  \r\nNumeric code is: " + xhr.status + " \r\nError is " + error);
		},
	
		success: function(result) {
			matchStat = (result == 'true');
		}
	});	
	return matchStat
}

function getLooking(){
	var inMatch = ""
	$.ajax({
		url: 'matchmaking/executeFunction.php',
		type: 'POST',
		async: false,
		data:{fName:"getLooking", user1:user},
		beforeSend: function() {
			console.log("Getting Looking stat")
		},
		fail: function(xhr, status, error) {
			alert("Error Message:  \r\nNumeric code is: " + xhr.status + " \r\nError is " + error);
		},
		success: function(result) {
			inMatch = (result == 'true');
			console.log("user currently in match:" + inMatch)
		}
	});	
	//returns the username of the other user looking for a match
	return inMatch
}

function endMatch(){
	$.ajax({
		url: 'matchmaking/executeFunction.php',
		type: 'POST',
		async: false,
		data:{fName:"endMatch", user1:user, user2:user2},
		beforeSend: function() {
			console.log("Ending Match")
		},
		fail: function(xhr, status, error) {
			alert("Error Message:  \r\nNumeric code is: " + xhr.status + " \r\nError is " + error);
		},
		success: function(result) {
			console.log("Ended Match")
		}
	});	
}

function turnEnd(board, origboard, turn, pieces, playerPieces){
	if(!checkGameState()){
		//Gamestate will be true if game exists, false if not
		alert(user2 + " has ended the game")
		endGame()
	}
	
	
	console.log("turnEnd Function")
	writeBoardFile(board, turn, pieces, "python/" + gameID + "temp.json")

	var tempresult = executePythonScript("python/turnEnd.py " + gameID)
	//tempresult = JSON.parse(result);
	var changedWords = tempresult["words"];
	var tempBoard = tempresult["board"];
	var tempscore = tempresult["score"];
	console.log("tempScore is: " + tempscore)
	console.log("score is: " + score)
	var firstTurnIdent = checkFirstTurn()
	console.log(firstTurnIdent)
	var charactersUsed = checkPieces(playerPieces, origboard, board, firstTurnIdent)
	
	console.log(charactersUsed)
	console.log(changedWords)
	var wordExists = callWordsAPI(changedWords)
	//testing
	//var wordExists = true
	
	console.log("word exists" + wordExists)
	var adjacent = false
	adjacent = checkAdjacent()
	console.log("adjacent is " + adjacent)
	
	if(wordExists && charactersUsed && firstTurnIdent && adjacent){
		turn +=1
		board = tempBoard
		score = parseInt(tempscore, 10) + parseInt(score, 10);
		console.log("score: " + score)
		console.log("turn: " + turn)
		console.log("the score is:" + score)
		//origboard = JSON.parse(JSON.stringify(tempBoard));
		
		incrementTurn(turn, score)
		//console.log(user)
		var filename = "gameState/" + user + "gameState.txt"
		
		
		updateMatch()
		switchTurn()
		updateUserScore()
		
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
		

		writeBoardFile(board, turn, pieces, "python/" + gameID + "old.json")
		location.reload();
		

	}

	else if(!firstTurnIdent){
		alert("Please place a piece in the middle of the board")
	}
	else if(!adjacent){
		alert("Please place pieces adjacent to each other (single letter words are not allowed such as 'a' or 'I')")
	}
	else if(!charactersUsed){
		alert("Please use characters that were alloted at beginning of turn")
	}
	else if(!wordExists){
		alert("Word does not exist")
	}

}

function pass(board, origboard, turn, pieces, playerPieces){
	console.log("Pass Function")
		
	score = parseInt(score, 10);

	turn +=1
	updateMatch()
	switchTurn()
	updateUserScore()
	
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
	
	writeBoardFile(board, turn, pieces, "python/" + gameID + "old.json")
	location.reload();

}
function endGame(){
	switchTurn()
	console.log("Game ending")
	
	//need to change later so that way when two users connect can get proper results
	//var score2 = 0
	//var user2 = "Joel"
	var winner = "user"
	if(score > score2){
		winner = user
	}
	else{
		winner = user2
	}
	
	filename1 = "gameState/" + user + "gameState.txt"
	
	filename2 = "gameState/" + user2 + "gameState.txt"
	
	
	$.ajax({
		type:'POST',
		async: false,
		url: "savetoSQL.php",
		data: {user1: user, user2: user2, winner: winner, score1: score, score2: score2, turns: turn, filename1: filename1, filename2: filename2},
		dataType: "json"
		
	})
	.done(function(msg){
		console.log("succefully wrote to SQL");
		//console.log(msg);
	})
	.fail(function(msg){
		console.log("failed to write to SQL");
		console.log(msg);
	});
	endMatch()
	alert("Winner is " + winner + ", you will be redirected to the home page shortly: Note if the other player ended the game then it is possible that there was no winner")
	location.replace("../home.php")
	
}
	
$(document).ready(function() {
	$("#ChatText").keyup(function(e){
			if(e.keyCode == 13) {
					
				var ChatText = $("#ChatText").val();
				$.ajax({
					type:'POST',
					url:'chat/insertMessage.php',
					data:{ChatText:ChatText},
					success:function()
					{
						$("#ChatText").val("");
					
					},
					fail:function()
					{
					alert('request failed');
					}

				})
			}
	})
	
	setInterval(function(){
			$("#ChatMessages").load("chat/DisplayMessages.php");
	},1500)
	
	$("#ChatMessages").load("chat/DisplayMessages.php");
	
});


</script>


</html>


