$(document).ready(function(){
  var onlineUsersList = $("#online-users-list")

  

  var checkOnline = function() {
    $.getJSON('checkonlineusers.php', function (data){
      console.log(data);
      $(".online-user").remove();
      $.each(data, function(name, status) {
			    onlineUsersList.append("<li class='online-user'>"+name+"</li>");
      });
    });
    setTimeout(checkOnline, 20000);
  };

  checkOnline();

});
