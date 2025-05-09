function changeUserStatus(client){
    $('#changeUserStatusModal').modal('show');
    let data = JSON.parse(client);
    $('#change-user-status-id').val(data.id);
    $('#change-user-full-name').val(data.full_name);
}
$(document).ready(function(){
    $(document).on('submit', '#change-user-status-form' ,function(event){
        event.preventDefault();
        var formData = $('#change-user-status-form').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/change-user-status',
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
    var userStatusElement = $('#status-badge');  // The badge showing the current status
    var statusDropdown = $('#status');  // The status dropdown
    
    // Check if the user has 'Active' status
    if (userStatusElement.hasClass('badge-success')) {
        // Set 'Active' option selected in the dropdown (Activate)
        statusDropdown.val('Active');
    } 
    // Check if the user has 'Inactive' status
    else if (userStatusElement.hasClass('badge-danger')) {
        // Set 'Inactive' option selected in the dropdown (Deactivate)
        statusDropdown.val('Inactive');
    }
});

$('#change-user-status-close-btn').click(function(){
    $('#changeUserStatusModal').modal('hide');
});