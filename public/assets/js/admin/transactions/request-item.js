$(document).ready(function() {
    $("#requestBtn").click(function() {
      $("#requestForm").css({
        "display": "flex",        
      });
    });
    $('#requestItem-closeBtn').click(function(){
        $("#requestForm").css({
            "display": "none",        
        });
    });
    $(document).on('submit', '#requestItem-form', function(event){
      event.preventDefault();
      var formData = $('#requestItem-form').serialize();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/request-item-admin',
        type: 'POST',
        data: formData,
        beforeSend: function() {
            $('#requestItemSubmit-btn').attr('disabled', true);
            Swal.fire({
                title: 'Loading...',
                text: 'Please wait while we process your request.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        },
        success: function(response){
            if(response.status === 500){
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: response.message,
                    showConfirmButton: true,
                })
            }else if(response.status === 400){
                    var errorMessages = Object.values(response.message).join('<br>');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        html: errorMessages,
                        showConfirmButton: true,
                    }).then(function() {
                        $('#requestItemSubmit-btn').attr('disabled', false);
                    });
            }else if(response.status === 200){
                Swal.fire({
                icon: "success",
                title: "All set!",
                html: response.message,
                showConfirmButton: true,
                }).then(function(){
                    window.location.reload();
                });
            }
        },error: function(error){
            console.log(error);
        }
    });
  });
});