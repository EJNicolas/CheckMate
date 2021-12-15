<html>
  <head>
    <title> CheckMate </title>
    <link rel="stylesheet" href="css/style.css">
    <script src='https://code.jquery.com/jquery-3.6.0.js' integrity='sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=' crossorigin='anonymous'></script>
    <script src='js/update-status.js'></script>
    <?php
    //changes what scripts and css will run in the header depending on the page
      switch(@$page){
        case "home.php":
          echo "<script src='js/online.js'></script>";
          break;
        case "profile.php":
        echo "<link rel='stylesheet' href='css/profile.css'>";
          echo "<script src='js/profile.js'></script>";
          echo "<script src='js/removeFav.js'></script>";
          break;
        case "chats.php":
          echo "<link rel='stylesheet' href='css/chats.css'>";
          echo "<script src='js/chats.js'></script>";
          break;
        case "match-details.php":
          echo "<script src=\"js/favourites.js\"></script>";
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
          echo "<a href='index.php' class='nav-item'>Home</a>";
          echo "<a href='find-game.php?pg=1' class='nav-item'>Search Games</a>";
          echo "<a href='chats.php' class='nav-item'>Chats</a>";
          echo "<a href='profile.php' class='nav-item'>My Profile</a>";
          echo "<a href='back-end/logout.php' class='nav-item'>Log Out</a>";
          echo "<a href='back-end/change-status.php' class='change-status-button'>Change Online Status</a>";
          echo "<h4 class=nav-name>Signed in as: " . $name . "</h4>";
        }
        else{
          echo "<a href='index.php' class='nav-item'>Home</a>";
          echo "<a href='find-game.php?pg=1' class='nav-item'>Search Games</a>";
          echo "<a href='login.php' class='nav-item'>Log In</a>";
        }
        //if a person is online, whenever they go to a new page, refresh the cookie so the user wont get timed out
        if(isset($_COOKIE['onlineStatus'])){
          if($_COOKIE['onlineStatus'] == "TRUE"){
            setcookie("onlineStatus","TRUE",time()+10);
          }
        }
      ?>
    </nav>
