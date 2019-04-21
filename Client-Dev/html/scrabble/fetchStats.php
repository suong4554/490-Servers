<?php

$filename = $_POST['file'];

$contents = file_get_contents($filename);

print($contents);

?>
