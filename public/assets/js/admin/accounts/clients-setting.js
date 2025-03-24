function setUser(client){
    $('#setUserModal').modal('show');
    let data = JSON.parse(client);
    $('#set-user-id').val(data.id);
    $('#full-name').val(data.full_name);
}
$(document).ready(function(){
    $(document).on('submit', '#set-user-role-form' ,function(event){
        event.preventDefault();
        var formData = $('#set-user-role-form').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/set-user-role',
            type: 'PATCH',
            data: formData,
            beforeSend: function() {
                $('#set-role-submit-btn').attr('disabled', true);
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
                            $('#set-role-submit-btn').attr('disabled', false);
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
$('#set-user-close-btn').click(function(){
    $('#setUserModal').modal('hide');
});
