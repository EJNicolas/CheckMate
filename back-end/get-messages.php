<?php
  //start off with an empty array and get the chat id through the url (look at chats.js)
  $messagesArray = [];
  $chatId = $_GET['chatId'];
  //establish connection to database
  $db = mysqli_connect("localhost", "root", "", "chess-games");
  if($db->connect_errno) {
      $msg = "Database connection failed: ";
      $msg .= mysqli_connect_error();
      $msg .= " (" . mysqli_connect_errno() . ")";
      exit($msg);
  }
  //get all the messages in a chat id and join the user's email with their username
  $queryString = "SELECT chat_messages.message_id, chat_messages.messageDateTime, chat_messages.message, users.username FROM chat_messages INNER JOIN users ON chat_messages.user = users.email WHERE chat_id = '$chatId' ORDER BY message_id";
  $result = $db->query($queryString);
  if($result){
    while($row = $result->fetch_assoc()){
      //arrange data as an associative array to be sent as json
      $messagesArray += [$row['message_id'] => [$row['username'], $row['message'], $row['messageDateTime']]];
    }
  }
  else{
    echo "error retrieving messages";
  }
  //encode associative array to json (check chats.js)
  print(json_encode($messagesArray));
?>
