<?php
  //establish connection to database and session
  session_start();
  include("header.php");
  $db = mysqli_connect("localhost", "root", "", "chess-games");
  if($db->connect_errno) {
      $msg = "Database connection failed: ";
      $msg .= mysqli_connect_error();
      $msg .= " (" . mysqli_connect_errno() . ")";
      exit($msg);
  }

  //if the user is logged in, send them to the home page if they try accessing the log in page again
  if(isset($_SESSION['email'])){
    header("Location: index.php");
  }

  //code to perform once the user hits the submit button
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //checks if the email and password arent empty
    if(!empty($_POST['email']) && !empty($_POST['password']) ){

    $email = $_POST['email'];
    $password = $_POST['password'];
    //validate email to put it in the query string
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
      //get the email and password in the database to check if the user's inputs were correct
      $queryString = "SELECT email, username, encrypted_password FROM users WHERE email = ?";
      $stmt = $db->prepare($queryString);
      $stmt->bind_param('s',$email);
      $stmt->execute();
      $result = mysqli_stmt_get_result($stmt);
      $dbEmail = "";
      $dbPassword = "";
      while($row = $result->fetch_assoc()){
        $dbEmail = $row['email'];
        $dbUsername = $row['username'];
        $dbPassword = $row['encrypted_password'];
      }

      //check if the inputted password and the password in the database are the same
      if(password_verify($password,$dbPassword)){
        //if the passwords math, log the user in by setting their session data
        echo "<p>You are logged in. Welcome $dbUsername</p>";
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $dbUsername;
        header("Location: index.php");
      }
      else
        echo "<p>Wrong username or password</p>";
    }
    else
      echo "<p>Email is not valid</p>";
  }
}
  //html for the log in form
  echo "<h1>Log in</h1>";
  echo "<form action='login.php' method='post'>";
    echo "<label>Email:<label> <br />";
    echo "<input type='text' name='email' value='' /><br />";
    echo "Password:<br />";
    echo "<input type='password' name='password' value='' /><br />";
    echo "<input type='submit' class='button'/>";
  echo "</form> </br>";

  echo "<a href='register.php'>Register</a>";

  include("footer.php");

?>
