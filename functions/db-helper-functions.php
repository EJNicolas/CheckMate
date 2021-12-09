<?php

  $db = mysqli_connect("localhost", "root", "", "chess-games");
  if($db->connect_errno) {
      $msg = "Database connection failed: ";
      $msg .= mysqli_connect_error();
      $msg .= " (" . mysqli_connect_errno() . ")";
      exit($msg);
  }

  function getUserEmail($username){
    global $db;
    $queryString = "SELECT email FROM users WHERE username = '$username'";
    $result = $db->query($queryString);
    if($result){
      if(!($result->num_rows == 0)){
        while($row = $result->fetch_assoc()){
          $email = $row['email'];
        }
      }
      return $email;
    }

  }
?>
