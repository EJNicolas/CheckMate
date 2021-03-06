<?php
  //connect to database and sessions
  session_start();
  include("header.php");
  $db = mysqli_connect("localhost", "root", "", "chess-games");
  if($db->connect_errno) {
      $msg = "Database connection failed: ";
      $msg .= mysqli_connect_error();
      $msg .= " (" . mysqli_connect_errno() . ")";
      exit($msg);
  }

  //code to perform when form is submitted
  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    //checks if all the required fields have something
    if( isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password_confirm'])){
      //gets all infomation from the forms
      $email = $_POST['email'];
      $username = htmlspecialchars($_POST['username']);
      $password = htmlspecialchars($_POST['password']);
      $passwordConfirm = htmlspecialchars($_POST['password_confirm']);
      $pictureid = $_POST['profile-picture-selection'];
      if(empty($_POST['elo']))
        $elo = NULL;
      else
        $elo = $_POST['elo'];

      //query for checking if an email already exists
      $queryString = "SELECT email FROM users WHERE email = '$email'";
      $resultEmail = $db->query($queryString);
      //query for checking if a username exists
      $queryString = "SELECT username FROM users WHERE username = '$username'";
      $resultUsername = $db->query($queryString);
      //check if the passwords were the same
      if(!($password == $passwordConfirm)){
        echo "<p>Passwords were not the same</p>";
      }
      //check if an email already exists
      else if(!($resultEmail->num_rows == 0)){
        echo "<p>Account under that email already exists</p>";
      }
      //check if an account with the username exists
      else if(!($resultUsername->num_rows == 0)){
        echo "<p>Account under that username already exists. Please use a different one</p>";
      }
      //email isnt valid
      else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo "<p>email is not valid</p>";
      }
      //when all of the information is valid
      else{
        $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);
        $creationDate = date("Y-m-d");
        //insiert data into database
        $insertString = "INSERT INTO users(email, username, encrypted_password, player_elo, picture_id, creation_date) VALUES " . "(" . "'$email', '$username', '$encryptedPassword', '$elo', '$pictureid', '$creationDate'" .  ")";
        echo "<p>$insertString</p>";
        $result = $db->query($insertString);
          if($result){
            echo "<p>Insert was successful</p>";
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $username;
            header("Location: index.php");
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
    //html for registry form
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
      echo "<input type='submit' class='button'/>";
    echo "</form>";

  include("footer.php");

?>
