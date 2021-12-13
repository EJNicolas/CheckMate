<html>
  <head>
    <title> CheckMate </title>
    <link rel="stylesheet" href="css/style.css">
    <script src='https://code.jquery.com/jquery-3.6.0.js' integrity='sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=' crossorigin='anonymous'></script>
    <script src='js/update-status.js'></script>
    <?php
      switch(@$page){
        case "home.php":
          echo "<script src='js/online.js'></script>";
          break;
        case "profile.php":
          echo "<script src='js/profile.js'></script>";
          break;
        case "chats.php":
          echo "<link rel='stylesheet' href='css/chats.css'>";
          echo "<script src='js/chats.js'></script>";
          break;
        default:
          break;
      }
    ?>
  </head>
  <body>
    <nav>
      <?php
        if(isset($_SESSION['email'])){
          $name = $_SESSION['username'];
          $email = $_SESSION['email'];
          echo "Signed in as: " . $name . "</br>";
          echo "<a href='home.php'>Home</a>";
          echo "<a href='find-game.php'>Search Games</a>";
          echo "<a href='profile.php'>My Profile</a>";
          echo "<a href='logout.php'>Log Out</a>";
          echo "<a href='change-status.php' class='change-status-button'>Change Online Status</a>";
        }
        else{
          echo "<a href='home.php'>Home</a>";
          echo "<a href='find-game.php'>Search Games</a>";
          echo "<a href='login.php'>Log In</a>";
        }

        if(isset($_COOKIE['onlineStatus'])){
          if($_COOKIE['onlineStatus'] == "TRUE"){
            setcookie("onlineStatus","TRUE",time()+10);
          }
        }
      ?>
    </nav>
