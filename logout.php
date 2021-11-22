<?php
  session_start();
  $db = mysqli_connect("localhost", "root", "", "chess-games");
  if($db->connect_errno) {
      $msg = "Database connection failed: ";
      $msg .= mysqli_connect_error();
      $msg .= " (" . mysqli_connect_errno() . ")";
      exit($msg);
  }

  $email = $_SESSION['email'];
  $queryString = "UPDATE users SET online_status = FALSE WHERE email = '$email'";
  $result = $db->query($queryString);
  unset($_SESSION['email']);
  unset($_SESSION['username']);
  setcookie("onlineStatus",time()-1);

  header("Location: login.php");
  // include("header.php");
  // include("footer.php");

?>
