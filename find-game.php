<?php
session_start();
$page = "find-game.php";
include("header.php");
  $db = mysqli_connect("localhost", "root", "", "chess-games");
  if($db->connect_errno) {
      $msg = "Database connection failed: ";
      $msg .= mysqli_connect_error();
      $msg .= " (" . mysqli_connect_errno() . ")";
      exit($msg);
  }
  if( isset($_GET['pg'])){
   $pg = (int)htmlspecialchars($_GET['pg']);
   if($pg<0){
    $pg=1;
   }
  }
  else{
    $pg = 1;
  }
  $prev = $pg -1;
  $next = $pg +1;
  if( isset($_SESSION['email'])){
          $name = htmlspecialchars($_SESSION['username']);
          $email = htmlspecialchars($_SESSION['email']);
          // echo "Signed in as: " . $name . "</br>";
}
if( isset($_POST['byUser'])) $byUser=htmlspecialchars($_POST['byUser']);
if( isset($_POST['eloStart'])) $eloStart=htmlspecialchars($_POST['eloStart']);
if( isset($_POST['eloEnd'])) $eloEnd=htmlspecialchars($_POST['eloEnd']);
if( isset($_POST['timeStart'])) $timeStart=htmlspecialchars($_POST['timeStart']);
if( isset($_POST['timeAdd'])) $timeAdd=htmlspecialchars($_POST['timeAdd']);
if( isset($_POST['searchOpen'])) $searchOpen=htmlspecialchars($_POST['searchOpen']);
if( isset($_POST['searchECO'])) $searchECO=htmlspecialchars($_POST['searchECO']);
if( isset($_POST['eventType'])) $eventType=htmlspecialchars($_POST['eventType']);
if( isset($_POST['terminationType'])) $terminationType=htmlspecialchars($_POST['terminationType']);
if( isset($_POST['matchResult'])) $matchResult=htmlspecialchars($_POST['matchResult']);

//old query if set, without page number
// if( isset($_POST['oldQuery'])) $oldQuery=htmlspecialchars($_POST['oldQuery']);

// if( isset($_POST['searchConditions'])) $searchConditions=$_POST['searchConditions'];

//https://stackoverflow.com/questions/25718856/php-best-way-to-display-x-results-per-page


//uncomment the lines below when we figure out how we want to do pages, limit to 10 for now for loading purposes
// $page = $_GET['p']; //track the page number
$limit = 10;
$pageNumber = ($pg - 1) * $limit;
  ?>
  <h1>Query</h1>
      <form action="find-game.php" method="post">
      <table>
        <tr><td>
        <h2>Select Search Conditions</h2>
          <table>
            <tr><td>Search By User: <input type="text" size="16" name="byUser"> </td></tr>
            <tr><td>Elo Range: <input type="number" size="4" name="eloStart"> To: <input type="number" size="4"name="eloEnd"></td></tr>
            <!-- <td><input type="checkbox" name="searchConditions[]" value="hardBounds">Hard Bounds </td></tr> -->
            <tr><td>Event:
              <select name="eventType">
                <option value="ALL"></option>
                <option value="Blitz">Blitz</option>
                <option value="Bullet">Bullet</option>
                <option value="Classical">Classical</option>
              </select>
              </td>
            <td>Time Control: <input type="number" size="4" name="timeStart"> + <input type="number" size="4"name="timeAdd"></td></tr>

            <tr><td>Termination:
              <select name="terminationType">
                <option value="ALL"></option>
                <option value="Normal">Normal</option>
                <option value="Time forfeit">Time forfeit</option>
                <option value="Abandoned">Abandoned</option>
                <option value="Rules infraction">Rules infraction</option>
              </select>
              </td>
              <td>Result:
              <select name="matchResult">
                <option value="ALL"></option>
                <option value="whiteWin">White Win</option>
                <option value="blackWin">Black Win</option>
                <option value="draw">Draw</option>
                <option value="abandoned">Abandoned</option>
              </select>
              </td></tr>

            <tr><td>Opening: <!-- Most common openings: https://www.thesprucecrafts.com/most-common-chess-openings-611517 -->
              <select name="searchOpen">
                <option value="ALL"></option>
                <option value="Ruy Lopez">Ruy Lopez</option>
                <option value="Italian">Italian</option>
                <option value="Sicilian">Sicilian</option>
                <option value="French">French</option>
                <option value="Caro-Kann">Caro-Kann</option>
                <option value="Pirc">Pirc</option>
                <option value="Queen's Gambit">Queen's Gambit</option>
                <option value="Indian">Indian</option>
                <option value="English">English</option>
                <option value="Reti">Reti</option>
              </select>
              </td>
              <td>ECO: <input type="text" size="2" name="searchECO"></td></tr>
          </table>
          <input type="submit" class='button'>
    </td>
    </tr>
      </table>
    </form>
    <?php

    $query='';
      //Select
        $query = "SELECT Event, White, Black, Result, WhiteElo, BlackElo, TimeControl, Termination, id";
      // $query = "SELECT Event, White, Black, Result, UTCDate, UTCTime, WhiteElo, BlackElo, WhiteRatingDiff, BlackRatingDiff, ECO, Opening, TimeControl, Termination";

      //from portion
        $query .= " FROM new_chess_data ";
        //function to allow AND's to be universal but inefficent (all id's are above 0)
        $query .= "  WHERE id > 0";
      //where portion
      if(!empty($byUser)){
        //LIKE to get Partial Strings
          $query .= " AND ((White LIKE '%" .$byUser ."%' OR Black LIKE '%" . $byUser ."%'))";
      }
      if(!empty($eloStart) && !empty($eloEnd)){
      	//Split into multiple lines for clarity
          //fix this XOR
          $query .= " AND ((WhiteElo >= " .$eloStart ." OR BlackElo >=" . $eloStart;
          $query .= " ) AND (WhiteElo <= " .$eloEnd ." OR BlackElo <=" . $eloEnd ."))" ;
      }
      if(!empty($eventType) && ($eventType !='ALL')){
        //LIKE to get Partial Strings
          $query .= " AND (Event LIKE '%" .$eventType ."%')";
      }
      if(!empty($timeStart) && !empty($timeAdd)){
        //Split into multiple lines for clarity
          $query .= " AND (TimeControl = '" .$timeStart ."+" . $timeAdd ."')";
      }
      if(!empty($terminationType) && ($terminationType !='ALL')){
          $query .= " AND (Termination = '" .$terminationType ."')";
      }
      if(!empty($matchResult) && ($matchResult !='ALL')){
        //Filter within match results
          if($matchResult == 'whiteWin'){
          $query .= " AND (Result = '1-0')";
        }
        if($matchResult == 'blackWin'){
          $query .= " AND (Result = '0-1')";
        }
        if($matchResult == 'draw'){
          $query .= " AND (Result = '1/2-')";
        }
        if($matchResult == 'abandoned'){
          $query .= " AND (Result = '*')";
        }
      }
      if(!empty($searchOpen) && ($searchOpen !='ALL')){
        //LIKE to get Partial Strings
          $query .= " AND (Opening LIKE '%" .$searchOpen ."%')";
      }
      if(!empty($searchECO) && ($searchECO !='ALL')){
        //LIKE to get Partial Strings
          $query .= " AND (ECO LIKE '%" .$searchECO ."%')";
      }
        // $tempQuery = $query;
      	// $query .= " LIMIT ".$limit." OFFSET ".$pageNumber .";";

      // echo $query;
    ?>
    <h1>Result</h1>
      <?php

      // $perPage = mysql_query("$query LIMIT $pageNumber,$limit");
      $perPage = $query;
      $perPage .= " LIMIT ".$limit." OFFSET ".$pageNumber .";";

        if(!empty($query)){
          $statement = mysqli_prepare($db, $query);
          if(!$statement) {
            die("Error is:". mysqli_error($db) );
          }
          mysqli_stmt_execute($statement);
          $results = mysqli_stmt_get_result($statement);


          if(mysqli_num_rows($results) != 0) {
            $res = mysqli_fetch_assoc($results);
            if(!isset($res)){
              $numResults = $res[0];
              $totalpages = ceil($numResults / $limit);
            }

            //make only the useful searchConditions
            // echo "<table><tr><td><a href=\"match-details.php\"";
            $resPage = mysqli_prepare($db, $perPage);
              if(!$resPage){
                die("Error is:". mysqli_error($db) );
              }
              mysqli_stmt_execute($resPage);
              $resultsPaged = mysqli_stmt_get_result($resPage);

              while($row = mysqli_fetch_assoc($resultsPaged)) {
              // while($row = mysqli_fetch_assoc($results)) {
                //only check if the key exists.
              	echo "<table><tr>"; //making new tables every link on purpose
              	//this way the table id can be set/styled/clicked on
              	echo "<tr>";

              	echo "<tr> <td class='hoverTable'><a  href=\"match-details.php?id=".$row['id']."\">" . $row['TimeControl']." ".$row['Event'] . $row['Termination']."" . " <br>";
              	echo "" . $row['White'] ."  VS  " . $row['Black'] . " <br>";
              	echo " " . $row['WhiteElo'] ." ".$row['Result']." " . $row['BlackElo'] . "</td></tr>";


              	echo "</tr>";
              	echo "  </tr></table>";
                }
              if($pg > 1){
                echo " <a class='paging' href='?pg=$prev'><-Previous</a>";
                // echo "<button type='button' id=\"prevPage\" class = \"button\" data-typeQuery = '$tempQuery'>Previous Page</button>";
              }
              echo " <a class='paging' href='?pg=$next'>Next-></a>";
              // echo "<button type='button' id=\"nextPage\" class = \"button\" data-typeQuery = '$tempQuery'>Next Page</button>";
            }
            // echo " </td></tr></table>";

          mysqli_free_result($results);
          mysqli_stmt_close($statement);

        }
      ?>
  <?php
  include("footer.php");
  ?>
