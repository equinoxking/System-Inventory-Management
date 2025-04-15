function editAdmin(admin){
    var data = JSON.parse(admin);
    $('#editAdminModal').modal('show'); 
    $('#edit-admin-id').val(data.id);
    $('#edit-admin-full-name').val(data.full_name);
    $('#edit-admin-control_number').val(data.control_number);
    $('#edit-admin-position').val(data.position);
}
$('#edit-admin-close-btn').click(function(){
    $('#editAdminModal').modal('hide');
});
$(document).ready(function() {
    $(document).on('submit', '#edit-admin-form', function(event){
        event.preventDefault();
        var formData = $('#edit-admin-form').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/update-admin',
            type: 'PATCH',
            data: formData,
            beforeSend: function() {
                $('#edit-admin-submit-btn').attr('disabled', true);
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
                            $('#edit-admin-submit-btn').attr('disabled', false);
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