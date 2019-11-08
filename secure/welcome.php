<?php // continue.php
  session_start();

  if (isset($_SESSION['username'])) {
    $firstname = htmlspecialchars($_SESSION['firstname']); 
  } else echo "<script> window.location.assign('uhoh.html'); </script>";

  // Otherwise, do nothing.
?>
<!DOCTYPE HTML>
<html>
<head>
  <title>Welcome</title>
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />    
  <link href="../styles.css" rel="stylesheet" type="text/css">  
  <link href="https://fonts.googleapis.com/css?family=Abril+Fatface" rel="stylesheet">
  <!-- This loads Foundation CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation.min.css" rel="stylesheet" type="text/css">
  <!-- This loads jQuery JavaScript (this must be before the Foundation js below) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <!-- This loads Foundation JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/js/foundation.min.js"></script>
  <!-- Finally, this starts foundation for everything on the page -->
  <script type="text/javascript">
	   $(document).ready(function() {
		 $(document).foundation();
	   });
   </script>
</head>
<body>
    <section class="grid-x grid-padding-x welcome-hero">
        <nav>
            <div class="medium-4 cell">
                <ul class="horizontal menu secure">
                    <li><a href="create.php">create a dino</a></li>
                    <li><a href="dinos.php">your dinos</a></li>
                    <li><a href="profile.php">your profile</a></li>
                    <li><a href="logout.php">sign out</a></li>
                </ul>
            </div>
        </nav>
        <div class="medium-12 cell text-center secure">
            <h1>Welcome, <?php echo $firstname ?> </h1>
        </div>
    </section>
    </body>
</html>