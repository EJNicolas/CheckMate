$(document).ready(function(){
  var statusButton = $(".change-status-button");

  var updateStatus = function(){
    status = readCookie("onlineStatus");
    if(status == "TRUE"){
      console.log("Youre online");
      statusButton.css("background-color", "#779556");
    }
    else{
      console.log("Youre offline");
      statusButton.css("background-color", "#c8342f");
      $.ajax({
        url: 'timeout.php'
      });
    }

    setTimeout(updateStatus, 4000);
  };

  updateStatus();

  //method from https://stackoverflow.com/questions/1599287/create-read-and-erase-cookies-with-jquery
  function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
  }

});
