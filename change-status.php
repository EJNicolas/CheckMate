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
    if(isset($_COOKIE['onlineStatus'])){
      if(!($_COOKIE['onlineStatus'] == "TRUE")){
        $queryString = "UPDATE users SET online_status = TRUE WHERE email = '$email'";
        setcookie("onlineStatus","TRUE",time()+10);
      }
      else{
        $queryString = "UPDATE users SET online_status = FALSE WHERE email = '$email'";
        setcookie("onlineStatus",time()-1);
      }
    }
    else{
      //this is the first time the user goes online, make them online and create the cookie
      $queryString = "UPDATE users SET online_status = TRUE WHERE email = '$email'";
      setcookie("onlineStatus","TRUE",time()+10);
    }
    $result = $db->query($queryString);
    header("Location: home.php");
  }
?>
