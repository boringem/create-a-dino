<?php
// Report all errors
error_reporting(-1);

require_once 'connect.php';

// Prepares the insert statement
$stmt = $conn->prepare("INSERT INTO Dinosaurs (dinosaurId, UserNum, petName, typeId) VALUES(DEFAULT, ?, ?, ?)");
$stmt->bind_param("isi", $UserNum, $name, $typenum);

// Sets the variables and ensures they can be safely inserted into the database
$InsertType = $_POST['breeds'];
$InsertName = mysqli_real_escape_string($conn, $_POST['petname']);


if ($InsertType === 'brontosaurus') {
    $typenum = 1;
} 
if ($InsertType === 'trex') {
    $typenum = 2;
} 
if ($InsertType === 'bird') {
    $typenum = 3;
} 
if ($InsertType === 'triceratops') {
    $typenum = 4;
}
$name = strip_tags($InsertName);
session_start();
if(isset($_SESSION['username'])){
    $UserNum = $_SESSION['usernum'];
}

// Executes the statement
$stmt->execute();
printf("%d Successfully added. \n", $stmt->affected_rows);


// Closes the statement and connection
$stmt->close();
$conn->close();
?>