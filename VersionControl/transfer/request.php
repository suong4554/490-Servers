#!/usr/bin/php
<?php
$argv;

$command = $argv[0];

######COMMANDS#########
#getDev
#toDev
#getQA
#toQA
#getProd
#toProd



if(isset($argv[1])){
	$version = $argv[1];
}
else{
	$version = "current";
}



if($command == "getDev"){
	
}
elseif($command == "getQA"){
	
}
elseif($command == "getProd"){
	
}


?>