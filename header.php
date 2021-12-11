<html>
  <head>
    <title> CheckMate </title>
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <nav>
      <?php
        if(isset($_SESSION['email'])){
          $name = $_SESSION['username'];
          $email = $_SESSION['email'];
          echo "Signed in as: " . $name . "</br>";
        }
      ?>
      <a href="home.php">Home</a>
      <a href="find-game.php">Search Games</a>
      <a href="profile.php">My Profile</a>
      <a href="login.php">Log In</a>
      <a href="logout.php">Log Out</a>
      <a href="change-status.php">Change Online Status</a>
      <?php

      ?>
    </nav>
