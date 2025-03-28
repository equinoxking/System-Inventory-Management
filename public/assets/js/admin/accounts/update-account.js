$(document).ready(function(){
    $(document).on('submit', '#update-account-form' ,function(event){
        event.preventDefault();
        var formData = $('#update-account-form').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/update-admin-account',
            type: 'PATCH',
            data: formData,
            beforeSend: function() {
                $('#update-account-btn').attr('disabled', true);
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
                    }).then(function() {
                        $('#update-account-btn').attr('disabled', false);
                    });
                }else if(response.status === 404){
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: response.message,
                        showConfirmButton: true,
                    }).then(function() {
                        $('#update-account-btn').attr('disabled', false);
                    });
                }else if(response.status === 400){
                        var errorMessages = Object.values(response.message).join('<br>');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: "Old password is incorrect!",
                            showConfirmButton: true,
                        }).then(function() {
                            $('#update-account-btn').attr('disabled', false);
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