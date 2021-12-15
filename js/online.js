$(document).ready(function(){
  //get the online users html
  var onlineUsersList = $("#online-users-list");
  var body = $("body");

  var checkOnline = function() {
    //get a json list of all online users from chceckonlineusers.php
    $.getJSON('back-end/checkonlineusers.php', function (data){
      //remove all of online users in html (will be put back later)
      $(".online-user").remove();
      //show all of the online users in a list
      $.each(data, function(name, status) {
			    onlineUsersList.append("<li class='online-user'>"+name+"</li>");
      });
      //get all of the online users
      var onlineUsers = $(".online-user");
      //variable to toggle the menu showing up
      var showMenu = false;
      //whenever the user clicks on someone who's online,, get the username to move to the next page and add the options in html
      onlineUsers.on("click", function(){
        // toggle the menu
        showMenu = !showMenu;
        //get the username to be used in the next pages
        var onlineUsername = $(this).text();
        if(showMenu){
          //html to show the meny
          $(this).append("<ul class='user-drop-down'> \n <li class='user-message'>message</li> \n <li class='view-user-profile'>view profile</li> \n </ul>");
          //if the user clicks on the message option then bring them to initializa-chat page
          $(".user-message").on("click", function(){
            //setting cookies to in js learned from https://www.w3schools.com/js/js_cookies.asp
            //make a new cookie to briefly store the username of the other user to initialize the chat
            const d = new Date();
            d.setTime(d.getTime() + (1000));
            let expires = "expires="+ d.toUTCString();
            document.cookie = "contactingUser=" + onlineUsername + ";" + expires;
            window.location.assign("back-end/initialize-chat.php");
          });
          //if the user clicks on the profile option, move them to the profile.php with the user's name in the url
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
