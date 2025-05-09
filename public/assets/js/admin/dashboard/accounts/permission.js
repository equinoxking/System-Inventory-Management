$(document).ready(function() {
    // Show the modal when 'Set Permission' is clicked
    $('#setPermission').click(function() {
        $('#setUserModal').modal('show');
    });
    $('#set-user-close-btn').click(function() {
        $('#setUserModal').modal('hide');
    });
});
$(document).ready(function(){
    $(document).on('submit', '#set-user-role-form' ,function(event){
        event.preventDefault();
        var formData = $('#set-user-role-form').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/dashboard-change-user-role',
            type: 'PATCH',
            data: formData,
            beforeSend: function() {
                $('#change-user-status-submit-btn').attr('disabled', true);
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
                            $('#change-user-status-submit-btn').attr('disabled', false);
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
$(document).ready(function() {
    var userStatusElement = $('#status-badge');
    var statusDropdown = $('#status');
    if (userStatusElement.hasClass('badge-success')) {
        statusDropdown.html('<option value="">Select Role</option><option value="Inactive">Deactivate</option>');
    } else if (userStatusElement.hasClass('badge-danger')) {
        statusDropdown.html('<option value="">Select Role</option><option value="Active">Activate</option>');
    }
});
$('#change-user-status-close-btn').click(function(){
    $('#changeUserStatusModal').modal('hide');
});