<?php

$servername = "3.137.3.79";
$username = "root";
$password = "senha.0099";
$dbname = "basedb"; 

// Create connection
$conn= mysqli_connect($servername,$username,$password,$dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
echo "Connected Successfully OKOK ok !!!.";
?>
