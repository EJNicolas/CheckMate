<?php
  session_start();
  $db = mysqli_connect("localhost", "root", "", "chess-games");
  if($db->connect_errno) {
      $msg = "Database connection failed: ";
      $msg .= mysqli_connect_error();
      $msg .= " (" . mysqli_connect_errno() . ")";
      exit($msg);
  }

  if(isset($_SESSION['email'])){
    $email = $_SESSION['email'];
    $queryString = "UPDATE users SET online_status = FALSE WHERE email = '$email'";
    $result = $db->query($queryString);
  }
  mysqli_free_result($result);
?>
