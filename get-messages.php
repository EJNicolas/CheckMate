<?php
  $messagesArray = [];
  $queryString = "SELECT chat_messages.message_id, chat_messages.messageDateTime, chat_messages.message, users.username FROM chat_messages INNER JOIN users ON chat_messages.user = users.email WHERE chat_id = '$chatId' ORDER BY message_id";
  $result = $db->query($queryString);
  if($result){
    while($row = $result->fetch_assoc()){
      $messagesArray += [$row['message_id'] => [$row['username'], $row['message'], $row['messageDateTime']]];
    }
  }
  else{
    echo "error retrieving messages";
  }
  //$messagesArrayLength = count($messagesArray);

  print(json_encode($messagesArray));
?>
