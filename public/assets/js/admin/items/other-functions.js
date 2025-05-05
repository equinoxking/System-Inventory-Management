
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

/* Function for received to prevent overflow of quantity */

