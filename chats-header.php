<html>
  <head>
    <title> CheckMate </title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/chats.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="js/chats.js"></script>
  </head>
  <body>
    <nav>
      <?php
        if(isset($_SESSION['username'])){
          $name = $_SESSION['username'];
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
