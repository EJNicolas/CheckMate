<?php
  session_start();
  include("profile-header.php");
  $db = mysqli_connect("localhost", "root", "", "chess-games");
  if($db->connect_errno) {
      $msg = "Database connection failed: ";
      $msg .= mysqli_connect_error();
      $msg .= " (" . mysqli_connect_errno() . ")";
      exit($msg);
  }

  if(isset($_SESSION['email'])){
    $email = $_SESSION['email'];
    $queryString = "SELECT username, player_elo, creation_date, picture_id FROM users WHERE email = '$email'";
    $result = $db->query($queryString);
    while($row = $result->fetch_assoc()){
      $username = $row['username'];
      $elo = $row['player_elo'];
      $date = $row['creation_date'];
      $picture = $row['picture_id'];
    }
  }
  else {
    header("Location: login.php");
  }

  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if( isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password_confirm'])){
      $newUsername = htmlspecialchars($_POST['username']);
      $newPassword = htmlspecialchars($_POST['password']);
      $newPasswordConfirm = htmlspecialchars($_POST['password_confirm']);
      $newPictureid = $_POST['profile-picture-selection'];
      if(empty($_POST['elo']))
        $newElo = NULL;
      else
        $newElo = $_POST['elo'];

        $queryString = "SELECT username FROM users WHERE username = '$newUsername'";
        $resultUsername = $db->query($queryString);
        if(!($newPassword == $newPasswordConfirm)){
          echo "<p>Passwords were not the same</p>";
        }
        else if(!($resultUsername->num_rows == 0)){
          echo "<p>Account under that username already exists. Please use a different one</p>";
        }
        else{
          $encryptedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

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

  echo "<h1>My Profile</h1>";
  echo "<p>Username: $username</p>";
  $queryString = "SELECT file_location FROM profile_picture WHERE picture_id = $picture";
  $result = $db->query($queryString);
  while($row = $result->fetch_assoc()){
    $pictureFile = $row['file_location'];
  }
  echo "<img src='$pictureFile' width='200' height='200'>";
  echo "<p>Account created on: $date</p>";
  if(!$elo==0){
    echo "<p>ELO $elo</p>";
  }

  echo "<button type='button' id='edit-profile-button'>Edit Profile</button>";

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
    echo "<input type='submit' />";
    echo "<button type='button' id='cancel-button'>Cancel</button>";
  echo "</form>";

  echo "<h2>Favourite Games</h2>";

  include("footer.php");
?>
