<?php

  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['chat-text-area'])){
      $message = htmlspecialchars($_POST['chat-text-area']);
      $messageDateTime = date("Y-m-d  H:i:s");
      $insertString = "INSERT INTO chat_messages (user, messageDateTime, message, chat_id) VALUES ('$email', '$messageDateTime', '$message', '$chatId')";
      $insertResult = $db->query($insertString);
      if($insertResult){
        $messageId = $db->insert_id;
        $insertString = "INSERT INTO sent_through (chat_id, message_id) VALUES ('$chatId', '$messageId')";
        header('Location: '.$_SERVER['REQUEST_URI']);
      }
      else{
        echo "<p>An error occured sending your message</p>";
      }
    }
  }



?>
