function editReceivedItem(data){
    $('#edit-received-id').val(data.received_id);
    $('#edit-control_number').val(data.control_number);
    $('#edit-received-max-quantity').val(data.max_quantity);
    $('#edit-received-item-id').val(data.item_id);
    $('#editReceivedModal').modal('show');
}
$('#edit-received-close-btn').click(function(){
    $('#editReceivedModal').modal('hide');
});
$(document).ready(function(){
    $(document).on('submit', '#edit-received-form', function(event){
      event.preventDefault();
      var formData = $('#edit-received-form').serialize();
      console.log(formData);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/edit-received-item',
            type: 'PATCH',
            data: formData,
            beforeSend: function() {
                $('#edit-received-submit-btn').attr('disabled', true);
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
                            title: 'Adding an item validation failed!',
                            html: errorMessages,
                            showConfirmButton: true,
                        }).then(function() {
                            $('#addItem-btn').attr('disabled', false);
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