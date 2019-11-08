<?php
$servername = "localhost";
$username = "projectAdmin";
$password = "mv1430@#!";
$dbname = "project";

//Create a new connection
$conn = new mysqli($servername, $username, $password, $dbname);
//Check connection 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//Assign form data to variables
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$email = $_POST["email"];
$password = $_POST["password"];

//Insert the form data into a table
$sql = "INSERT INTO users(firstName, lastName, email, pswd)
VALUES('$fname', '$lname', '$email', MD5('$password'))";
if($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>