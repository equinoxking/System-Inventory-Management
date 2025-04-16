/* Prevent item quantity to value of negative */
document.addEventListener('DOMContentLoaded', function () { 
    const quantity = document.getElementById("quantity");
    quantity.addEventListener('input', function () {
        if (Number(quantity.value) < 0 || isNaN(quantity.value)) {
            $('#quantity').val('0');
        }
    });
});
/* Function for monthly and quarterly display */
document.addEventListener('DOMContentLoaded', function () {
    const period = document.getElementById("period");
    period.addEventListener('change', function () {
        const monthRow = document.getElementById("month-row");
        const quarterlyRow = document.getElementById('quarterly-row');
        const signatoriesRow = document.getElementById('signatories-row');
        if (period.value === "Monthly") {
            monthRow.style.display = "block"; 
            quarterlyRow.style.display = "none";
            signatoriesRow.style.display = "block";
        } else if(period.value === "Quarterly") {
            monthRow.style.display = "none"; 
            quarterlyRow.style.display = "block";
            signatoriesRow.style.display = "block";
        }else{
            quarterlyRow.style.display = "none";
            monthRow.style.display = "none";
            signatoriesRow.style.display = "none";
        }
    });
});
/* Function for year */
window.onload = function() {
    // Monthly Select (current year on top)
    var currentYearMonthly = new Date().getFullYear();
    var selectMonthly = document.getElementById('selectedYear'); // Get the monthly select element

    // First, add the current year at the top for monthly selection
    let currentOptionMonthly = document.createElement('option');
    currentOptionMonthly.value = currentYearMonthly;
    currentOptionMonthly.text = currentYearMonthly;
    currentOptionMonthly.selected = true;  // Set the current year as selected by default
    selectMonthly.appendChild(currentOptionMonthly);  // Append to selectMonthly

    // Then, add options for the previous 5 years
    for (var i = currentYearMonthly - 1; i >= currentYearMonthly - 5; i--) {
        let option = document.createElement('option');
        option.value = i;
        option.text = i;
        selectMonthly.appendChild(option);  // Append to selectMonthly
    }

    // Quarterly Select (current year on top)
    var currentYearQuarterly = new Date().getFullYear();
    var selectQuarterly = document.getElementById('yearSelectQuarterly'); // Get the quarterly select element

    // First, add the current year at the top for quarterly selection
    let currentOptionQuarterly = document.createElement('option');
    currentOptionQuarterly.value = currentYearQuarterly;
    currentOptionQuarterly.text = currentYearQuarterly;
    currentOptionQuarterly.selected = true;  // Set the current year as selected by default
    selectQuarterly.appendChild(currentOptionQuarterly);  // Append to selectQuarterly

    // Then, add options for the previous 10 years
    for (var i = currentYearQuarterly - 1; i >= currentYearQuarterly - 10; i--) {
        let option = document.createElement('option');
        option.value = i;
        option.text = i;
        selectQuarterly.appendChild(option);  // Append to selectQuarterly
    }
};
/* Function for received to prevent overflow of quantity */
document.addEventListener('DOMContentLoaded', function () {
    const receivedQuantity = document.getElementById('received_quantity'); // Input for received quantity
    const remainingQuantity = document.getElementById("remaining_quantity"); // Read-only remaining quantity
    const maxQuantity = document.getElementById('max_quantity'); // Read-only max quantity
    
    // Listen for input on receivedQuantity
    receivedQuantity.addEventListener('input', function () {
        const remaining = Number(remainingQuantity.value); // Get remaining quantity
        const received = Number(receivedQuantity.value); // Get received quantity
        const max = Number(maxQuantity.value); // Get max quantity

        // If the sum of receivedQuantity and remainingQuantity exceeds maxQuantity, adjust the receivedQuantity
        if (received + remaining > max) {
            // Limit the receivedQuantity to max - remainingQuantity to avoid exceeding maxQuantity
            receivedQuantity.value = max - remaining;
        }
        
        // Ensure receivedQuantity is never negative
        if (receivedQuantity.value < 0) {
            receivedQuantity.value = 0;
        }
    });
});
/* Function for edit received to prevent overflow of quantity */
document.addEventListener('DOMContentLoaded', function () {
    const receivedQuantity = document.getElementById('edit-received-quantity'); // Input for received quantity
    const remainingQuantity = document.getElementById("edit-received-remaining-quantity"); // Read-only remaining quantity
    const maxQuantity = document.getElementById('edit-received-max-quantity'); // Read-only max quantity
    
    // Listen for input on receivedQuantity
    receivedQuantity.addEventListener('input', function () {
        const remaining = Number(remainingQuantity.value); // Get remaining quantity
        const received = Number(receivedQuantity.value); // Get received quantity
        const max = Number(maxQuantity.value); // Get max quantity

        // If the sum of receivedQuantity and remainingQuantity exceeds maxQuantity, adjust the receivedQuantity
        if (received + remaining > max) {
            // Limit the receivedQuantity to max - remainingQuantity to avoid exceeding maxQuantity
            receivedQuantity.value = max - remaining;
        }
        
        // Ensure receivedQuantity is never negative
        if (receivedQuantity.value < 0) {
            receivedQuantity.value = 0;
        }
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const containers = document.querySelectorAll('.table-container');
    const urlParams = new URLSearchParams(window.location.search);
    const section = urlParams.get('section') || 'items'; // Default to 'items'

    // Function to hide all containers
    function hideAllContainers() {
        containers.forEach(container => {
            container.style.display = 'none';
        });
    }

    // Function to show a specific container
    function showContainer(containerId) {
        hideAllContainers();  // Hide all containers
        const selectedContainer = document.getElementById(containerId);
        if (selectedContainer) {
            selectedContainer.style.display = 'block'; // Show the selected container
        }
    }

    // Initially show the container based on the URL parameter
    showContainer(`${section}-container`);

    // Handle the change event for the dropdown
    document.getElementById('container').addEventListener('change', function () {
        const selectedValue = this.value;  // Get selected value from dropdown
        showContainer(`${selectedValue}-container`); // Show the corresponding container
    });

    // Handle anchor clicks for each section (items, deliveries, etc.)
    const anchorLinks = document.querySelectorAll('.card a');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault();  // Prevent default link action
            const section = this.dataset.section;  // Get section from data-section attribute
            showContainer(`${section}-container`); // Show the corresponding container
        });
    });
});
