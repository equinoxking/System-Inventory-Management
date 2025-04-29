<div class="container-fluid card w-100 shadow rounded p-4" id="itemForm" style="max-height: 500px; overflow-y: auto; background-color: #f8f9fa; display:none; border: 2px solid #ddd;">
    <!-- Form Header -->
    <div class="d-flex justify-content-between align-items-center bg-success text-white p-3 rounded-top">
        <h4 class="m-0 text-center flex-grow-1"><strong>CREATE ITEM FORM</strong></h4>
        <button type="button" id="createItem-closeBtn" class="btn btn-danger p-2">
            &times;
        </button>
    </div>
    <!-- Form Body -->
    <form action="" id="createItem-form" class="p-3">
        <div class="col-md-12 d-flex justify-content-end">
            <button type="button" id="addItem-btn" class="btn btn-primary rounded px-4 py-2" title="Add item row">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
        <div id="item-container">
            <div class="row mb-3 mt-2 item-row">
                <!-- Category -->
                <div class="col-md-3 form-group">
                    <label for="category" class="font-weight-bold">Category</label>
                    <input type="text" class="search-category form-control" name="category[]" placeholder="Search categories..." autocomplete="off"/>
                    <ul class="category-results" style="display: none; max-height: 300px; overflow-y: auto;"></ul>
                    <input type="text" class="selected-category-id" id="category" name="categoryId[]" hidden>
                </div>
                <!-- Item Name -->
                <div class="col-md-3 form-group">
                    <label for="itemName" class="font-weight-bold">Item Name</label>
                    <textarea name="itemName[]" class="form-control" cols="5" rows="1"></textarea>
                </div>
                <!-- Item Unit -->
                <div class="col-md-2 form-group">
                    <label for="itemUnit" class="font-weight-bold">Unit of Measurement</label>
                    <input type="text" class="search-unit form-control" name="unit[]"  placeholder="Search unit..." autocomplete="off"/>
                    <ul class="unit-results" style="display: none; max-height: 200px; overflow-y: auto;"></ul>
                    <input type="text" class="selected-unit-id" name="unitId[]" hidden>
                </div>
                <!-- Item Quantity -->
                <div class="col-md-1 form-group">
                    <label for="quantity" class="font-weight-bold">Stock on Hand</label>
                    <input type="number" class="form-control" id="quantity" name="quantity[]" placeholder="Enter quantity" min="0">
                </div>
                <div class="col-md-1 form-group">
                    <label for="buffer" class="font-weight-bold">Buffer Stock</label>
                    <input type="number" class="form-control" id="buffer" name="buffer[]" placeholder="Enter buffer stock" min="0">
                </div>
                <div class="col-md-1 form-group">
                    <label for="action" class="font-weight-bold">Action</label><br>
                    <button type="button" class="remove-add-item btn btn-danger" title="Erase item row button"><i class="fa-solid fa-eraser"></i></button>
                </div>
            </div>
        </div>
            <div class="row">
                <div class="col-md-12 d-flex justify-content-end">
                    <div class="col-md-1 form-group">
                        <label for="" class="font-weight-bold">&nbsp</label>
                        <button type="reset" class="btn btn-secondary rounded px-4 py-2 me-3 form-control">Clear</button>
                    </div>
                    <div class="col-md-1 form-group">
                        <label for="" class="font-weight-bold">&nbsp</label>
                        <button type="submit" id="addItemSubmit-btn" class="btn btn-success rounded px-4 py-2 form-control">Submit</button>
                    </div>
                </div>
            </div>
    </form>    
</div>
@php
    
@endphp
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-9" style="text-align: left">
            <h4><strong>INVENTORY</strong></h4>
        </div>
        <div class="col-md-3" style="text-align: right">
            <button type="button" class="btn btn-success" id="addItemBtn" title="Add item button">
                <i class="fa-solid fa-plus"></i>
            </button>
            <button type="button" class="btn btn-info" id="pdfBtn" title="Generate PDF button">
                <i class="fa-solid fa-file-pdf"></i>
            </button>
        </div>
    </div>
</div>
<div class="container-fluid card mb-5 w-100" style="max-height: 700px; overflow-y: auto;">
    <div class="row">
        <div class="col-md-2 form-group mt-3">
            <label for="category-filter">Filter by Category: </label>
            <select id="category-filter" class="form-control">
                <option value="">All</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->name }}">{{ $category->name }}</option>
                    @endforeach
            </select>
        </div>
        <div class="col-md-2 form-group mt-3">
            <label for="unit-filter">Filter by Unit: </label>
            <select id="unit-filter" class="form-control">
                <option value="">All</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->name }}">{{ $unit->name }}</option>
                    @endforeach
            </select>
        </div>
        <div class="col-md-2 form-group mt-3">
            <label for="minimum-quantity-filter">Filter by Minimum Quantity: </label>
            <input type="number" class="form-control" id="min-quantity-filter" placeholder="Min Quantity" min="0" value="0">
        </div>
        <div class="col-md-2 form-group mt-3">
            <label for="maximum-quantity-filter">Filter by Maximum Quantity: </label>
            <input type="number" class="form-control" id="max-quantity-filter" placeholder="Max Quantity" min="0" value="0">
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
                <table id="itemsTable" class="table-striped table-hover" style="font-size: 11px">
                    <thead class="bg-info">
                        <th>Item Number</th>
                        <th width="15%">Category</th>
                        <th width="25%">Item Name</th>
                        <th>UoM</th>
                        <th>Stock on Hand</th>
                        <th>Buffer Stock</th>
                        <th>Date/Time Created</th>
                        <th>Status</th>
                        <th width="5%">Stock Level</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteItemModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" style="color:white;">DELETE ITEM FORM</h5>
                </div>
            <div class="modal-body">
                <div class="row">
                <form id="delete-item-form">
                        <div class="col-md-3 form-group" hidden>
                            <label for="deleteItemId">Item ID</label>
                            <input type="text" class="form-control" name="delete-item-id" id="delete-item-id">
                        </div>
                        <strong>Are you sure you want to delete this item?</strong>
                </div>
                <div class="row">
                    <div class="modal-footer d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-success" id="delete-item-close-btn">NO</button>
                        <button type="submit" class="btn btn-danger" id="delete-submit-btn">YES</button>
                    </div>   
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" style="color:white;">EDIT ITEM FORM</h5>
                    <button type="button" id="edit-item-close-btn" data-dismiss="modal" class="btn btn-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row edit-item-row">
                    <form id="edit-item-form">
                        <div class="form-group" hidden>
                            <label for="editItemId">Item ID</label>
                            <input type="text" class="form-control" name="edit-item-id[]" id="edit-item-id">
                        </div>
                        <div class="form-group">
                            <label for="category" class="font-weight-bold">Category</label>
                            <input type="text" class="edit-search-category form-control" name="category[]" placeholder="Search categories..." autocomplete="off"/>
                            <ul class="edit-category-results" style="display: none; max-height: 200px; overflow-y: auto;"></ul>
                            <input type="text" class="edit-selected-category-id" id="edit-category" name="category_id[]" hidden>
                        </div>
                        <div class="form-group">
                            <label for="edit-itemName" class="font-weight-bold">Item Name</label>
                            <textarea name="item_name[]" id="edit-item-name" class="form-control" cols="5" rows="1"></textarea>
                        </div>
                        <!-- Item Unit -->
                        <div class="form-group">
                            <label for="itemUnit" class="font-weight-bold">Unit</label>
                            <input type="text" class="edit-search-unit form-control" name="unit[]"  placeholder="Search unit..." autocomplete="off"/>
                            <ul class="edit-unit-results" style="display: none; max-height: 200px; overflow-y: auto;"></ul>
                            <input type="text" class="edit-selected-unit-id" name="unitId[]" hidden>
                        </div>
                        <div class="form-group">
                            <label for="buffer" class="font-weight-bold">Buffer Stock</label>
                            <input type="number" class="form-control" id="edit-buffer" name="buffer[]" placeholder="Enter buffer stock" min="0">
                        </div>
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-danger" id="delete-submit-btn">SUBMIT</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="pdfReportModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" style="color:white;">PDF CUSTOMIZE FORM</h5>
                    <button type="button" id="pdf-report-close-btn" data-dismiss="modal" class="btn" aria-label="Close" style="background-color: white">
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
<script src="{{ asset('assets/js/admin/items/add-item-functions/category-search.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/add-item-functions/unit-search.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/delete-item.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/edit-item.js') }}"></script>
<script src="{{ asset('assets/js/admin/pdf/report.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const periodSelect = document.getElementById('period');
        const monthSelect = document.getElementById('month');
        const yearMonthly = document.getElementById('selectedYear');
        const quarterSelect = document.getElementById('quarterly');
        const yearQuarterly = document.getElementById('yearSelectQuarterly');
        const preparedSelect = document.getElementById('prepared');
        const submitBtn = document.getElementById('report-submit-btn');
    
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
        validateForm();
        periodSelect.addEventListener('change', function () {
            const selected = this.value;
            document.getElementById('month-row').style.display = selected === 'Monthly' ? 'block' : 'none';
            document.getElementById('quarterly-row').style.display = selected === 'Quarterly' ? 'block' : 'none';
            document.getElementById('signatories-row').style.display = selected !== '' ? 'block' : 'none';
    
            validateForm(); 
        });
        [monthSelect, yearMonthly, quarterSelect, yearQuarterly, preparedSelect]
            .forEach(el => el.addEventListener('change', validateForm));
    });
</script>
    