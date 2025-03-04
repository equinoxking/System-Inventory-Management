$(document).ready(function(){
    $(document).on('submit', '#registration-form', function(event){
        event.preventDefault();
        var formData = $('#registration-form').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/register-user',
            type: 'POST',
            data: formData,
            beforeSend: function() {
                $('#register-btn').attr('disabled', true);
                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait while we process your registration.',
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
                            title: 'Registration validation failed!',
                            html: errorMessages,
                            showConfirmButton: true,
                        }).then(function() {
                            $('#register-btn').attr('disabled', false);
                        });
                }else if(response.status === 200){
                    Swal.fire({
                    icon: "success",
                    title: "All set!",
                    html: response.message + '<br>' +"Your username is: " + '<strong>' + response.username + '</strong>',
                    showConfirmButton: true,
                    }).then(function(){
                        window.location.reload();
                        $('#registerModal').modal('hide');
                    });
                }
            },error: function(error){
                console.log(error);
            }
        });
    });
});