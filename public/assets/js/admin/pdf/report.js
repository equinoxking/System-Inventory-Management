
$("#pdfBtn").click(function() {
    $("#pdfReportModal").modal('show');
});
$('#pdf-report-close-btn').click(function(){
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
            success: function(response){
                let blob = new Blob([response], { type: 'application/pdf' });
                let link = document.createElement('a');
                let url = window.URL.createObjectURL(blob);
                const filename = 'inventory-report-' + new Date().toLocaleString('default', { month: 'long' }) + '-' + new Date().getFullYear() + '.pdf';
            
                link.href = url;
                link.download = filename;  

                let newWindow = window.open(url, '_blank');
                newWindow.focus();

                link.click();
            
                window.URL.revokeObjectURL(url);

                Swal.close();
                $('#report-submit-btn').attr('disabled', false);
            }, 
            error: function(error){
                console.log(error);
            }
            
        });
    });
  });