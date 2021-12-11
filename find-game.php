<?php
include("header.php");
  $db = mysqli_connect("localhost", "root", "", "chess-games");
  if($db->connect_errno) {
      $msg = "Database connection failed: ";
      $msg .= mysqli_connect_error();
      $msg .= " (" . mysqli_connect_errno() . ")";
      exit($msg);
  }
if( isset($_POST['eloStart'])) $eloStart=$_POST['eloStart'];
if( isset($_POST['eloEnd'])) $eloEnd=$_POST['eloEnd'];
if( isset($_POST['timeStart'])) $timeStart=$_POST['timeStart'];
if( isset($_POST['timeAdd'])) $timeAdd=$_POST['timeAdd'];
if( isset($_POST['searchConditions'])) $searchConditions=$_POST['searchConditions'];
if( isset($_POST['hardBounds'])) $searchConditions=$_POST['hardBounds'];
//https://stackoverflow.com/questions/25718856/php-best-way-to-display-x-results-per-page


//uncomment the lines below when we figure out how we want to do pages, limit to 10 for now for loading purposes
// $page = $_GET['p']; //track the page number
$limit = 10;
// $page = ($page - 1) * $limit;
  ?>
  <h1>Query</h1>
    <form action="find-game.php" method="post">
      <table>
        <tr><td>
        <h2>Select Search Conditions</h2>
          <table>
            <tr><td>Elo Range:
            <input type="text" size="4" name="eloStart"> To: <input type="text" size="4"name="eloEnd"></td></tr>
            <!-- <td><input type="checkbox" name="searchConditions[]" value="hardBounds">Hard Bounds </td></tr> -->
            <tr><td>Time Control: <input type="text" size="4" name="timeStart"> + <input type="text" size="4"name="timeAdd"></td></tr>
          </table>
          <input type="submit">
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
      //where portion
      if(isset($eloStart) && isset($eloEnd)){
      	//Split into multiple lines for clarity
          //fix this XOR
          $query .= " WHERE ((WhiteElo >= " .$eloStart ." OR BlackElo >=" . $eloStart;
          $query .= " ) AND (WhiteElo <= " .$eloEnd ." OR BlackElo <=" . $eloEnd ."))" ;
      }

      if(isset($timeStart) && isset($timeAdd)){
        //Split into multiple lines for clarity
          $query .= " AND (TimeControl = '" .$timeStart ."+" . $timeAdd ."')";
      }
        //ASK LEO ABOUT IF PAGING SHOULD HAPPEN AS A QUERY OR IN HTML. IF IN HTML HOW DO WE DO IT -------------
      	$query .= " LIMIT ".$limit." OFFSET "."0;";
      	//to add pages, above line is set to offset 0 instead of page number.
      	//Order by id makes results load faster
      	// $query .= ." ORDER BY id LIMIT ".$limit." OFFSET ".$page ;
      echo $query;
    ?>
    <h1>Result</h1>
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

            	echo "<tr> <td><a href=\"match-details.php\?id=".$row['id']."\">" . $row['TimeControl']." ".$row['Event'] . $row['Termination']."" . " <br>";
            	echo "" . $row['White'] ."  VS  " . $row['Black'] . " <br>";
            	echo " " . $row['WhiteElo'] ." ".$row['Result']." " . $row['BlackElo'] . "</td></tr>";


            	echo "</tr>";
            	echo "  </tr></table>";
              }
            }
            // echo " </td></tr></table>";

          mysqli_free_result($results);
          mysqli_stmt_close($statement);

        }
      ?>
  <?php
  include("footer.php");
  ?>
