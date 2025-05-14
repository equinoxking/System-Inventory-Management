// document.addEventListener("DOMContentLoaded", function () {
//     const modalElement = document.querySelector('.generateTransactionPdfModal');
//     const modal = new bootstrap.Modal(modalElement);

//     const triggerButton = document.querySelector('.pdfTransactionGenerationBtn');
//     triggerButton.addEventListener('click', function () {
//         modal.show();
//     });
// });

  
$('.pdfTransactionGenerationBtn').click(function(){
    $("#generateTransactionPdfModal").modal('show');
});
$('.generate-transaction-pdf-close-btn').click(function(){
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
 function setCurrentTime() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const currentTime = `${hours}:${minutes}`;

        const timeInput = document.getElementById('timeRelease');
        if (timeInput) {
            timeInput.value = currentTime;
        }
    }

    // Trigger both functions on page load
    document.addEventListener('DOMContentLoaded', function () {
        toggleSelection();      // Show or hide time/reason sections based on default status
        setCurrentTime();       // Prefill the current time if needed
    });

function toggleSelection() {
const status = document.getElementById('status').value;
const timeDivision = document.getElementById('timeDivision1');
const reasonDivision = document.getElementById('reasonDivision');
    if (status === '3') {
        reasonDivision.style.display = 'block';
        timeDivision.style.display = 'none';

    } else if (status === '2') {
        reasonDivision.style.display = 'none';
        timeDivision.style.display = 'block';

    } 
}
document.addEventListener('DOMContentLoaded', function () {
    const selection = document.getElementById('selection');
    const admin = document.getElementById('admin');
    const submitBtn = document.getElementById('transaction-report-submit-btn');

    // Function to toggle the submit button
    function toggleSubmitButton() {
        if (selection.value === '' || admin.value === '') {
            submitBtn.disabled = true;
        } else {
            submitBtn.disabled = false;
        }
    }

    // Initial check
    toggleSubmitButton();

    // Add event listeners
    selection.addEventListener('change', toggleSubmitButton);
    admin.addEventListener('change', toggleSubmitButton);
});
