<?php

#deletes file for user 1
$filename = $_POST['filename1'];
if(file_exists($filename)){
    unlink($filename);
}
print("{}");

?>