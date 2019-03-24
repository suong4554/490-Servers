<?php


$servername = "localhost";
$username = "root";
$password = "root";
$project = "testdb";


$db = mysqli_connect($servername, $username, $password , $project);
if (mysqli_connect_errno())
  {
          $message = "Failed to connect to MySQL: " . mysqli_connect_error();
          echo $message;
          error_log($message);
          exit();
  }

mysqli_select_db( $db, $project );

?>
