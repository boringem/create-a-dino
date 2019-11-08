<?php
  /**
    authenticate.php
    Authenticate a user in the database
    @param username the username in the database
    @param password a passsword to be checked 
     against the hashed password for the user
   */

  // produce all output in JSON format.
  $json = array();
  $json['errors'] = array();
  require_once 'pretty_json.php';

  /**
    Terminate the script, close connections, and print error messages.
    @param errObject may be a simple string with an error message,
      or an array including state and error numbers.
   */
  function dieWithError($json, $errObject = null, $conn = null) {
    $json['success'] = false;
    if($errObject) {
      $json['errors'][] = $errObject;
    }
    if(isset($conn) && is_object($conn) 
       && get_class($conn) == 'mysqli') {
      if($thread = mysqli_thread_id($conn)) {
        $conn->kill($thread);
      }
      $conn->close();
    }
    die(pretty_encode($json) . "\n");
  }

  isset($_POST['username']) 
  or dieWithError($json, 'User Name omitted.');
  $raw_username = $_POST['username'];

  isset($_POST['password']) 
  or dieWithError($json, 'Password omitted.');
  $raw_password = $_POST['password'];

  require_once 'connect.php';

  $un_temp = $conn->escape_string($raw_username);
  $json['username'] = $un_temp;
  if($un_temp != $raw_username) {
    $json['raw_username'] = $raw_username;
  }

  $query = "select * from Users where Username = '$un_temp'";
  $json['query'] = $query;

  $result = $conn->query($query);

  if(!$result) {
    dieWithError($json, $conn->error_list, $conn);
  }

  if($result->num_rows == 0) {
    dieWithError($json, "User Name '$un_temp' is unknown.", $conn);
  }

  if($result->num_rows > 1) {
    dieWithError($json, "Internal error (duplicate user name '$un_temp').", $conn);
  }

  $row = $result->fetch_array(MYSQLI_NUM);
  if(!$row) {
    dieWithError($json, $conn->error_list, $conn);
  }

  $hash = $row[5];
  $approved = $row[6];
   //var_dump($approved);
  require_once 'password.php';
  if(!password_verify($raw_password, $hash)) {
    dieWithError($json, "Password for '$un_temp' does not match.", $conn);
  }
  if(!$approved) {
    dieWithError($json, "You are not approved!");
  }
  
  // Success!
  $json['success'] = true;
  session_start();
  $_SESSION['firstname'] = $row[2];
  $_SESSION['username'] = $row[1];
  $_SESSION['usernum'] = $row[0];
  ini_set('session.gc_maxlifetime', 60 * 60 * 24);
  echo "<script> window.location.assign('welcome.php'); </script>";
  // Make session variables available to the Javascript client.
  // WARNING:  This is not always safe.  
  // If you have variables that the server should see 
  // but the client should not, 
  // replace this line with multiple statements, e.g.:
  // $json['session'] = array();
  // $json['session']['key1'] = $_SESSION['key1'];
  // ...
  $json['session'] = $_SESSION;
  //print(pretty_encode($json) . "\n");
?>
