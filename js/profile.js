$(document).ready(function(){
  var editButton = $("#edit-profile-button");
  var cancelButton = $("#cancel-button");

  editButton.on("click", function(){
    $("#edit-form").removeAttr('hidden');
  });

  cancelButton.on("click", function(){
    $("#edit-form").attr('hidden', true);
  });

});
