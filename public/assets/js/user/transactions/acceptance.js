function userAcceptance(transaction){
    $('#acceptanceTransactionModal').modal('show');
    let data = JSON.parse(transaction);
    $('#transaction-acceptance-id').val(data.id);
}
$(document).ready(function(){
    $(document).on('submit', '#transaction-acceptance-form', function(event){
        event.preventDefault();
        var formData = $('#transaction-acceptance-form').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/user/acceptance-transactions',
            type: 'PATCH',
            data: formData,
            beforeSend: function() {
                $('#register-btn').attr('disabled', true);
                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait while we process your registration.',
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
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: response.message,
                            showConfirmButton: true,
                        }).then(function() {
                            $('#transaction-acceptance-submit-btn').attr('disabled', false);
                        });
                }else if(response.status === 200){
                    Swal.fire({
                    icon: "success",
                    title: "All set!",
                    html: response.message,
                    showConfirmButton: true,
                    }).then(function(){
                        window.location.reload();
                        $('#acceptanceTransactionModal').modal('hide');
                    });
                }
            },error: function(error){
                console.log(error);
            }
        });
    });
});