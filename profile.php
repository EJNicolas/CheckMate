<?php
  //connect to session and database
  session_start();
  $page = "profile.php";
  include("header.php");
  include("functions/db-helper-functions.php");
  if(isset($_GET['username'])){
    $username=htmlspecialchars($_GET['username']);
    $email = getUserEmail($username);
  }
  //if there is no username in the url, get the user's information through the session
  else if(isset($_SESSION['username'])){
    $username = htmlspecialchars($_SESSION['username']);
    $email = htmlspecialchars($_SESSION['email']);
  }
  //if there is no username in the url and there is no session, then the user shouldnt be here,
  else{
    header("Location: login.php");
  }

  $queryString = @"SELECT username, player_elo, creation_date, picture_id FROM users WHERE username = '$username'";
  $result = @$db->query($queryString);
  //if no results are found print show statement
  if($result->num_rows == 0){
    echo "<p>User not found</p>";
  }
  //if some results are found, save information to variables and display them
  else{
    //saving data from query
    while($row = $result->fetch_assoc()){
      $username = $row['username'];
      $elo = $row['player_elo'];
      $date = $row['creation_date'];
      $picture = $row['picture_id'];
    }

    //showing information
    //showing the profile picture
    $queryString = "SELECT file_location FROM profile_picture WHERE picture_id = $picture";
    $result = $db->query($queryString);
    while($row = $result->fetch_assoc()){
      $pictureFile = $row['file_location'];
    }
    echo "<img src='$pictureFile' class='profile-picture'>";
    //html for other information
    echo "<div class='profile-information'>";
      echo "<h1 class='profile-username'>$username</h1>";
      echo "<p>Account created on: $date</p>";
      //since inputting an elo is optional, we need to check if a user input anything there
      if(!$elo==0){
        echo "<p>ELO $elo</p>";
      }
    echo "</div>";
  }

  //checks if the session has the same username as on the one in the URL or the session exists and there isnt something in the url
  if( @($_SESSION['username'] == $username) || (isset($_SESSION['username']) && !isset($_GET['username']) )){

    //Updating profile information after pressing submit on the edit profile form
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      if( isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password_confirm'])){
        //retrieve information from POST
        $newUsername = htmlspecialchars($_POST['username']);
        $newPassword = htmlspecialchars($_POST['password']);
        $newPasswordConfirm = htmlspecialchars($_POST['password_confirm']);
        $newPictureid = $_POST['profile-picture-selection'];
        if(empty($_POST['elo']))
          $newElo = NULL;
        else
          $newElo = $_POST['elo'];
          //query to check if a username of the same name already exists
          $queryString = "SELECT username FROM users WHERE username = '$newUsername'";
          $resultUsername = $db->query($queryString);
          //check if the password input and the password confirmation are the same
          if(!($newPassword == $newPasswordConfirm)){
            echo "<p>Passwords were not the same</p>";
          }
          else if(!($resultUsername->num_rows == 0)){
            echo "<p>Account under that username already exists. Please use a different one</p>";
          }
          //if everything is fine, update their information
          else{
            $encryptedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $email = $_SESSION['email'];
            $updateString = "UPDATE users SET username = '$newUsername', encrypted_password = '$encryptedPassword', player_elo = '$newElo', picture_id = '$newPictureid' WHERE email = '$email'";
            echo "<p>$updateString</p>";
            $result = $db->query($updateString);
              if($result){
                echo "<p>Update was successful</p>";
                $_SESSION['username'] = $newUsername;
                header("Location: profile.php");
              }
              else{
                echo "<p>Update was not successful</p>";
              }
            }
          }
          else{
            echo "<p>All inputs are not present</p>";
          }
      }
    //Button to show and hide edit profile form. Look at profile.js to see how javascript changes this
    echo "<button type='button' id='edit-profile-button' class='button'>Edit Profile</button>";

    echo "<form action='profile.php' method='post' id='edit-form' hidden>";
      echo "<label>Username:</label><br />";
      echo "<input type='text' name='username' value='' required /><br />";
      echo "<label>Password:</label><br />";
      echo "<input type='password' name='password' value='' required /><br />";
      echo "<label>Confirm Password:</label><br />";
      echo "<input type='password' name='password_confirm' value='' required /><br />";
      echo "<label>Chess ELO: (Optional)</label><br />";
      echo "<input type='number' name='elo' value='' /><br />";
      echo "<label>Select profile picture</label><br />";
      echo "<select name='profile-picture-selection'>";
        echo "<option selected value = '1'>1</option>";
        for($i=2;$i<=10;$i++){
          echo "<option value = '$i'>$i</option>";
        }
      echo "</select> <br />";
      echo "<input type='submit' class='button'/>";
      echo "<button type='button' id='cancel-button' class='button'>Cancel</button>";
    echo "</form>";

  }

  echo "<h2>Favourite Games</h2>";
      $favQueryA = "SELECT * FROM favourites WHERE email = '" . $email ."'";
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
                        $cellID = $row['id'];
                        echo "<table class=\"matchTable\"><tr>"; //making new tables every link on purpose
                        //this way the table id can be set/styled/clicked on
                        echo "<tr>";

                        echo "<tr> <td class='hoverTable'><a class=\"tableLink\" href=\"match-details.php?id=".$row['id']."\">Event: ".$row['Event']." " .$row['Result'];
                        echo "<br> Time Control: " . $row['TimeControl']." Termination:     ". $row['Termination']."" . " <br>";
                        echo "White:   " . $row['White'] ." (ELO:" . $row['WhiteElo'].")     VS     Black:   " . $row['Black'] ." (ELO: " . $row['BlackElo']. ") <br>";
                        echo "</td></tr>";

                        echo "</tr>";
                        if(isset($_SESSION['username']) && $_SESSION['username'] == $username){
                          echo "<tr><td><button type=\"button\" class=\"deleteGame\" id='$cellID'>Remove From Favourites</button></td></tr>";
                        }
                        echo "  </tr></table>";

                        }
                      }
                    mysqli_free_result($results);
                    mysqli_stmt_close($statement);
              }
            }
        }

        }

  include("footer.php");
?>
