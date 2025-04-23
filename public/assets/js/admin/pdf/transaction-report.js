document.addEventListener("DOMContentLoaded", function () {
    const modal = new bootstrap.Modal(document.getElementById('generateTransactionPdfModal'));
    document.getElementById('pdfGenerationBtn').addEventListener('click', function () {
      modal.show();
    });
  });
$('#generate-transaction-pdf-close-btn').click(function(){
    $("#generateTransactionPdfModal").modal('hide');
});
$(document).ready(function(){
    $(document).on('submit', '#generate-transaction-form', function(event){
          event.preventDefault();
          var formData = $('#generate-transaction-form').serialize();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/generate-transaction-report',
            type: 'POST',
            data: formData,
            xhrFields: {
                responseType: 'blob' 
            },
            beforeSend: function() {
                $('#transaction-report-submit-btn').attr('disabled', true);
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
                let blob = new Blob([response], { type: 'application/pdf' });
                let link = document.createElement('a');
                let url = window.URL.createObjectURL(blob);
                const filename = 'transaction-report-' + new Date().toLocaleString('default', { month: 'long' }) + '-' + new Date().getFullYear() + '.pdf';
            
                link.href = url;
                link.download = filename;  

                let newWindow = window.open(url, '_blank');
                newWindow.focus();

                link.click();
            
                window.URL.revokeObjectURL(url);

                Swal.close();
                $('#transaction-report-submit-btn').attr('disabled', false);
            }, 
            error: function(error){
                console.log(error);
            }
            
        });
    });
});