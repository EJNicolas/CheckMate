<?php
  session_start();
  $page = "home.php";
  include("header.php");
  $db = mysqli_connect("localhost", "root", "", "chess-games");
  if($db->connect_errno) {
      $msg = "Database connection failed: ";
      $msg .= mysqli_connect_error();
      $msg .= " (" . mysqli_connect_errno() . ")";
      exit($msg);
  }

  echo "<h1>Home</h1>";
  // $onlineStatus = "onlineStatus";
  // if(!isset($_COOKIE[$onlineStatus])) {
  //   echo "Cookie named '" . $onlineStatus . "' is not set!";
  // } else {
  //   echo "Cookie '" . $onlineStatus . "' is set!<br>";
  //   if($_COOKIE[$onlineStatus]=="TRUE"){
  //     echo "Cookie '" . $onlineStatus . "' is true!<br>";
  //   }
  // }

  //We want to have a match of the day and we want it to be random each day. Since we randomly took out rows in our database, we cant just do random number within our range since it may give us a non-existant row. Current idea is to use a random offset with limit = 1

  echo "<h3>Featured Game</h3>";
    $offset = rand(0,762813);
    $query = "SELECT * FROM new_chess_data LIMIT 1 OFFSET " .$offset .";";
    if(!empty($query)){
          $statement = mysqli_prepare($db, $query);
          if(!$statement) {
            die("Error is:". mysqli_error($db) );
          }
          mysqli_stmt_execute($statement);
          $results = mysqli_stmt_get_result($statement);
          if(mysqli_num_rows($results) != 0) {
            while($row = mysqli_fetch_assoc($results)) {
              //only check if the key exists.
              echo "<table><tr>"; //making new tables every link on purpose
              echo "<td><a href=\"match-details.php?id=".$row['id']."\"> Go To Game:</td>";
              echo "<tr>";
              echo "<tr><td> Event: " .$row['Event'] ." Result: " .$row['Result'] ." TimeControl: ".$row['TimeControl'] ." Termination: " .$row['Termination'] ."</td></tr>";
              echo "<tr><td> White: " .$row['White'] ." Elo: " .$row['WhiteElo'] ."   ";
              echo " Black: " .$row['Black'] ." Elo: " .$row['BlackElo'] ." </td></tr>";
              echo "<tr><td> ECO: " .$row['ECO'] ." Opening: " .$row['Opening'] ."</td></tr>";
              echo "</tr>";
              echo "  </tr></table>";
              }
            }
          mysqli_free_result($results);
          mysqli_stmt_close($statement);

        }

  echo "<h2>Online Users</h2>";
  echo "<ul id='online-users-list'>";
  echo "</ul>";
  //include("checkonlineusers.php");

  include("footer.php");
?>
