<?php
  session_start();
  include("onlineheader.php");
  $db = mysqli_connect("localhost", "root", "", "chess-games");
  if($db->connect_errno) {
      $msg = "Database connection failed: ";
      $msg .= mysqli_connect_error();
      $msg .= " (" . mysqli_connect_errno() . ")";
      exit($msg);
  }

  echo "<h1>Home</h1>";
  $onlineStatus = "onlineStatus";
  // if(!isset($_COOKIE[$onlineStatus])) {
  //   echo "Cookie named '" . $onlineStatus . "' is not set!";
  // } else {
  //   echo "Cookie '" . $onlineStatus . "' is set!<br>";
  //   if($_COOKIE[$onlineStatus]=="TRUE"){
  //     echo "Cookie '" . $onlineStatus . "' is true!<br>";
  //   }
  // }

  echo "<h2>Online Users</h2>";
  echo "<ul id='online-users-list'>";
  echo "</ul>";
  include("checkonlineusers.php");

  include("footer.php");
?>
