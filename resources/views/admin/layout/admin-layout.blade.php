<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSIMS Admin</title>
    <link rel="icon" href="{{ asset('assets/images/LOGO.webp') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/admin/styles.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <header>
            @include('admin.layout.header')
        </header>
        <div class="content">
            <main>
                @yield('content') 
            </main>
        </div>
       
    
        <footer>
            @include('admin.layout.footer')
        </footer>
    </div>
</body>
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js" integrity="sha512-b+nQTCdtTBIRIbraqNEwsjB6UvL3UEMkXnhzd8awtCYh0Kcsjl9uEgwVFVbhoj3uu1DO1ZMacNvLoyJJiNfcvg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
<script src="{{ asset('assets/js/admin/logout.js') }}"></script>
<script src="{{ asset('assets/js/admin/data-tables/data-tables.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/add-item.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#generateReportBtn').click(function(){
            $('#pdfReportModal').modal('show');
        });
    });
</script>
<div id="pdf-container" style="margin-top: 20px;"></div>
<div class="modal fade" id="pdfReportModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" style="color:white;">INVENTORY REPORT CUSTOMIZE FORM</h5>
                    <button type="button" data-dismiss="modal" class="btn pdf-report-close-btn btn-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <form id="pdf-report-form">
                        <div class="form-group">
                            <label for="period" class="font-weight-bold">Period</label>
                            <select name="period" id="period" class="form-control">
                                <option value="">Select period</option>
                                <option value="Monthly">Monthly</option>
                                <option value="Quarterly">Quarterly</option>
                            </select>
                        </div>
                        <div class="form-group" id="month-row" style="display: none">
                            <label for="month" class="font-weight-bold">Month</label>
                            <select name="month" id="month" class="form-control">
                                <option value="">Select month</option>
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                            <label for="selectedYear" class="font-weight-bold">Select Year</label>
                            <select id="selectedYear" name="monthlySelectedYear" class="form-control">
                                <option value="">Select a Year</option>
                            </select>
                        </div>
                        <div class="form-group" id="quarterly-row" style="display: none">
                            <label for="quarterly" class="font-weight-bold">Quarterly</label>
                            <select name="quarterly" id="quarterly" class="form-control">
                                <option value="">Select quarterly</option>
                                <option value="1-2-3">First Quarter</option>
                                <option value="4-5-6">Second Quarter</option>
                                <option value="7-8-9">Third Quarter</option>
                                <option value="10-11-12">Fourth Quarter</option>
                            </select>
                            <label for="selectedYear" class="font-weight-bold">Select Year</label>
                            <select id="yearSelectQuarterly" name="selectedYear" class="form-control">
                                <option value="">Select a Year</option>
                            </select>
                        </div>
                        <div class="form-group" id="signatories-row" style="display: none">
                            <label for="prepared" class="font-weight-bold">Prepared By:</label>
                            <select name="prepared" id="prepared" class="form-control">
                                <option value="">Select Prepared By:</option>
                                @foreach ($admins as $admin)
                                    <option value="{{ $admin->id }}">{{ $admin->full_name }}</option>
                                @endforeach
                            </select>
                        </div>                        
                    </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-info" id="report-submit-btn">SUBMIT</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="generateTransactionPdfModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" style="color:white;">USER LEDGER PDF CUSTOMIZE FORM</h5>
                    <button type="button" data-dismiss="modal" class="btn btn-danger generate-transaction-pdf-close-btn" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <form id="generate-transaction-form">
                        <div class="form-group">
                            <label for="selectOption" class="font-weight-bold">Option</label>
                            <select name="selectOption" id="selectOption" class="form-control">
                                <option value="">Select Option</option>
                                <option value="User">User</option>
                                <option value="Division">Division</option>
                            </select>
                        </div>

                        <div class="form-group" id="user-select-group">
                            <label for="selection" class="font-weight-bold">User</label>
                            <select name="selection" id="selection" class="form-control">
                                <option value="">Select User</option>
                                <option value="All">All</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->full_name }}</option> 
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" id="division-select-group" style="display:none;">
                            <label for="division" class="font-weight-bold">Division</label>
                            <select name="division" id="division" class="form-control">
                                <option value="">Select Division</option>
                                <option value="first division">First Division</option>
                                <option value="second division">Second Division</option>
                                <option value="third division">Third Division</option>
                                <option value="fourth division">Fourth Division</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="admin" class="font-weight-bold">Prepared By:</label>
                            <select name="admin" id="admin" class="form-control">
                                <option value="">Select Admin</option>
                                @foreach ($admins as $admin)
                                    <option value="{{ $admin->id }}"> {{ $admin->full_name }} </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="modal-footer">
                                <div class="col-md-3 form-group">
                                    <button type="submit" class="btn btn-info" id="transaction-report-submit-btn">SUBMIT</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/admin/pdf/report.js') }}"></script>
<script src="{{ asset('assets/js/admin/pdf/transaction-report.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const periodSelect = document.getElementById('period');
    const monthSelect = document.getElementById('month');
    const yearMonthly = document.getElementById('selectedYear');
    const quarterSelect = document.getElementById('quarterly');
    const yearQuarterly = document.getElementById('yearSelectQuarterly');
    const preparedSelect = document.getElementById('prepared');
    const submitBtn = document.getElementById('report-submit-btn');

    function isFutureDate(period) {
        const currentDate = new Date();
        const currentMonth = currentDate.getMonth(); // 0-based
        const currentYear = currentDate.getFullYear();

        if (period === 'Monthly') {
            const selectedMonth = parseInt(monthSelect.value); // 1-based
            const selectedYear = parseInt(yearMonthly.value);

            if (selectedYear > currentYear || (selectedYear === currentYear && selectedMonth - 1 > currentMonth)) {
                alert("You cannot generate a report for a future month.");
                return true;
            }
        } else if (period === 'Quarterly') {
            const selectedQuarter = parseInt(quarterSelect.value);
            const selectedYear = parseInt(yearQuarterly.value);
            const currentQuarter = Math.floor(currentMonth / 3) + 1;

            if (selectedYear > currentYear || (selectedYear === currentYear && selectedQuarter > currentQuarter)) {
                alert("You cannot generate a report for a future quarter.");
                return true;
            }
        }

        return false;
    }

    function validateForm() {
        const period = periodSelect.value;
        const prepared = preparedSelect.value;

        let isValid = false;

        if (period === 'Monthly') {
            const month = monthSelect.value;
            const year = yearMonthly.value;
            isValid = (month !== '' && year !== '' && prepared !== '');

        } else if (period === 'Quarterly') {
            const quarter = quarterSelect.value;
            const year = yearQuarterly.value;
            isValid = (quarter !== '' && year !== '' && prepared !== '');
        }

        submitBtn.disabled = !isValid;
    }

    periodSelect.addEventListener('change', function () {
        const selected = this.value;
        document.getElementById('month-row').style.display = selected === 'Monthly' ? 'block' : 'none';
        document.getElementById('quarterly-row').style.display = selected === 'Quarterly' ? 'block' : 'none';
        document.getElementById('signatories-row').style.display = selected !== '' ? 'block' : 'none';

        validateForm();
    });

    [monthSelect, yearMonthly, quarterSelect, yearQuarterly, preparedSelect]
        .forEach(el => el.addEventListener('change', validateForm));

    // ⛔ Prevent submission if future date
    submitBtn.addEventListener('click', function (e) {
        const period = periodSelect.value;

        if (isFutureDate(period)) {
            e.preventDefault(); // Stop form submission or PDF generation
        }
    });

    validateForm(); // Initial call
});
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
  document.getElementById('selectOption').addEventListener('change', function() {
        const val = this.value;
        const userGroup = document.getElementById('user-select-group');
        const divisionGroup = document.getElementById('division-select-group');

        if (val === 'User') {
            userGroup.style.display = 'block';
            divisionGroup.style.display = 'none';
        } else if (val === 'Division') {
            userGroup.style.display = 'none';
            divisionGroup.style.display = 'block';
        } else {
            userGroup.style.display = 'none';
            divisionGroup.style.display = 'none';
        }
    });

  const selectOption = document.getElementById('selectOption');
    const selection = document.getElementById('selection');
    const division = document.getElementById('division');
    const admin = document.getElementById('admin');
    const submitBtn = document.getElementById('transaction-report-submit-btn');

    // Show/hide user or division selects based on option selected
    function toggleSelects() {
        if (selectOption.value === 'User') {
            document.getElementById('user-select-group').style.display = 'block';
            document.getElementById('division-select-group').style.display = 'none';
        } else if (selectOption.value === 'Division') {
            document.getElementById('user-select-group').style.display = 'none';
            document.getElementById('division-select-group').style.display = 'block';
        } else {
            document.getElementById('user-select-group').style.display = 'none';
            document.getElementById('division-select-group').style.display = 'none';
        }
    }

    function validationForm() {
        const optionVal = selectOption.value.trim();
        const userVal = selection.value.trim();
        const divisionVal = division.value.trim();
        const adminVal = admin.value.trim();

        // Conditions:
        // option must be selected AND
        // admin must be selected AND
        // if option is User -> userVal must be selected
        // if option is Division -> divisionVal must be selected

        if (
            optionVal === '' ||
            adminVal === '' ||
            (optionVal === 'User' && userVal === '') ||
            (optionVal === 'Division' && divisionVal === '')
        ) {
            submitBtn.disabled = true;
        } else {
            submitBtn.disabled = false;
        }
    }

    // Event listeners
    selectOption.addEventListener('change', () => {
        toggleSelects();
        validationForm();
    });
    selection.addEventListener('change', validationForm);
    division.addEventListener('change', validationForm);
    admin.addEventListener('change', validationForm);

    // Initialize on page load
    toggleSelects();
    validationForm();

</script>

