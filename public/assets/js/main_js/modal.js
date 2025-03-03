$(document).ready(function () {
    $(".register-btn").click(function () {
        $("#registerModal").modal('show'); 
    });
});
$(document).ready(function () {
    $(".close-btn").click(function () {
        $("#registerModal").modal('hide'); 
    });
});