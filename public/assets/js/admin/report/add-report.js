$(document).ready(function() {
    $("#addReportBtn").click(function() {
        $('#addReportModal').modal('show');
    });
    $("#add-report-close-btn").click(function() {
        $('#addReportModal').modal('hide');
    });
    $(document).ready(function() {
        // Handle view PDF button click
        $('.view-pdf-btn').on('click', function() {
            var filename = $(this).data('filename'); // Get the PDF filename from the button's data attribute
            var pdfUrl = '/pdf-reports/' + filename; // Correct URL construction for the PDF
            // Set the src of the iframe to the PDF file
            $('#pdfFrame').attr('src', pdfUrl);
    
            // Show the modal
            $('#pdfPreviewModal').modal('show');
        });
    
        // Close the modal and reset the iframe src when the modal is closed
        $('#pdf-preview-close-btn').on('click', function() {
            $('#pdfFrame').attr('src', ''); // Clear the iframe src to stop the PDF from loading
        });
    });
    
    $('#pdf-preview-close-btn').click(function() {
        $('#pdfPreviewModal').modal('hide');
    });
    $(document).on('submit', '#add-report-form', function(event) {
        event.preventDefault();
    
        Swal.fire({
            title: 'Submit Report?',
            text: 'Please review all information carefully before submitting. Once submitted, the report will be saved and cannot be edited.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                let formData = new FormData($('#add-report-form')[0]);
    
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/add-report',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#add-report-submit-btn').attr('disabled', true);
                        Swal.fire({
                            title: 'Loading...',
                            text: 'Please wait while we process your request.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function (response) {
                        if (response.status === 500) {
                            Swal.fire({
                                icon: "error",
                                title: "Error!",
                                text: response.message,
                                showConfirmButton: true,
                            });
                        } else if (response.status === 400) {
                            var errorMessages = Object.values(response.message).join('<br>');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                html: errorMessages,
                                showConfirmButton: true,
                            }).then(function () {
                                $('#add-report-submit-btn').attr('disabled', false);
                            });
                        } else if (response.status === 200) {
                            Swal.fire({
                                icon: "success",
                                title: "All set!",
                                html: response.message,
                                showConfirmButton: true,
                            }).then(function () {
                                $('#add-report-submit-btn').attr('disabled', false);
                                $('#addReportModal').modal('hide');
                                location.reload();
                            });
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
        });
    });    
});