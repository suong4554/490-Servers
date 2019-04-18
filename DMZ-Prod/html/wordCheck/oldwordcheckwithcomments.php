<?php

function wordcheck()
{
require 'vendor/autoload.php'; 
//need to accept some background api stuff

$array = array ("koppisch","bar","tar");
var_dump($array);
echo "\n";
echo "\n";
//Made a manual array for testing

//print_r("The count of the array is: ");
//print_r(count($array));
//Checking the size of the array again for safe measure
echo "\n";
echo "\n";


//echo "The words in the array are: ";
for ($i = 0 ; $i < count($array); $i++)
{ 
	//echo $array[$i];
	//echo " ";
}
//Going through the array word by word with a for loop
echo "\n";
echo "\n";


for ($a = 0 ; $a < count($array); $a++)
{
$response = Unirest\Request::get("https://wordsapiv1.p.rapidapi.com/words/$array[$a]/also",
  array(
    "X-RapidAPI-Key" => "eb441a0bb3mshaaafa0b9c535e84p1fe1e4jsn792e1f6e3cc4"
  )
);
//for loop goes through each word and sends it to the Words API

//you can print_r($response); for all the code

$number = ($response->code); 
//404 is nothing 200 is something
//print_r($number); to check
if ($number == 404)
{
	print_r($array[$a]);	
	echo " ";
	var_dump((bool) "");
}
else
{
	print_r($array[$a]);
	echo "  ";
	var_dump((bool) 1);
}
//If statment for true or false if word is found in Words API
echo "\n";
echo "\n";
}//ends the for loop for words api
//Words API provided code to access the Words API
}//ends the whole wordcheck function
wordcheck();
?>

