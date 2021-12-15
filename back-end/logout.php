<?php
  session_start();
  $db = mysqli_connect("localhost", "root", "", "chess-games");
  if($db->connect_errno) {
      $msg = "Database connection failed: ";
      $msg .= mysqli_connect_error();
      $msg .= " (" . mysqli_connect_errno() . ")";
      exit($msg);
  }

  //gets the email from the user's session
  $email = $_SESSION['email'];
  //makes their online status false
  $queryString = "UPDATE users SET online_status = FALSE WHERE email = '$email'";
  $result = $db->query($queryString);
  //unsets everything in the session
  unset($_SESSION['email']);
  unset($_SESSION['username']);
  //makes the online status cookie expire
  setcookie("onlineStatus",time()-1);

  header("Location: ../login.php");

?>
