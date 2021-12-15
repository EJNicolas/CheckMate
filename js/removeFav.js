$(document).ready(function(){
	let searchParams = new URLSearchParams(window.location.search);
    // var gameID = searchParams.get('id');
    var removeFav = $(".deleteGame");
	console.log(removeFav);
  	removeFav.on("click", function(){
  	alert("Removed From Favourites");
    // alert(this.id);
    gameID = this.id;
    console.log(gameID);
    $.ajax({
        url: 'back-end/removeFromFave.php?id='+gameID
      }).fail(function(er){
      console.log(er);
    });
  });

});
