$(document).ready(function(){
  var chatArea = $(".chat-messages-scroll-container");

  var updateChat = function(){
    $.getJSON('get-messages.php', function (data) {
      console.log(data);
      $.each(data, function (key, value){
        chatArea.append("<div class='chat-message'> \n <h3 class='chat-user'>" + value[0] + "</h3> \n <h4 class='chat-date'>" + value[2] + "</h4> \n <p class='chat-text'>" + value[1] + "</p> \n </div>");
      });
    });
    setTimeout(updateChat, 4000);
  };

  updateChat();

});
