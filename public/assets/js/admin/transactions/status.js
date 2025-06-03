function changeStatus(transaction){
    $('#transactionStatusModal').modal('show');
    $('#transaction-status-id').val(transaction.id);
}
$('#transaction-status-close-btn').click(function(){
    $('#transactionStatusModal').modal('hide');
});
$(document).ready(function(){
    $(document).on('submit', '#transaction-status-form', function(event){
      event.preventDefault();
      var formData = $('#transaction-status-form').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/change-transaction-status',
            type: 'PATCH',
            data: formData,
            beforeSend: function() {
                $('#transaction-status-submit-btn').attr('disabled', true);
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
                            $('#transaction-status-submit-btn').attr('disabled', false);
                        });
                }else if(response.status === 501){
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: response.message,
                        showConfirmButton: true,
                    }).then(function() {
                        $('#transaction-status-submit-btn').attr('disabled', false);
                        $('#transactionStatusModal').modal('hide');
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