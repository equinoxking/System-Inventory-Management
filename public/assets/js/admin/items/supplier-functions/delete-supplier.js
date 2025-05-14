function deleteSupplier(supplier){
    var data = JSON.parse(supplier);
    $('#deleteSupplierModal').modal('show'); 
    $('#delete-supplier-id').val(data.id);
}
$('#delete-supplier-close-btn').click(function(){
    $('#deleteSupplierModal').modal('hide');
});
$(document).ready(function() {
    $(document).on('submit', '#delete-supplier-form', function(event){
        event.preventDefault();
        var formData = $('#delete-supplier-form').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/delete-supplier',
            type: 'DELETE',
            data: formData,
            beforeSend: function() {
                $('#delete-supplier-submit-btn').attr('disabled', true);
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
                            $('#delete-supplier-submit-btn').attr('disabled', false);
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