$(document).ready(function(){
  //get the chat area element in html
  var chatArea = $(".chat-messages-scroll-container");

  var updateChat = function(){
    //get the url to get the chat id
    let searchParams = new URLSearchParams(window.location.search);
    var chatId = searchParams.get('chatId');
    //get the json that was printed in get-messages.php
    $.getJSON('back-end/get-messages.php?chatId='+chatId, function (data) {
      //remove all of the elements in the chat
      $(".chat-message").remove();
      //re-add all of the messages that was sent by the json
      $.each(data, function (key, value){
        chatArea.append("<div class='chat-message'> \n <h3 class='chat-user'>" + value[0] + "</h3> \n <h4 class='chat-date'>" + value[2] + "</h4> \n <p class='chat-text'>" + value[1] + "</p> \n </div>");
      });
    })
    .fail(function(er){
      console.log(er);
    });
    //keep checking the database every few seconds
    setTimeout(updateChat, 3000);
  };

  updateChat();

});
