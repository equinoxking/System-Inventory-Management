function userCancel(transaction){
    var modal = new bootstrap.Modal(document.getElementById('cancelTransactionModal'));
    modal.show();
    $('#transaction-cancel-id').val(transaction.id);
}
$(document).ready(function () {
    $('#transaction-cancel-close-btn').click(function () {
        const modalEl = document.getElementById('cancelTransactionModal');
        const instance = bootstrap.Modal.getInstance(modalEl);
        if (instance) instance.hide();
    });
});
$(document).ready(function(){
    $(document).on('submit', '#transaction-cancel-form', function(event){
        event.preventDefault();
        var formData = $('#transaction-cancel-form').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/user/cancel-transaction',
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
                            $('#transaction-cancel-submit-btn').attr('disabled', false);
                        });
                }else if(response.status === 200){
                    Swal.fire({
                    icon: "success",
                    title: "All set!",
                    html: response.message,
                    showConfirmButton: true,
                    }).then(function(){
                        window.location.reload();
                        $('#cancelTransactionModal').modal('hide');
                    });
                }
            },error: function(error){
                console.log(error);
            }
        });
    });
});