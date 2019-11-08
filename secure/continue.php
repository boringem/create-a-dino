<?php // continue.php
  session_start();

  if (!isset($_SESSION['Username'])) {
    $json = array();
    $json['success'] = false;
    $json['errors'] = 'Empty User Name';
    $json['notice'] = 'You must be logged in to use this form';
    $json['refer'] = 'login.html';
    require_once 'pretty_json.php';
    die(pretty_encode($json) . "\n"); 
  }

  // Otherwise, do nothing.
?>