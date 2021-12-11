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
if( isset($_GET['id'])) $id=$_GET['id'];
if( isset($_POST['commentMessage'])) $commentMessage=$_POST['commentMessage'];
if( isset($_SESSION['email'])){
          $name = $_SESSION['username'];
          $email = $_SESSION['email'];
          // echo "Signed in as: " . $name . "</br>";
}

$limit = 10;
// $page = ($page - 1) * $limit;
  ?>
    <form action="match-details.php" method="get">
    </form>
    <?php

    $query='';
       $query = "SELECT *";

      //from portion
        $query .= " FROM new_chess_data ";
      //where portion
        $query .= "WHERE id = '" . $id."';";

    ?>
    <h1>Match Details</h1>
      <?php
        if(!empty($query)){
          $statement = mysqli_prepare($db, $query);
          if(!$statement) {
            die("Error is:". mysqli_error($db) );
          }
          mysqli_stmt_execute($statement);
          $results = mysqli_stmt_get_result($statement);
          if(mysqli_num_rows($results) != 0) {
            
            //make only the useful searchConditions
            // echo "<table><tr><td><a href=\"match-details.php\"";

            while($row = mysqli_fetch_assoc($results)) {
              //only check if the key exists.
            	echo "<table><tr>"; //making new tables every link on purpose
            	//this way the table id can be set/styled/clicked on
            	echo "<tr>";

              echo "<tr><td> Event: " .$row['Event'] ." Result: " .$row['Result'] ." TimeControl: ".$row['TimeControl'] ." Termination: " .$row['Termination'] ."</td></tr>";
              echo "<tr><td> White: " .$row['White'] ." Elo: " .$row['WhiteElo'] ."   ";
              echo " Black: " .$row['Black'] ." Elo: " .$row['BlackElo'] ." </td></tr>";
              echo "<tr><td> ECO: " .$row['ECO'] ." Opening: " .$row['Opening'] ."</td></tr>";
              echo "<tr><td> Moves: " .$row['AN'] ."</td></tr>";


            	echo "</tr>";
            	echo "  </tr></table>";
              }
            }
            // echo " </td></tr></table>";
            
          mysqli_free_result($results);
          mysqli_stmt_close($statement);

        }
      ?>
      <h2>Comments</h2>
      <?php
      if (isset($_SESSION['email'])){
        echo "<form action=\"match-details.php?id=$id\" method=\"post\">
          <table>
            <tr><td>Add Comment
              <input type=\"textarea\" rows=\"4\" cols=\"30\" name=\"commentMessage\">
            </td></tr>
          </table>
          <input type=\"submit\">
        </form>";
      }
        ?>
      <?php
      // echo $email;
      if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $dateTime = date("Y-m-d  H:i:s");
        $insertStringA = "INSERT INTO comment_message(username, dateTime, message) VALUES ('$email', '$dateTime', '$commentMessage')";
        // echo $insertStringA;
        if(!empty($insertStringA)){
            $insertAStatement = mysqli_prepare($db, $insertStringA);
            if(!$insertAStatement) {
              die("Error is:". mysqli_error($db) );
            }
            mysqli_stmt_execute($insertAStatement);
            $cid = $db->insert_id;
            // $AResults = mysqli_stmt_get_result($insertAStatement);
            // if($AResults) { 
            //   //make only the useful searchConditions
            //   echo "message";
            //     //only check if the key exists.
            //   }
            // // mysqli_free_result($AResults);
            mysqli_stmt_close($insertAStatement);

          }

        $insertStringB = "INSERT INTO comments(email, cid, gameId) VALUES ( '$email', '$cid', '$id')";
        if(!empty($insertStringB)){
            $insertBStatement = mysqli_prepare($db, $insertStringB);
            if(!$insertBStatement) {
              die("Error is:". mysqli_error($db) );
            }
            mysqli_stmt_execute($insertBStatement);
            mysqli_stmt_close($insertBStatement);

          }
        }


      $commentQuery='';
       $commentQuery = "SELECT *";

      //from portion
        $commentQuery .= " FROM comments, comment_message ";
      //where portion
        $commentQuery .= "WHERE comments.gameId = '" . $id;

        $commentQuery .= "' ORDER BY comment_message.dateTime ;" ;

          //print the comments for this game

        if(!empty($commentQuery)){
          $commentStatement = mysqli_prepare($db, $commentQuery);
          if(!$commentStatement) {
            die("Error is:". mysqli_error($db) );
          }
          mysqli_stmt_execute($commentStatement);
          $commentResults = mysqli_stmt_get_result($commentStatement);
          if(mysqli_num_rows($commentResults) != 0) {
            
            //make only the useful searchConditions
            while($commentRow = mysqli_fetch_assoc($commentResults)) {
              //only check if the key exists.
              echo "<table><tr>";
              echo "<tr><td>" .$commentRow['username']."</td>";
              echo "<tr><td>" .$commentRow['dateTime']."</td>";
              echo "<tr><td>" . $commentRow['message'] ."</td></tr>"; 
              echo "</tr>";
              echo "  </tr></table>";
              }
            }
            // echo " </td></tr></table>";
            
          mysqli_free_result($commentResults);
          mysqli_stmt_close($commentStatement);

        }
      ?>
  <?php
  include("footer.php");
  ?>