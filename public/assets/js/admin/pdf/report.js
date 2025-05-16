
$(".generateReportBtn").click(function() {
    $("#pdfReportModal").modal('show');
});
$('.pdf-report-close-btn').click(function(){
    $("#pdfReportModal").modal('hide');
});
$(document).ready(function(){
    $(document).on('submit', '#pdf-report-form', function(event){
          event.preventDefault();
          var formData = $('#pdf-report-form').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/generate-report',
            type: 'POST',
            data: formData,
            xhrFields: {
                responseType: 'blob' 
            },
            beforeSend: function() {
                $('#report-submit-btn').attr('disabled', true);
                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait while we process your request.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                let blob = new Blob([response], { type: 'application/pdf' });
                let url = window.URL.createObjectURL(blob);
                const filename = 'inventory-report-' + 
                    new Date().toLocaleString('default', { month: 'long' }) + '-' + 
                    new Date().getFullYear() + '.pdf';

                let newWindow = window.open(); // Open immediately to avoid popup blocker

                if (newWindow) {
                    newWindow.location.href = url;
                    newWindow.focus();
                } else {
                    // Fallback to download if popup blocked
                    let link = document.createElement('a');
                    link.href = url;
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    alert('Popup blocked! File has been downloaded instead and you can view the pdf on the very top on your archive.');
                }

                window.URL.revokeObjectURL(url);

                Swal.close();
                $('#report-submit-btn').attr('disabled', false);
            },
        });
    });
  });