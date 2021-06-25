<?php

$servername = "172.31.4.135";
$username = "root";
$password = "senha.0099";
$dbname = "basedb"; 

// Create connection
$conn= mysqli_connect($servername,$username,$password,$dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
echo "Connected Successfully cremogema!!!.";
?>
