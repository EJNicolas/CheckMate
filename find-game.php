<?php
include("header.php");
  $db = mysqli_connect("localhost", "root", "", "new_chess_data");
  if($db->connect_errno) {
      $msg = "Database connection failed: ";
      $msg .= mysqli_connect_error();
      $msg .= " (" . mysqli_connect_errno() . ")";
      exit($msg);
  }
if( isset($_POST['eloStart'])) $eloStart=$_POST['eloStart'];
if( isset($_POST['eloEnd'])) $eloEnd=$_POST['eloEnd'];
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
            <tr><td>Elo Range:<br>
            From: <input type="text" size="4" name="eloStart"> To: <input type="text" size="4"name="eloEnd"></td></tr>
            <td><input type="checkbox" name="searchConditions[]" value="hardBounds">Hard Bounds </td></tr>
          </table>
          <input type="submit">
    </td>
    </tr>
      </table>
    </form>
    <?php

    $query='';
      //Select
      $query = "SELECT Event, White, Black, Result, UTCDate, UTCTime, WhiteElo, BlackElo, WhiteRatingDiff, BlackRatingDiff, ECO, Opening, TimeControl, Termination";

      //from portion
      // if(!empty($searchConditions)){
        $query .= " FROM new_chess_data ";
      // }

      //where portion
      if(!empty($ordNum)){
          $query .= " WHERE new_chess_data.orderNumber=" .$ordNum;
      }
      if(!empty($eloStart) && !empty($eloEnd)){
      	//Split into multiple lines for clarity

          $query .= " WHERE (WhiteElo >= " .$eloStart ." OR BlackElo >=" . $eloStart;
          $query .= " ) AND (WhiteElo <= " .$eloEnd ." OR BlackElo <=" . $eloEnd .")" ;
      }
      	$query .= " LIMIT ".$limit." OFFSET "."0;";
      	//to add pages, above line is set to offset 0 instead of page number.
      	//Order by id makes results load faster
      	//$query .= "LIMIT ".$limit." OFFSET ".$page." ORDER BY id ;";
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

            while($row = mysqli_fetch_assoc($results)) {
              //only check if the key exists.
            	echo "<table><tr>"; //making new tables every link on purpose
            	//this way the table id can be set/styled/clicked on
            	echo "<tr>";

            	echo "<tr> <td>" . $row['TimeControl']." </td><td>".$row['Event'] . $row['Termination']."</td><td>" . "</td></tr>";
            	echo "<tr> <td>" . $row['White'] ." </td> <td>VS</td> <td> " . $row['Black'] . "</td></tr>";
            	echo "<tr> <td>" . $row['WhiteElo'] ." </td><td>"."</td><td>" . $row['BlackElo'] . "</td></tr>";
            	echo "<tr> <td>" . $row['ECO'] ." </td><td>".$row['Result'] ."</td><td>" . $row['Opening'] . "</td></tr>";


            	echo "</tr>";
            	echo "  </tr></table>";
              }
            }
            
          mysqli_free_result($results);
          mysqli_stmt_close($statement);

        }
      ?>
  <?php
  include("footer.php");
  ?>