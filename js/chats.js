$(document).ready(function(){
  var chatArea = $(".chat-messages-scroll-container");

  var updateChat = function(){
    let searchParams = new URLSearchParams(window.location.search)
    var chatId = searchParams.get('chatId');
    console.log(chatId);
    $.getJSON('get-messages.php?chatId='+chatId, function (data) {
      $(".chat-message").remove();
      $.each(data, function (key, value){
        chatArea.append("<div class='chat-message'> \n <h3 class='chat-user'>" + value[0] + "</h3> \n <h4 class='chat-date'>" + value[2] + "</h4> \n <p class='chat-text'>" + value[1] + "</p> \n </div>");
      });
    })
    .fail(function(er){
      console.log(er);
    });
    setTimeout(updateChat, 6000);
  };

  updateChat();

});
