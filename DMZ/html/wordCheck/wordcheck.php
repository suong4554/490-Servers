<?php

#$array = $_POST['wordsArr'];
#$array = json_decode($array);

function wordcheck($array){
	$array = json_decode($array);
	require 'vendor/autoload.php'; 

	$endvalue = "";



	for ($i = 0 ; $i < count($array); $i++){
		$response = Unirest\Request::get("https://wordsapiv1.p.rapidapi.com/words/$array[$i]/also",
		  array(
			"X-RapidAPI-Key" => "eb441a0bb3mshaaafa0b9c535e84p1fe1e4jsn792e1f6e3cc4"
		  )
		);


		$number = ($response->code); 
		//404 is nothing 200 is something
		if ($number == 200){
			$endvalue = "true";
		}
		else{
			$endvalue = "false";
			return $endvalue;
		}
	}
	return $endvalue;
}

$temp = wordcheck($array);

print($temp);
?>

