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
    $check = "SELECT * FROM favourites WHERE email='$email' AND gameId='$id'";
    if(!empty($check)){
        $insertCheck = mysqli_prepare($db, $check);
        if(!$insertCheck) {
          die("Error is:". mysqli_error($db) );
        }
        mysqli_stmt_execute($insertCheck);
        $checkResults = mysqli_stmt_get_result($insertCheck);
          if(mysqli_num_rows($checkResults) != 0) {          
            //make only the useful searchConditions
            $delete = "DELETE FROM favourites WHERE email='$email' AND gameId='$id'";
            // echo $added;
            if(!empty($delete)){
            $insertDelete = mysqli_prepare($db, $delete);
            if(!$insertDelete) {
                die("Error is:". mysqli_error($db) );
            }
            mysqli_stmt_execute($insertDelete);
            mysqli_stmt_close($insertDelete);
        }
            }
        mysqli_free_result($checkResults);
        mysqli_stmt_close($insertCheck);
    }

    

?>