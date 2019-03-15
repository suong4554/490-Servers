<?php

$path = "test.log";
echo "Path : $path";
require $path;


error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set("error_log", "test.log");
echo "\n";

foreach(glob('dir/*.php') as $file) {
	include $file;
	echo ("$file");
}

?>
