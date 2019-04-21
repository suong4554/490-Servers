<?php

session_start();
$filename = $_POST['file'];

unlink($filename);

$_SESSION = array();
#clear session from disk
session_destroy();






print("{}");
?>
