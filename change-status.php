<?php
  //initialize session and database connection
  session_start();
  $db = mysqli_connect("localhost", "root", "", "chess-games");
  if($db->connect_errno) {
      $msg = "Database connection failed: ";
      $msg .= mysqli_connect_error();
      $msg .= " (" . mysqli_connect_errno() . ")";
      exit($msg);
  }

  if(isset($_SESSION['email'])){
    //get user's email from session and query the database to see if their online status is true or not
    $email = $_SESSION['email'];
    $queryString = "SELECT online_status FROM users WHERE email = '$email'";
    $result = $db->query($queryString);
    $status = $result->fetch_array();
    //we use a cookie to track if a user is online or not
    if(isset($_COOKIE['onlineStatus'])){
      //switch a user back to online if they are offline
      if(!($_COOKIE['onlineStatus'] == "TRUE")){
        $queryString = "UPDATE users SET online_status = TRUE WHERE email = '$email'";
        setcookie("onlineStatus","TRUE",time()+10);
      }
      //switch a user back to offline if they are online
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
