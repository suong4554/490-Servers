<?php
$boardArray = $_POST['board1'];
#print($board1);
function createBoard($boardArray, &$board){
	$board = "<div id='ScrabbleBoard'>";
	$i = 0;

	foreach($boardArray as $line){
		$j = 0;
		$lineString = "";
		$tempStr = "";
		foreach($line as $cell){
			if ($cell[1] == "0" or $cell[2] !=""){
				$cell[1] = "normalText";
			}
			$tempStr = "<div class='tooltip'> <input type='text' id='$i-$j' maxlength='1' class='$cell[1]' value='$cell[2]' onKeyUp='updateJson(this.id, event, playerPieces)'"; #/>";
			if ($cell[2] != ""){
				$tempStr = $tempStr . " readonly";
			}
			$tempStr = $tempStr . "/> <span class='tooltiptext' id='$i-$j-tooltip'> Value = $cell[0] </span> </div>";
			 
			$lineString = $lineString . $tempStr;
			
			$j +=1;
		}
		$lineString = $lineString . "<br>";
		$board = $board . $lineString;
		
		$i +=1;
	}
	$board = $board . "</div>";
	
return $board;	
}

$htmlBoard = "";
createBoard($boardArray, $htmlBoard);

print($htmlBoard);

?>

