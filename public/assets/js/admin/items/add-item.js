$(document).ready(function() {
    $("#addItemBtn").click(function() {
      $("#itemForm").css({
        "display": "flex",        
      });
    });
    $('#createItem-closeBtn').click(function(){
      $("#itemForm").css({
        "display": "none",        
      });
    })
});
