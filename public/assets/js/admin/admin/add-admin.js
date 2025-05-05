
$(document).ready(function() {
    $('#addAdminBtn').click(function(){
        $('#addAdminModal').modal('show');
    });
    $('#add-admin-close-btn').click(function(){
        $('#addAdminModal').modal('hide');
    });
    $(document).on('submit', '#add-admin-form', function(event){
        event.preventDefault();
        var formData = $('#add-admin-form').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/add-admin',
            type: 'POST',
            data: formData,
            beforeSend: function() {
                $('#add-admin-submit-btn').attr('disabled', true);
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
                            $('#add-admin-submit-btn').attr('disabled', false);
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