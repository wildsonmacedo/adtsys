<?php

$servername = "172.31.8.132";
$username = "root";
$password = "senha.00995";
$dbname = "basedb"; 

// Create connection
$conn= mysqli_connect($servername,$username,$password,$dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
echo "Connected Successfully OKOK ok 4444 !!!.";
?>
