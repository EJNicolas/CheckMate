<?php
  session_start();
  include("header.php");
  $db = mysqli_connect("localhost", "root", "", "chess-games");
  if($db->connect_errno) {
      $msg = "Database connection failed: ";
      $msg .= mysqli_connect_error();
      $msg .= " (" . mysqli_connect_errno() . ")";
      exit($msg);
  }

  if(isset($_SESSION['email'])){
    $email = $_SESSION['email'];
    $queryString = "SELECT username, player_elo, creation_date, picture_id FROM users WHERE email = '$email'";
    $result = $db->query($queryString);
    while($row = $result->fetch_assoc()){
      $username = $row['username'];
      $elo = $row['player_elo'];
      $date = $row['creation_date'];
      $picture = $row['picture_id'];
    }
  }
  else {
    header("Location: login.php");
  }



  echo "<h1>$username</h1>";
  echo "<p>Your profile picture should be: $picture</p>";
  echo "<p>Account created on: $date</p>";
  echo "<p>ELO $date</p>";


  include("footer.php");
?>
