<?php

session_start();

$turn = $_POST['turnD'];


$_SESSION["turn"] = $turn;

print("{}");
?>
