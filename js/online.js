$(document).ready(function(){
  var onlineUsersList = $("#online-users-list");
  var body = $("body");

  var checkOnline = function() {
    $.getJSON('checkonlineusers.php', function (data){
      $(".online-user").remove();
      $.each(data, function(name, status) {
			    onlineUsersList.append("<li class='online-user'>"+name+"</li>");
      });
      var onlineUsers = $(".online-user");
      var showMenu = false;
      onlineUsers.on("click", function(){
        showMenu = !showMenu;
        var onlineUsername = $(this).text();
        if(showMenu){
          $(this).append("<ul class='user-drop-down'> \n <li class='user-message'>message</li> \n <li class='view-user-profile'>view profile</li> \n </ul>");

          $(".user-message").on("click", function(){
            //setting cookies to in js learned from https://www.w3schools.com/js/js_cookies.asp
            const d = new Date();
            d.setTime(d.getTime() + (1000));
            let expires = "expires="+ d.toUTCString();
            document.cookie = "contactingUser=" + onlineUsername + ";" + expires;
            window.location.assign("initialize-chat.php");
          });

          $(".view-user-profile").on("click", function(){
            window.location.href = "profile.php?username="+onlineUsername;
          });
        }
        else{
          $(".user-drop-down").remove();
        }
      });
    });
    setTimeout(checkOnline, 4200);
  };

  checkOnline();

});
