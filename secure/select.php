<?php
// report all errors
error_reporting(-1);

$json = preProcess();

session_start();

if (isset($_SESSION['username'])) {
    $firstname = htmlspecialchars($_SESSION['firstname']); 
    $usernum = intval($_SESSION['usernum']);
    $username = $_SESSION['username'];
} else echo "<script> window.location.assign('uhoh.html'); </script>";
require_once 'connect.php';
require_once 'pretty_json.php';

submitQuery($conn, $json);

$conn->close();

/**
 * Check the form before connecting to the database
 */
function preProcess() {
  $json = array();
  $errors = array();
  
  // an input name is required
  array_key_exists('menu',$_REQUEST)
  and $menu = $_REQUEST['menu']
  or $errors['menu'] = 'missing value';
  switch($menu) {
    case 'petName':
    case 'typeId':
      break;
      
    default:
      $errors['menu'] = 'incorrect value: ' . $menu;
  };

  // a search term is required
  array_key_exists('search',$_REQUEST)
  and $search = $_REQUEST['search']
  or $errors['search'] = 'missing value';
  
  $json['errors'] = $errors;
  
  $json['submitted'] = array(
    'menu' => $menu,
    'search' => $search
  );;
  
  if(count($errors) > 0) {
    die(pretty_encode($json) . "\n");
  }
  
  return $json;
}

/**
 * Check the form before connecting to the database
 */
function submitQuery($conn, $json) {
  // print_r($json);
  $usernum = intval($_SESSION['usernum']);
  $columns = '';
  $values = '';
  $comma = '';    // omit the first comma
  
  $raw_menu = $json['submitted']['menu'];
  $menu = $conn->escape_string($raw_menu);
  $raw_search = $json['submitted']['search'];
  $search = $conn->escape_string($raw_search);
  if($menu != $raw_menu || $search != $raw_search) {
    $json['escaped'] = array();
    $json['escaped']['menu'] = $menu;
    $json['escaped']['search'] = $search;
  }
  if($menu === 'typeId'){
    if(strpos($search, 'rex') !== false || strpos($search, 'tyrannosaurus') !== false){
        $search_int = 2;
    }
    if(strpos($search, 'brontosaurus') !== false){
        $search_int = 1;
    }
    if(strpos($search, 'Brontosaurus') !== false){
      $search_int = 1;
  }
    if(strpos($search, 'bird') !== false){
        $search_int = 3;
    }
    if(strpos($search, 'tops') !== false){
        $search_int = 4;
    }
    $query = "SELECT Dinosaurs.*, d.breedName, d.breedEra, d.breedFact FROM Dinosaurs AS Dinosaurs JOIN dinosaurdetails AS d ON Dinosaurs.typeId = d.typeId WHERE UserNum = '$usernum' AND d.$menu = '$search_int' AND Dinosaurs.$menu = '$search_int';";
} 
else {
      $query = "SELECT * FROM Dinosaurs WHERE UserNum = '$usernum' AND $menu like '%$search%';";
  }
    $json['query'] = $query;
    $json['variable'] = $usernum;
  
    $result = $conn->query($query);
    if($result == null) {
        $json['errors'] = array(
        "MySQL error: " . $conn->error,
        "Query: " . $query,
        );
        die(pretty_encode($json) . "\n");
    }
    $json['result'] = array();
    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $json['result'][] = $row;
    }
    $json['success'] = true;
    print(pretty_encode($json) . "\n");

}