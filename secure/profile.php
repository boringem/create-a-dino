<?php
  session_start();
  require_once('connect.php');

  if (isset($_SESSION['username'])) {
    $firstname = htmlspecialchars($_SESSION['firstname']); 
    $usernum = intval($_SESSION['usernum']);
  } else echo "<script> window.location.assign('uhoh.html'); </script>";

  $query = "SELECT * FROM Dinosaurs WHERE UserNum = '$usernum'";
  $result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Your Profile</title>
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
    <!-- <script src="upload.js"></script> -->
</head>
<body>
    <nav class="grid-x grid-padding-x grid-padding-y">
        <ul class="horizontal menu">
            <li><a href="create.php">create a dino</a></li>
            <li><a href="dinos.php">your dinos</a></li>
            <li><a href="profile.php">your profile</a></li>
            <li><a href="logout.php">sign out</a></li>
        </ul>
    </nav>
    <section class="grid-x grid-padding-y grid-padding-x">
        <div class="medium-12 cell">
            <div class="card" id="profile">
                <div class="grid-x grid-padding-x align-middle">
                    <div class="medium-8 cell card-section text-center">
                        <h1>Good to see you, <?php echo $firstname ?></h1>
                        <span><a href="create.php">New Dinosaur</a></span>
                        <span><a href="dinos.php">Search Dinosaurs</a></span>
                        <span><a href="logout.php">Sign Out</a></span>
                    </div>
                    <div class="medium-4 cell card-section">
                        <img src="../images/uhoh.jpg" alt="placeholder">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="grid-x grid-padding-x grid-padding-y">
        <div class="medium-12 cell">
            <div class="card" id="brontosaurus">
                <div class="grid-x grid-padding-x">
                    <div class="medium-12 cell card-section text-center">
                    <h1>Your Dinosaurs</h1>
                        <?php
                        if ($result->num_rows > 0) {
                            echo "<h4>Here's some more information about your dinosaurs...</h4>";
                            while($row = $result->fetch_assoc()) {
                                $type = intval($row["typeID"]);
                                $newq = "SELECT * FROM dinosaurdetails WHERE typeId = '$type'";
                                $newr = $conn->query($newq);
                                if($newr->num_rows > 0) {
                                    while($rows = $newr->fetch_assoc()) {
                                        $breedName = $rows["breedName"];
                                        $breedEra = $rows["breedEra"];
                                        $breedFact = $rows["breedFact"];
                                    }
                                }
                                $dinosaurName = $row["petName"];
                                echo "<div class='row dinofacts'><span><h4>Pet Name: </h4>
                                $dinosaurName </span><span><h4> Breed: </h4> $breedName 
                                </span><span><h4> Era: </h4> $breedEra </span><span><h4> Fun Fact: </h4> $breedFact</span>
                                </div>";
                            }
                        } else {
                            echo "<h4>Looks like you don't have any dinosaurs yet! Click the links above to adopt one now :)</h4>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>