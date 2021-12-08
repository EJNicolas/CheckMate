$(document).ready(function(){
  var onlineUsersList = $("#online-users-list");
  var body = $("body");

  var checkOnline = function() {
    $.getJSON('checkonlineusers.php', function (data){
      console.log(data);
      $(".online-user").remove();
      $.each(data, function(name, status) {
			    onlineUsersList.append("<li class='online-user'>"+name+"</li>");
      });
      var onlineUsers = $(".online-user");
      var showMenu = false;
      onlineUsers.on("click", function(){
        showMenu = !showMenu;
        var onlineUsername = $(this).text();
        console.log(onlineUsername);
        if(showMenu){
          $(this).append("<ul class='user-drop-down'> \n <li class='user-message'>message</li> \n <li class='view-user-profile'>view profile</li> \n </ul>");

          $(".user-message").on("click", function(){
            alert("creating chat between you and " + onlineUsername);
            //setting cookies to in js learned from https://www.w3schools.com/js/js_cookies.asp
            const d = new Date();
            d.setTime(d.getTime() + (1000));
            let expires = "expires="+ d.toUTCString();
            document.cookie = "contactingUser=" + onlineUsername + ";" + expires;
            window.location.assign("chats.php");
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
    setTimeout(checkOnline, 8000);
  };

  checkOnline();

  // var showMenu = false;
  // body.on("mouseover", function(){
  //   var onlineUsers = $(".online-user");
  //   onlineUsers.on("click", function(){
  //     showMenu = !showMenu;
  //     console.log(showMenu);
  //     if(showMenu){
  //       $(this).append("<ul class='user-drop-down'> \n <li class='user-message'>message</li> \n <li class='view-user-profile'>view profile</li> \n </ul>");
  //     }
  //     else{
  //       $(".user-drop-down").remove();
  //     }
  //
  //     // if(showMenu){
  //     //   showMenu = false;
  //     // }
  //
  //   });
  // });



});
