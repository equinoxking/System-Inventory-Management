$(document).ready(function() {
    // Trigger the AJAX request when the "Generate Report" button is clicked
    $("#generatePdfReportBtn").click(function() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // CSRF token for security
            },
            url: '/generate-user-ledger-report',  // Your endpoint for generating the full report
            type: 'POST',
            data: {},  // No parameters needed since you're generating the full report
            xhrFields: {
                responseType: 'blob'  // Expecting a Blob (binary large object) response
            },
            beforeSend: function() {
                // Disable the button while the report is being generated
                $('#generatePdfReportBtn').attr('disabled', true);
                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait while we process your request.',
                    allowOutsideClick: false,  // Prevent closing the Swal popup
                    didOpen: () => {
                        Swal.showLoading();  // Show loading spinner
                    }
                });
            },
            success: function(response) {
                let blob = new Blob([response], { type: 'application/pdf' });  // Create a Blob from the response
                let link = document.createElement('a');  // Create an anchor tag for downloading
                let url = window.URL.createObjectURL(blob);  // Create an object URL for the Blob
                const filename = 'transaction-report-' + new Date().toLocaleString('default', { month: 'long' }) + '-' + new Date().getFullYear() + '.pdf';  // Dynamic filename

                link.href = url;
                link.download = filename;  // Set the download attribute

                // Open the PDF in a new tab and automatically trigger the download
                let newWindow = window.open(url, '_blank');
                newWindow.focus();

                link.click();  // Click the anchor to trigger the download

                window.URL.revokeObjectURL(url);  // Clean up the object URL

                Swal.close();  // Close the loading popup
                $('#generatePdfReportBtn').attr('disabled', false);  // Re-enable the button
            },
            error: function(error) {
                console.error('Error generating report:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'There was an issue generating the report. Please try again.',
                    icon: 'error'
                });
                $('#generatePdfReportBtn').attr('disabled', false);  // Re-enable the button if error occurs
            }
        });
    });
});
