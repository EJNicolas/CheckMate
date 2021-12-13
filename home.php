<?php
  session_start();
  $page = "home.php";
  include("header.php");
  $db = mysqli_connect("localhost", "root", "", "chess-games");
  if($db->connect_errno) {
      $msg = "Database connection failed: ";
      $msg .= mysqli_connect_error();
      $msg .= " (" . mysqli_connect_errno() . ")";
      exit($msg);
  }

  echo "<h1>Home</h1>";
  // $onlineStatus = "onlineStatus";
  // if(!isset($_COOKIE[$onlineStatus])) {
  //   echo "Cookie named '" . $onlineStatus . "' is not set!";
  // } else {
  //   echo "Cookie '" . $onlineStatus . "' is set!<br>";
  //   if($_COOKIE[$onlineStatus]=="TRUE"){
  //     echo "Cookie '" . $onlineStatus . "' is true!<br>";
  //   }
  // }

  //We want to have a match of the day and we want it to be random each day. Since we randomly took out rows in our database, we cant just do random number within our range since it may give us a non-existant row. Current idea is to use a random offset with limit = 1

  echo "<h2>Online Users</h2>";
  echo "<ul id='online-users-list'>";
  echo "</ul>";
  //include("checkonlineusers.php");

  include("footer.php");
?>
