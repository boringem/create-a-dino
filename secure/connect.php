<?php
  $db_hostname = 'localhost';
  $db_database = 'id11519244_project';
  $db_username = 'id11519244_projectadmin';
  $db_password = 'mv1430@#!';
  
  $conn = new mysqli($db_hostname, $db_username, $db_password, $db_database);
  
  if($conn->connect_error) die($conn->connect_error);
?>