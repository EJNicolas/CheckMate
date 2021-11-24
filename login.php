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

  if(isset($_SESSION['email'])){
    header("Location: home.php");
  }

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(!empty($_POST['email']) && !empty($_POST['password']) ){

    $email = $_POST['email'];
    $password = $_POST['password'];

    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
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

      if(password_verify($password,$dbPassword)){
        echo "<p>You are logged in. Welcome $dbUsername</p>";
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $dbUsername;
        header("Location: home.php");
      }
      else
        echo "<p>Wrong username or password</p>";
    }
    else
      echo "<p>Email is not valid</p>";
  }
}

  echo "<h1>Log in</h1>";
  echo "<form action='login.php' method='post'>";
    echo "<label>Email:<label> <br />";
    echo "<input type='text' name='email' value='' /><br />";
    echo "Password:<br />";
    echo "<input type='password' name='password' value='' /><br />";
    echo "<input type='submit' />";
  echo "</form> </br>";

  echo "<a href='register.php'>Register</a>";

  include("footer.php");

?>
