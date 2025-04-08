$(document).ready(function() {
    $("#addItemBtn").click(function() {
        $("#itemForm").css({
            "display": "flex",        
        });
        $("#receivedItemForm").css({
            "display": "none",        
        });
    });
    $('#createItem-closeBtn').click(function(){
        $("#itemForm").css({
            "display": "none",        
        });
    })
});
$(document).ready(function(){
    $(document).on('submit', '#createItem-form', function(event){
        event.preventDefault();  // Prevent the default form submission
        
        // Show SweetAlert confirmation before submitting the form
        Swal.fire({
            title: 'Are you sure?',
            text: 'Please confirm if the data is correct before submitting.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit!',
            cancelButtonText: 'No, cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // User clicked 'Yes, submit!'
                
                // Serialize form data
                var formData = $('#createItem-form').serialize();

                // AJAX call to submit the form data
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/add-item',
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#addItemSubmit-btn').attr('disabled', true);  // Disable the submit button to prevent multiple submissions
                        Swal.fire({
                            title: 'Loading...',
                            text: 'Please wait while we process your request.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();  // Show loading spinner
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
                                title: 'Adding an item validation failed!',
                                html: errorMessages,
                                showConfirmButton: true,
                            }).then(function() {
                                $('#addItemSubmit-btn').attr('disabled', false);
                                $('#createItem-form')[0].reset();  // Reset the form
                            });
                        } else if(response.status === 200){
                            Swal.fire({
                                icon: "success",
                                title: "All set!",
                                html: response.message,
                                showConfirmButton: true,
                            });
                        }
                    },
                    error: function(error){
                        console.log(error);  // Log any unexpected error
                    }
                });
            } else {
                // User clicked 'No, cancel'
                console.log("Form submission canceled");
            }
        });
    });
});
