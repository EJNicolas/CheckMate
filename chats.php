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
    $userEmail = $_SESSION['email'];
    $member1 = $_SESSION['username'];
  }
  else header("Location: home.php");

  if(isset($_COOKIE['contactingUser'])){
    $member2 = $_COOKIE['contactingUser'];
  }
  $queryString = "";

  echo "<h1>This chat is for $member1 and $member2</h2>";

  include("footer.php");
?>
