<?php

$di = new RecursiveDirectoryIterator(__DIR__,RecursiveDirectoryIterator::SKIP_DOTS);
$it = new RecursiveIteratorIterator($di);

foreach($it as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) == "php") {
	echo $file, PHP_EOL;
	exec("$file", $ouput);
	//print_r($output);
	$error = shell_exec("php -l $file");
	error_log("$error", 3,"errorlogs.log");
    }
}
?>
