function editUnit(unit){
    var data = JSON.parse(unit);
    $('#editUnitModal').modal('show'); 
    $('#edit-unit-id').val(data.id);
    $('#edit-unit-name').val(data.name);
    $('#edit-unit-symbol').val(data.symbol);
    $('#edit-unit-control_number').val(data.control_number);
    $('#edit-unit-description').val(data.description);
}
$('#edit-unit-close-btn').click(function(){
    $('#editUnitModal').modal('hide');
});
$(document).ready(function() {
    $(document).on('submit', '#edit-unit-form', function(event){
        event.preventDefault();
        var formData = $('#edit-unit-form').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/update-unit',
            type: 'PATCH',
            data: formData,
            beforeSend: function() {
                $('#edit-unit-submit-btn').attr('disabled', true);
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
                            $('#edit-unit-submit-btn').attr('disabled', false);
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