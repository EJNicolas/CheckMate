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

  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if( isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password_confirm'])){
      $email = $_POST['email'];
      $username = htmlspecialchars($_POST['username']);
      $password = htmlspecialchars($_POST['password']);
      $passwordConfirm = htmlspecialchars($_POST['password_confirm']);
      $pictureid = $_POST['profile-picture-selection'];
      if(empty($_POST['elo']))
        $elo = NULL;
      else
        $elo = $_POST['elo'];

      $queryString = "SELECT email FROM users WHERE email = '$email'";
      $result = $db->query($queryString);
      if(!($password == $passwordConfirm)){
        echo "<p>Passwords were not the same</p>";
      }
      else if(!($result->num_rows == 0)){
        echo "<p>Account under that email already exists</p>";
      }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo "<p>email is not valid</p>";
      }
      else{
        $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);
        $creationDate = date("Y-m-d");

        $insertString = "INSERT INTO users(email, username, encrypted_password, player_elo, picture_id, creation_date) VALUES " . "(" . "'$email', '$username', '$encryptedPassword', '$elo', '$pictureid', '$creationDate'" .  ")";
        echo "<p>$insertString</p>";
        $result = $db->query($insertString);
          if($result){
            echo "<p>Insert was successful</p>";
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $username;
          }
          else{
            echo "<p>Insert was not successful</p>";
          }
        }
      }
      else{
        echo "<p>All inputs are not present</p>";
      }
  }

    echo "<h1>Register</h1>";
    echo "<form action='register.php' method='post'>";
      echo "<label>Email:<label><br />";
      echo "<input type='text' name='email' value='' required /><br />";
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
    echo "</form>";

  include("footer.php");

?>
