function editReceivedItem(data){
    $('#edit-received-id').val(data.received_id);
    $('#edit-received-quantity').val(data.received_quantity);
    $('#edit-received-item-id').val(data.item_id);
    $('#updateReceivedModal').modal('show');
}
$('#edit-received-close-btn').click(function(){
    $('#updateReceivedModal').modal('hide');
});
$(document).ready(function(){
    $(document).on('submit', '#update-received-status-form', function(event){
        event.preventDefault();
        var formData = $('#update-received-status-form').serialize();
        console.log(formData);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/update-received-item',
            type: 'PATCH',
            data: formData,
            beforeSend: function() {
                $('#update-received-submit-btn').attr('disabled', true);
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
                            $('#update-received-submit-btn').attr('disabled', false);
                        });
                }else if(response.status === 200){
                    Swal.fire({
                    icon: "success",
                    title: "All set!",
                    html: response.message,
                    showConfirmButton: true,
                    }).then(function(){
                        $('#update-received-submit-btn').attr('disabled', false);
                        swal.close();
                        $('#updateReceivedModal').modal('hide');
                    });
                }
            },error: function(error){
                console.log(error);
            }
        });
    });
});