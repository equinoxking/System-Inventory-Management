function editSupplier(supplier){
    var data = JSON.parse(supplier);
    $('#editSupplierModal').modal('show'); 
    $('#edit-supplier-id').val(data.id);
    $('#edit-supplier-name').val(data.name);
    $('#edit-supplier-symbol').val(data.symbol);
    $('#edit-supplier-control_number').val(data.control_number);
    $('#edit-supplier-description').val(data.description);
}
$('#edit-supplier-close-btn').click(function(){
    $('#editSupplierModal').modal('hide');
});
$(document).ready(function() {
    $(document).on('submit', '#edit-supplier-form', function(event){
        event.preventDefault();
        var formData = $('#edit-supplier-form').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/update-supplier',
            type: 'PATCH',
            data: formData,
            beforeSend: function() {
                $('#edit-supplier-submit-btn').attr('disabled', true);
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
                            $('#edit-supplier-submit-btn').attr('disabled', false);
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