$(document).ready(function(){
	let searchParams = new URLSearchParams(window.location.search);
    var gameID = searchParams.get('id');
    var addFav = $("#addFav");
	// console.log(addFav);
	// console.log(gameID);
  	addFav.on("click", function(){
  	alert("Added To Favourites");
    $.ajax({
        url: 'back-end/addToFav.php?id='+gameID 
      }).fail(function(er){
      console.log(er);
    });
  });

    var copyToClip = $("#copied");
    copyToClip.on("click", function(){
      alert("Copied To Clipboard");
      $.getJSON('back-end/copyToClip.php?id='+gameID, function (data) {
        console.log(data);
        $.each(data, function (key, value){

          navigator.clipboard.writeText(value);
        });
    });
  });

});
