<?php
  //establish connection with session and database
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

  echo "<h1>CheckMate</h1>";

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
              echo "<a href=\"match-details.php?id=".$row['id']."\"> Go To Game: </a>";
              echo "<table class=\"matchTable\"><tr>";
              echo "<tr><td class=\"tableElem\"> Event: " .$row['Event']  ."TimeControl: ".$row['TimeControl'] ."</td><td class=\"tableElem\"> Termination: " .$row['Termination'] ." Result: " .$row['Result'] ."</td></tr>";
              echo "<tr><td class=\"tableElem\"> White: " .$row['White'] ." Elo: " .$row['WhiteElo'] ."   ";
              echo "</td><td class=\"tableElem\"> Black: " .$row['Black'] ." Elo: " .$row['BlackElo'] ." </td></tr>";
              echo "<tr><td class=\"tableElem\"> ECO: " .$row['ECO'] ."</td><td class=\"tableElem\"> Opening: " .$row['Opening'] ."</td></tr>";
            	echo "  </tr></table>";
              }
            }
          mysqli_free_result($results);
          mysqli_stmt_close($statement);

        }

  //Online users set through online.js
  echo "<h2>Online Users</h2>";
  echo "<ul id='online-users-list'>";
  echo "</ul>";
if (isset($_SESSION['email'])){
  echo "<h2>Favourite Games</h2>";
      @$favQueryA = "SELECT * FROM favourites WHERE email = '" . $email ."'";
        if(!empty($favQueryA)){
          $favStatementA = mysqli_prepare($db, $favQueryA);
          if(!$favStatementA) {
            die("Error is:". mysqli_error($db) );
          }
          mysqli_stmt_execute($favStatementA);
          $favStatementA = mysqli_stmt_get_result($favStatementA);
          if(mysqli_num_rows($favStatementA) != 0) {
            //make only the useful searchConditions
            while($favRowA = mysqli_fetch_assoc($favStatementA)) {
              //only check if the key exists.
              $gameID = $favRowA['gameID'];
              $query = "SELECT * FROM new_chess_data WHERE id = '" . $gameID."';";
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
                         echo "<table class=\"matchTable\"><tr>"; //making new tables every link on purpose
                        //this way the table id can be set/styled/clicked on
                        echo "<tr>";

                        echo "<tr> <td class='hoverTable'><a class=\"tableLink\" href=\"match-details.php?id=".$row['id']."\">Event: ".$row['Event']." " .$row['Result'];
                        echo "<br> Time Control: " . $row['TimeControl']." Termination:     ". $row['Termination']."" . " <br>";
                        echo "White:   " . $row['White'] ." (ELO:" . $row['WhiteElo'].")     VS     Black:   " . $row['Black'] ." (ELO: " . $row['BlackElo']. ") <br>";
                        echo "</td></tr>";

                        echo "</tr>";
                        echo "  </tr></table>";
                        }
                      }
                    mysqli_free_result($results);
                    mysqli_stmt_close($statement);
              }
            }
          }
        }

        }
  include("footer.php");
?>
