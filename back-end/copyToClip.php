<?php
  $db = mysqli_connect("localhost", "root", "", "chess-games");
  if($db->connect_errno) {
      $msg = "Database connection failed: ";
      $msg .= mysqli_connect_error();
      $msg .= " (" . mysqli_connect_errno() . ")";
      exit($msg);
  }   
  if( isset($_GET['id'])) $id=$_GET['id'];
      $iWant =[];
      $query='';
       $query = "SELECT *";
      //from portion
        $query .= " FROM new_chess_data ";
      //where portion
        $query .= "WHERE id = '" . $id."';";

         if(!empty($query)){
          $statement = mysqli_prepare($db, $query);
          if(!$statement) {
            die("Error is:". mysqli_error($db) );
          }
          mysqli_stmt_execute($statement);
          $results = mysqli_stmt_get_result($statement);
          if(mysqli_num_rows($results) != 0) {

            //make only the useful searchConditions
            while($row = mysqli_fetch_assoc($results)) {
              $iWant += ['stringAN' => $row['AN']];
              }
            }
            // echo " </td></tr></table>";
          print(json_encode($iWant));
          mysqli_free_result($results);
          mysqli_stmt_close($statement);

        }
?>