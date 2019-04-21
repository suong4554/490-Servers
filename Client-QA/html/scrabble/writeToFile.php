<?php

$data = $_POST['json'];
$filename = $_POST['file'];


$f = fopen($filename, 'w');
fwrite($f, $data);
fclose($f);
#print("{}");
print($data);

?>
