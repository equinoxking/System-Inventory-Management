
$(document).ready(function() {
    $('#createUnit').click(function(){
        $('#addUnitModal').modal('show');
    });
    $('#add-unit-close-btn').click(function(){
        $('#addUnitModal').modal('hide');
    });
    $(document).on('submit', '#add-unit-form', function(event){
        event.preventDefault();
        var formData = $('#add-unit-form').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/add-unit',
            type: 'POST',
            data: formData,
            beforeSend: function() {
                $('#add-unit-submit-btn').attr('disabled', true);
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
                            $('#add-unit-submit-btn').attr('disabled', false);
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