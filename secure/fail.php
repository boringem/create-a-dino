<?php
  session_start();

  if (isset($_SESSION['username'])) {
    $firstname = htmlspecialchars($_SESSION['firstname']); 
  } else echo "<script> window.location.assign('uhoh.html'); </script>";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Upload Failed</title>
        <link href="../styles.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <h1>Uploads aren't quite working yet...sorry about that. Here's a link back to your <a href="profile.php">profile</a>!</h1>
    </body>
</html>