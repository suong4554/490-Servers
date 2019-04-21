<?php

session_start();
$theGoods = $_POST['bagOfChar'];
$currArr = $_POST['currGoods'];
#print($board1);
#$theGoods = '[[" ",0],[" ",0],["E",1],["E",1],["E",1],["E",1],["E",1],["E",1],["E",1],["E",1],["E",1],["E",1],["E",1],["E",1],["A",1],["A",1],["A",1],["A",1],["A",1],["A",1],["A",1],["A",1],["A",1],["I",1],["I",1],["I",1],["I",1],["I",1],["I",1],["I",1],["I",1],["I",1],["O",1],["O",1],["O",1],["O",1],["O",1],["O",1],["O",1],["O",1],["N",1],["N",1],["N",1],["N",1],["N",1],["N",1],["R",1],["R",1],["R",1],["R",1],["R",1],["R",1],["T",1],["T",1],["T",1],["T",1],["T",1],["T",1],["L",1],["L",1],["L",1],["L",1],["S",1],["S",1],["S",1],["S",1],["U",1],["U",1],["U",1],["U",1],["D",2],["D",2],["D",2],["D",2],["G",2],["G",2],["G",2],["B",3],["B",3],["C",3],["C",3],["M",3],["M",3],["P",3],["P",3],["F",4],["F",4],["H",4],["H",4],["V",4],["V",4],["W",4],["W",4],["Y",4],["Y",4],["K",5],["J",8],["X",8],["Q",10],["Z",10]]';
#$currArr = '[]';


$theGoods = json_decode($theGoods);
$currArr = json_decode($currArr);

#$theGoods = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'g','h','i'];
#$currArr = [];

function allotPieces(&$theGoods, $currArr, &$result){
	$currArrLen = count($currArr);
	$Needed = 7 - $currArrLen;
	for($i = 0; $i < $Needed; $i++){
		$temp = array_rand($theGoods);
		array_push($result, $theGoods[$temp]);
		unset($theGoods[$temp]);
		$theGoods = array_values($theGoods);	
	}
	foreach($currArr as $temp){
		array_push($result, $temp);
		
	}
	
	
	
}

$result = [];
allotPieces($theGoods, $currArr, $result);

$final = [];
$final["newPieces"] = $result;
$final["pieces"] = $theGoods;

$final = json_encode($final);
$theGoods = json_encode($theGoods);

$_SESSION["userPieces"] = $theGoods;
print("$final");

?>

