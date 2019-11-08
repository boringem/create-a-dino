<?php
// Report all errors
error_reporting(-1);

require_once 'connect.php';

// Sets the variables and ensures they can be safely inserted into the database
$InsertUsername = mysqli_real_escape_string($conn, $_POST['username']);
$InsertFirstName = mysqli_real_escape_string($conn, $_POST['fname']);
$InsertLastName = mysqli_real_escape_string($conn, $_POST['lname']);
$InsertEmail = mysqli_real_escape_string($conn, $_POST['email']);
$InsertPassword = mysqli_real_escape_string($conn, $_POST['password']);

// Encrypts the password
require_once 'password.php';
$SecurePassword = password_hash($InsertPassword, PASSWORD_DEFAULT);

$username = strip_tags($InsertUsername);
$fname = strip_tags($InsertFirstName);
$lname = strip_tags($InsertLastName);
$email = strip_tags($InsertEmail);
$SecurePassword = strip_tags($SecurePassword);

// Creates the query 
$sql = "INSERT INTO Users (UserNum, Username, UserFName, UserLName, UserEmail, UserPassword, UserApproved)
VALUES (DEFAULT, '$username', '$fname', '$lname', '$email', '$SecurePassword', DEFAULT)";
// Executes the query
if ($conn->query($sql) === TRUE) {
  echo "<script> window.location.assign('signin.html'); </script>";
} else {
    echo "Uh oh! There was an error getting you back to the mesozoic era. Please try again later." . $conn->error; 
}


// Closes the connection
$conn->close();
?>