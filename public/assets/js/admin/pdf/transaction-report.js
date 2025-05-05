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

// Watch for visibility change on #timeDivision1
const target = document.getElementById('timeDivision1');

if (target) {
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'style') {
                const isVisible = window.getComputedStyle(target).display !== 'none';
                if (isVisible) {
                    setCurrentTime();
                }
            }
        });
    });

    // Observe changes to attributes (like style)
    observer.observe(target, { attributes: true });
}
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

} else {
    timeDivision.style.display = 'none';
    reasonDivision.style.display = 'none';

}
}
document.addEventListener("DOMContentLoaded", function () {
const selection = document.getElementById("selection");
const userGroup = document.getElementById("user").closest(".form-group");
const adminGroup = document.getElementById("admin").closest(".form-group");

// Handle selection changes
selection.addEventListener("change", function () {
    const value = this.value;

    // Show admin for All, User
    if (value === "All" || value === "User") {
        adminGroup.style.display = "block";
    } else {
        adminGroup.style.display = "none";
    }

    // Show user select only for User
    userGroup.style.display = value === "User" ? "block" : "none";
});

const userSelect = document.getElementById("user");
const adminSelect = document.getElementById("admin");
const submitBtn = document.getElementById("transaction-report-submit-btn");

// Function to handle visibility and validation
function updateVisibilityAndValidation() {
    const selectedOption = selection.value;

    // Reset visibility
    userGroup.style.display = "none";
    adminGroup.style.display = "none";

    // Show fields based on selection
    if (["All", "User"].includes(selectedOption)) {
        adminGroup.style.display = "block";
    }
    if (selectedOption === "User") {
        userGroup.style.display = "block";
    }

    // Validate
    validateForm();
}

// Form validation logic
function validateForm() {
    const selectedOption = selection.value;
    let isValid = false;

    if (selectedOption === "All") {
        isValid = adminSelect.value !== "";
    } else if (selectedOption === "User") {
        isValid = userSelect.value !== "" && adminSelect.value !== "";
    }

    submitBtn.disabled = !isValid;
}

// Event listeners
selection.addEventListener("change", updateVisibilityAndValidation);
userSelect.addEventListener("change", validateForm);
adminSelect.addEventListener("change", validateForm);

// Initial check
updateVisibilityAndValidation();
});
