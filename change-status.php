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
    $queryString = "SELECT online_status FROM users WHERE email = '$email'";
    $result = $db->query($queryString);
    $status = $result->fetch_array();
    if($status[0]==FALSE){
      $queryString = "UPDATE users SET online_status = TRUE WHERE email = '$email'";
      setcookie("onlineStatus","TRUE",time()+120);
    }
    else{
      $queryString = "UPDATE users SET online_status = FALSE WHERE email = '$email'";
      setcookie("onlineStatus",time()-1);
    }
    $result = $db->query($queryString);
    header("Location: home.php");
  }
?>
