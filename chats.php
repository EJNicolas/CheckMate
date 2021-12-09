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

  if(isset($_GET['chatId'])) $chatId = $_GET['chatId'];
  if(isset($_SESSION['email'])){
    $email = $_SESSION['email'];
    $usename = $_SESSION['username'];
  } else header("Location: login.php");

  //check if the user belongs in this chat
  $queryString = "SELECT member1, member2"


  include("footer.php");

?>
