<?php

function wordcheck()
{
require 'vendor/autoload.php'; 

$endvalue = 0;
$array = array ("koppisch","bar","tar");
var_dump($array);
echo "\n";
echo "\n";
//Made a manual array for testing


echo "\n";
echo "\n";



for ($i = 0 ; $i < count($array); $i++)
{ 
	//echo $array[$i];
	//echo " ";
}

echo "\n";
echo "\n";


for ($a = 0 ; $a < count($array); $a++)
{
$response = Unirest\Request::get("https://wordsapiv1.p.rapidapi.com/words/$array[$a]/also",
  array(
    "X-RapidAPI-Key" => "eb441a0bb3mshaaafa0b9c535e84p1fe1e4jsn792e1f6e3cc4"
  )
);


$number = ($response->code); 
//404 is nothing 200 is something
if ($number == 200)
{
	
	print_r($array[$a]);	
	echo " ";
	$endvalue == true;
}
else
{
	print_r($array[$a]);
	echo "  ";
	$endvalue == false;
	print_r($endvalue);
	return;
}
//prints the last word and ends if anything is false so if anything is false everything is false.
echo "\n";
echo "\n";
}
}
wordcheck();
?>

