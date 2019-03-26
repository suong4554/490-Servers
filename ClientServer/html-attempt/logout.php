<?php

session_start();

include("Function.php");
$_SESSION = array();
#clear session from disk
session_destroy();

redirect("", "index.php", 0);
exit();

?>
