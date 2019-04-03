<?php
$filename = $_POST['file'];

$python = "python " . $filename;

$result = shell_exec($python);

#print("{}");
print($result);

?>
