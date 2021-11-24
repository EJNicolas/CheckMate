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
        if(showMenu){
          $(this).append("<ul class='user-drop-down'> \n <li class='user-message'>message</li> \n <li class='view-user-profile'>view profile</li> \n </ul>");
          $(".user-message").on("click", function(){
            alert("message user clicked on");
          });

          $(".view-user-profile").on("click", function(){
            window.location.assign("profile.php");
          });

        }
        else{
          $(".user-drop-down").remove();
        }

      });

    });
    setTimeout(checkOnline, 20000);
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
