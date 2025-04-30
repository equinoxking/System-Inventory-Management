$(document).ready(function() {
    $("#addItem").click(function() {
        $('#createItemModal').modal('show');
    });
    $("#createItem-closeBtn").click(function() {
        $('#createItemModal').modal('hide');
    });
});
$(document).ready(function(){
    $(document).on('submit', '#createItem-form1', function(event){
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: 'Have you reviewed the items before submitting?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: 'No, cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                var formData = $('#createItem-form1').serialize();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/add-item',
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#addItemSubmit-btn').attr('disabled', true);
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
                            });
                        } else if(response.status === 400){
                            var errorMessages = Object.values(response.message).join('<br>');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                html: errorMessages,
                                showConfirmButton: true,
                            }).then(function() {
                                $('#addItemSubmit-btn').attr('disabled', false);
                            });
                        } else if(response.status === 200){
                            Swal.fire({
                                icon: "success",
                                title: "All set!",
                                html: response.message,
                                showConfirmButton: true,
                            }).then(function(){
                                $('#createItem-form')[0].reset();
                                $('#addItemSubmit-btn').attr('disabled', false);
                            });
                        }
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Cancelled',
                    text: 'Your submission was cancelled.',
                });
            }
        });
    });
});

