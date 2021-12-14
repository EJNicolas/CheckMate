<?php
  //this chunk of php is used in chats.php
  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['chat-text-area'])){
      //get the message and the date it was created
      $message = htmlspecialchars($_POST['chat-text-area']);
      $messageDateTime = date("Y-m-d  H:i:s");
      //insert put data into database
      $insertString = "INSERT INTO chat_messages (user, messageDateTime, message, chat_id) VALUES ('$email', '$messageDateTime', '$message', '$chatId')";
      $insertResult = $db->query($insertString);
      if($insertResult){
        //if the insert was successful, create a new entry in the sent_through relational table
        $messageId = $db->insert_id;
        $insertString = "INSERT INTO sent_through (chat_id, message_id) VALUES ('$chatId', '$messageId')";
        //reload the page
        header('Location: '.$_SERVER['REQUEST_URI']);
      }
      else{
        echo "<p>An error occured sending your message</p>";
      }
    }
  }



?>
