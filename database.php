<?php
  $servername = "localhost"; 
  $username = "samuel"; //your MySQL username
  $password = "Sam@4991"; //your MySQL password
  $dbname = "myDb1"; //the name of your database
  
  // Create a connection
  $mysqli = new mysqli($servername, $username, $password, $dbname);
  
  // Check connection

  if ($mysqli->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  
  return $mysqli;
  
?>