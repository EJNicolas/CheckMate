<?php
  session_start();
  include("functions/db-helper-functions.php");

  //get 1st member's information
  if(isset($_SESSION['email'])){
    $member1Email = $_SESSION['email'];
    $member1Name = $_SESSION['username'];
  }
  else header("Location: login.php");

  //get 2nd member's information
  if(isset($_COOKIE['contactingUser'])){
    $member2Name = $_COOKIE['contactingUser'];
    $member2Email = getUserEmail($member2Name);
  }

  //check if a connection between these members already exists
  $queryString = "SELECT member1, member2 FROM chat_connections WHERE (member1 = '$member1Email' OR member2 = '$member1Email') AND (member1 = '$member2Email' OR member2 = '$member2Email')";
  $result = $db->query($queryString);

  //code for initially creating a chat connection
  if($result->num_rows == 0){

    //insert the two member's emails into the chat_connections table
    $insertString = "INSERT INTO chat_connections(member1, member2) VALUES ('$member1Email', '$member2Email')";
    $insertResult = $db->query($insertString);

    if($insertResult){
      echo "<p>Chat connection insertion was successful</p>";

      //get the chat id for the new chat_connection that was made
      $queryString = "SELECT chat_id FROM chat_connections WHERE (member1 = '$member1Email' OR member2 = '$member1Email') AND (member1 = '$member2Email' OR member2 = '$member2Email')";
      $result = $db->query($queryString);
      while($row = $result->fetch_assoc()){
        $chatId = $row['chat_id'];
      }

      //create new row in contacts table that has the new chat id and the members in it
      $insertString = "INSERT INTO contacts (chat_id, email1, email2) VALUES ('$chatId', '$member1Email', '$member2Email')";
      $contactResult = $db->query($insertString);
      if($contactResult){
        echo "it worked! i should redirect you";
      }
      else{
        echo "<p>Something went wrong with the contact</p>";
      }
    }
    else{
      echo "<p>Something went wrong with the chat connection</p>";
    }
  }
  else{
    echo "<p>Chat already exists</p>";
    //header("Location: home.php");
  }

?>
