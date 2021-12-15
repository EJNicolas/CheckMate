<?php
  $db = mysqli_connect("localhost", "root", "", "chess-games");
  if($db->connect_errno) {
      $msg = "Database connection failed: ";
      $msg .= mysqli_connect_error();
      $msg .= " (" . mysqli_connect_errno() . ")";
      exit($msg);
  }
  session_start();
  if( isset($_GET['id'])) $id=$_GET['id'];
  if( isset($_SESSION['email'])){
          $name = htmlspecialchars($_SESSION['username']);
          $email = htmlspecialchars($_SESSION['email']);
    }
    // echo "<script>console.log('test')</script>";

    $check = "SELECT * FROM favourites WHERE email='$email' AND gameID='$id'";
    if(!empty($check)){
        $insertCheck = mysqli_prepare($db, $check);
        if(!$insertCheck) {
          die("Error is:". mysqli_error($db) );
        }
        mysqli_stmt_execute($insertCheck);
        $checkResults = mysqli_stmt_get_result($insertCheck);
          if(mysqli_num_rows($checkResults) == 0) {
            //make only the useful searchConditions
            $added = "INSERT INTO favourites(email, gameID) VALUES ('$email', '$id')";
            // echo $added;
            if(!empty($added)){
            $insertAdded = mysqli_prepare($db, $added);
            if(!$insertAdded) {
                die("Error is:". mysqli_error($db) );
            }
            mysqli_stmt_execute($insertAdded);
            mysqli_stmt_close($insertAdded);
        }
            }
        mysqli_free_result($checkResults);
        mysqli_stmt_close($insertCheck);
    }



?>
