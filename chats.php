<?php
  //initialize session, functions and database
  session_start();
  $page = "chats.php";
  include("header.php");
  include("functions/db-helper-functions.php");

  //get user's session info. If the user is not logged in, move them to the login page
  if(isset($_SESSION['email'])){
    $email = $_SESSION['email'];
    $username = $_SESSION['username'];
  } else header("Location: login.php");

  //making the chats side bar. This can happen even when there is no other chat id in the url
  $queryString = "SELECT * FROM contacts WHERE email1 = '$email' OR email2 = '$email'";
  $result = $db->query($queryString);

  echo "<aside class='chat-contacts'>";
    echo "<h2>Chats</h2>";

    echo "<div class='contacts-container'>";
      //get the chat id, and email of the other user
      if($result){
        while($row = $result->fetch_assoc()){
          $chatContactId = $row['chat_id'];
          //the email can be on either column
          if($email == $row['email1'])  $contactEmail = $row['email2'];
          else $contactEmail = $row['email1'];
          $contactUsername = getUserName($contactEmail);

          //retrieving the profile picture to display
          $queryStringPicture = "SELECT users.picture_id, profile_picture.file_location FROM users INNER JOIN profile_picture ON users.picture_id = profile_picture.picture_id WHERE email = '$contactEmail'";
          $resultPicture = $db->query($queryStringPicture);
          $picture = $resultPicture->fetch_assoc();
          $pictureFile = $picture['file_location'];

          //html to be displayed
          echo "<div class='contact'>";
            echo "<img src='$pictureFile' width='50' height='50'>";
            echo "<a href='chats.php?chatId=$chatContactId'>$contactUsername</a>";
          echo "</div>";
        }
      }
      else "<p>No contacts found</p>";
    echo "</div>";
  echo "</aside>";

  //code when there is a chat id in the url
  if(isset($_GET['chatId'])){
    $chatId = $_GET['chatId'];

    //check if the user belongs in this chat
    $queryString = "SELECT * FROM chat_connections WHERE chat_id = '$chatId'";
    $result = $db->query($queryString);
    //checks if a chat connection does exist
    if($result->num_rows == 0){
      echo "<p>Chat does not exist</p>";
    }
    else{
      //finds out the other member in the chat
      while($row = $result->fetch_assoc()){
        $member1 = $row['member1'];
        $member2 = $row['member2'];
      }
      if($email == $member1){
        $otherUser = $member2;
      }
      else if($email == $member2){
        $otherUser = $member1;
      }
      else{
        header("Location: chats.php");
      }
      $otherUserName = getUserName($otherUser);

      //html of the chat messages
      echo "<div class=chat-container>";
        echo "<h1 class='chat-title'>$otherUserName</h1>";
        echo "<div class='chat-messages-scroll-container'>";
          //uncomment to see an example of a text message
          // echo "<div class='chat-message'>";
          //   echo "<h3 class='chat-user'>test</h4>";
          //   echo "<h4 class='chat-date'>12/25/2000</h4>";
          //   echo "<p class='chat-text'>testeateatasd</p>";
          // echo "</div>";
        echo "</div>";
        echo "<div class='chat-form'>";
          echo "<form method ='post'>";
            echo "<textarea id='chat-text-area' name='chat-text-area' rows=3 cols='100'> </textarea> </br>";
            echo "<button type='submit'>Send</button>";
          echo "</form>";
        echo "</div>";
      echo "</div>";

      include("send-message.php");
    }
  }

  mysqli_free_result($result);
  include("footer.php");

?>
