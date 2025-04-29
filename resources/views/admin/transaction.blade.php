@extends('admin.layout.admin-layout')
@section('content')
<div class="container-fluid mt-3 mb-3">
    <div class="row align-items-center">
        <div class="col-md-8">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb"> 
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Transactions</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-end">
            <!-- Generate PDF Button Properly Aligned -->
            <button type="button" class="btn btn-success" id="requestBtn" title="Request item button">
                <i class="fa-solid fa-plus"></i>
            </button>
            <button type="button" class="btn btn-info" id="pdfGenerationBtn" title="Generate PDF button">
                <i class="fa-solid fa-file-pdf"></i> 
            </button>
        </div>
        <div class="row mt-2">
            <div class="col-md-12" style="text-align: left">
                <h4><strong >PENDING TRANSACTIONS</strong></h4>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid card w-100 shadow rounded p-4" id="requestForm" style="max-height: 500px; overflow-y: auto; background-color: #f8f9fa; display:none; border: 2px solid #ddd;">
    <!-- Form Header -->
    <div class="d-flex justify-content-between align-items-center bg-success text-light p-3 rounded-top">
        <h4 class="m-0 text-center flex-grow-1"><strong>REQUEST ITEM FORM</strong></h4>
        <button type="button" id="requestItem-closeBtn" class="btn btn-danger p-2">&times;</button>
    </div>
    <!-- Form Body -->
    <form action="" id="requestItem-form" class="p-3">
        @csrf
        <div class="col-md-12 d-flex justify-content-end">
            <button type="button" id="requestItemReceived-btn" class="btn btn-primary rounded px-4 py-2">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
        <div id="requestItem-container">
            <div class="row mb-3 mt-2 request-item-row">
                <div class="col-md-3 form-group">
                    <label for="itemName" class="font-weight-bold">Item Name</label>
                    <input type="text" class="search-request-items form-control" name="requestItemName[]" id="requestItemName" placeholder="Search items..." autocomplete="off"/>
                    <ul class="item-results" style="display: none; max-height: 200px; overflow-y: auto;"></ul>
                    <input type="text" class="selected-item-id" id="requestItemId" name="requestItemId[]" hidden>
                </div>
                <div class="col-md-1 form-group">
                    <label for="quantity" class="font-weight-bold">Quantity</label>
                    <input type="number" class="form-control requestQuantity" name="requestQuantity[]" id="requestQuantity" placeholder="Enter quantity" min="1">
                </div>
                <div class="col-md-2 form-group">
                    <label for="maxQuantity" class="font-weight-bold">Available Quantity</label>
                    <input type="number" class="form-control quantity requestMaxQuantity" name="requestMaxQuantity[]" id="requestMaxQuantity" readonly>
                </div>
                <div class="col-md-1 form-group">
                    <label for="action" class="font-weight-bold">Action</label><br>
                    <button type="button" class="remove-request-item btn btn-danger"> <i class="fa-solid fa-eraser mr-1"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 d-flex justify-content-end">
                <div class="col-md-1 form-group">
                    <label for="" class="font-weight-bold">&nbsp</label>
                    <button type="reset" class="btn btn-secondary rounded px-4 py-2 me-3 w-100">Clear</button>
                </div>
                <div class="col-md-1 form-group">
                    <label for="" class="font-weight-bold">&nbsp</label>
                    <button type="submit" id="requestItemSubmit-btn" class="btn btn-success rounded px-4 py-2 w-100">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="container-fluid card w-100">
    <div class="row">
        <div class="col-md-12">
            <table id="transactionTable" style="font-size: 10px">
                <thead>
                    <th>Time Request</th>
                    <th>Transaction Number</th>
                    <th>Stock On Hand</th>
                    <th>Quantity</th>
                    <th>UoM</th>
                    <th>Item Name</th>
                    <th>Requestor</th>
                    <th>Date/Time Acted</th>
                    <th>Request Aging</th>
                    <th>Released by</th>
                    <th>Time Released</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Action</th>
                </thead>
                <tbody>
                </tbody>
                <tfoot>

                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-12" style="text-align: left">
            <h4><strong>ACTED TRANSACTIONS</strong></h4>
        </div>
    </div>
</div>
<div class="container-fluid card w-100">
    <div class="row">
        <div class='col-md-12'>
            <table id="transactionHistoryTable" style="font-size: 10px;">
                <thead>
                    <th>Time Request</th>
                    <th>Transaction Number</th>
                    <th>Stock On Hand</th>
                    <th>Quantity</th>
                    <th>UoM</th>
                    <th>Item Name</th>
                    <th>Requestor</th>
                    <th>Date/Time Acted</th>
                    <th>Request Aging</th>
                    <th>Released by</th>
                    <th>Time Released</th>
                    <th>Availability Aging</th>
                    <th class="text-center">Status</th>
                    <th>Remarks</th>
                </thead>
                <tbody>
                   
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
<div class="modal fade" id="transactionStatusModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" style="color:white;">TRANSACTION STATUS FORM</h5>
                    <button type="button" id="transaction-status-close-btn" data-dismiss="modal" class="btn btn-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <form id="transaction-status-form">
                        <div class="form-group" hidden>
                            <label for="transactionStatusID">Transaction ID</label>
                            <input type="text" class="form-control" name="transaction-status-id" id="transaction-status-id">
                        </div>
                        <div class="form-group" >
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" onchange="toggleSelection()">
                                <option value="">Select Status</option>
                                @foreach ($statuses as $status)
                                    @if ($status->name != 'Pending' && $status->name != 'Canceled')
                                        <option value="{{ $status->id }}">{{ $status->name }}</option> 
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="timeDivision" style="display:none;">
                            <label for="releaseTime">Release Time</label><br>
                            <input type="time" class="form-control" id="time" value="" name="time">
                        </div>
                        <div class="form-group" style="display:none;" id="reasonDivision">
                            <label for="reason">Reason</label><br>
                            <textarea name="reason" id="reason" cols="5" rows="5" class="form-control"></textarea>
                        </div>                        
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-warning" id="transaction-status-submit-btn">SUBMIT</button>
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
                    <h5 class="modal-title" style="color:white;">TRANSACTION PDF CUSTOMIZE FORM</h5>
                    <button type="button" id="generate-transaction-pdf-close-btn" data-dismiss="modal" class="btn btn-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <form id="generate-transaction-form">
                    <div class="form-group">
                        <label for="selectOption">Pdf Option</label>
                        <select name="selection" id="selection" class="form-control">
                            <option value="">Select Option</option>
                            <option value="All">All</option>
                            <option value="User">User</option>
                            <option value="Monthly">Monthly</option>
                        </select>
                    </div>
                    <div class="form-group" style="display: none">
                        <label for="user">Users</label>
                        <select name="user_selection" id="user" class="form-control">
                            <optgroup label="Users">
                                @php
                                    $uniqueUsers = collect();
                                @endphp
                                @foreach ($transactionUsers as $transaction)
                                    @if ($transaction->client && !$uniqueUsers->contains('id', $transaction->client->id))
                                        @php
                                            $uniqueUsers->push($transaction->client);
                                        @endphp
                                        <option value="user-{{ $transaction->client->id }}">
                                            {{ $transaction->client->full_name }}
                                        </option>
                                    @endif
                                @endforeach
                            </optgroup>
                        
                            <optgroup label="Admins">
                                @php
                                    $uniqueAdmins = collect();
                                @endphp
                                @foreach ($transactionUsers as $transaction)
                                    @if ($transaction->admin && !$uniqueAdmins->contains('id', $transaction->admin->id))
                                        @php
                                            $uniqueAdmins->push($transaction->admin);
                                        @endphp
                                        <option value="admin-{{ $transaction->admin->id }}">
                                            {{ $transaction->admin->full_name }}
                                        </option>
                                    @endif
                                @endforeach
                            </optgroup>
                        </select>                        
                    </div>
                    <div class="form-group" style="display: none">
                        <label for="month">Month</label>
                        <select name="month" id="month" class="form-control">
                            <option value="">Select Month</option>
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
                        <label for="year">Year</label>
                        <select name="year" id="year" class="form-control">
                            <option value="">Select Year</option>
                        </select>
                    </div>
                    <div class="form-group" style="display: none">
                        <label for="admin">Prepared By:</label>
                        <select name="admin" id="admin" class="form-control">
                            <option value="">Select Admin</option>
                            @foreach ($admins as $admin)
                                <option value="{{ $admin->id }}"> {{ $admin->full_name }} </option>
                            @endforeach
                        </select>
                    </div>        
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-info" id="transaction-report-submit-btn">SUBMIT</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/js/admin/transactions/status.js') }}"></script>
<script src="{{ asset('assets/js/admin/transactions/request-item.js') }}"></script>
<script src="{{ asset('assets/js/admin/transactions/search-items.js') }}"></script>
<script src="{{ asset('assets/js/admin/pdf/transaction-report.js') }}"></script>
<script>
    window.onload = function() {
    const options = { timeZone: 'Asia/Manila', hour12: false };
    const now = new Date();
    const timeInManila = new Intl.DateTimeFormat('en-GB', {
        hour: '2-digit',
        minute: '2-digit',
        timeZone: 'Asia/Manila'
    }).format(now);
    const [hours, minutes] = timeInManila.split(':');
    const currentTime = `${hours}:${minutes}`;
    document.getElementById('time').value = currentTime;
};
function toggleSelection() {
    const status = document.getElementById('status').value;
    const timeDivision = document.getElementById('timeDivision');
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
    const monthGroup = document.getElementById("month").closest(".form-group");
    const adminGroup = document.getElementById("admin").closest(".form-group");
    const yearSelect = document.getElementById("year");

    // Populate year dropdown
    const currentYear = new Date().getFullYear();
    for (let i = 0; i < 10; i++) {
        const year = currentYear - i;
        const option = document.createElement("option");
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
    }

    // Handle selection changes
    selection.addEventListener("change", function () {
        const value = this.value;

        // Show admin for All, User, Monthly
        if (value === "All" || value === "User" || value === "Monthly") {
            adminGroup.style.display = "block";
        } else {
            adminGroup.style.display = "none";
        }

        // Show user select only for User
        userGroup.style.display = value === "User" ? "block" : "none";

        // Show month/year only for Monthly
        monthGroup.style.display = value === "Monthly" ? "block" : "none";
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const selection = document.getElementById("selection");
    const userSelect = document.getElementById("user");
    const monthSelect = document.getElementById("month");
    const yearSelect = document.getElementById("year");
    const adminSelect = document.getElementById("admin");
    const submitBtn = document.getElementById("transaction-report-submit-btn");

    const userGroup = userSelect.closest(".form-group");
    const monthGroup = monthSelect.closest(".form-group");
    const adminGroup = adminSelect.closest(".form-group");

    // Populate Year dropdown
    const currentYear = new Date().getFullYear();
    for (let i = 0; i < 10; i++) {
        const year = currentYear - i;
        const option = document.createElement("option");
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
    }

    function updateVisibilityAndValidation() {
        const selectedOption = selection.value;

        // Reset visibility
        userGroup.style.display = "none";
        monthGroup.style.display = "none";
        adminGroup.style.display = "none";

        // Show fields based on selection
        if (["All", "User", "Monthly"].includes(selectedOption)) {
            adminGroup.style.display = "block";
        }
        if (selectedOption === "User") {
            userGroup.style.display = "block";
        }
        if (selectedOption === "Monthly") {
            monthGroup.style.display = "block";
        }

        // Validate
        validateForm();
    }

    function validateForm() {
        const selectedOption = selection.value;
        let isValid = false;

        if (selectedOption === "All") {
            isValid = adminSelect.value !== "";
        } else if (selectedOption === "User") {
            isValid = userSelect.value !== "" && adminSelect.value !== "";
        } else if (selectedOption === "Monthly") {
            isValid = monthSelect.value !== "" && yearSelect.value !== "" && adminSelect.value !== "";
        }

        submitBtn.disabled = !isValid;
    }

    // Event listeners
    selection.addEventListener("change", updateVisibilityAndValidation);
    userSelect.addEventListener("change", validateForm);
    monthSelect.addEventListener("change", validateForm);
    yearSelect.addEventListener("change", validateForm);
    adminSelect.addEventListener("change", validateForm);

    // Initial check
    updateVisibilityAndValidation();
});
</script>