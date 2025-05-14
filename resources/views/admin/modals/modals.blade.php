<div class="modal fade" id="setUserModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" style="color:white;">SET USER ROLE FORM</h5>
                    <button type="button" id="set-user-close-btn" data-dismiss="modal" class="btn bg-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark" style="color: white;"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <form id="set-user-role-form">
                        @csrf
                        <div class="form-group">
                            <label for="fullName" class="font-weight-bold">Full Name</label>
                            <select name="full_name" id="full_name" class="form-control">
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" >
                            <label for="roleId" class="font-weight-bold">System Roles</label>
                            <select name="role_id" id="role-id" class="form-control">
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-warning" id="set-role-submit-btn">SUBMIT</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="changeUserStatusModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" style="color:white;">CHANGE USER STATUS FORM</h5>
                    <button type="button" id="change-user-status-close-btn" data-dismiss="modal" class="btn bg-light" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <form id="change-user-status-form">
                        @csrf
                        <div class="form-group">
                            <label for="fullName" class="font-weight-bold">Full Name</label>
                            <select name="user_id" id="full_name" class="form-control">
                                @foreach ($clients as $client)
                                    @if ($client->id != 1)
                                        <option value="{{ $client->id }}">{{ $client->full_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" >
                            <label for="status" class="font-weight-bold">System Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">Select User Status</option>
                                <option value="Inactive">Deactivate</option>
                                <option value="Active">Activate</option>
                            </select>
                        </div>
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-danger" id="change-user-status-submit-btn">SUBMIT</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
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
                        <div class="form-group">
                            <label for="transactionStatusID" class="font-weight-bold">Transaction Number</label>
                            <select name="transaction-status-id" id="transaction-status-id" class="form-control">
                                <option value="">Select Transaction Number</option>
                                @foreach ($transacts as $transaction)
                                    @if ($transaction->status_id == 1) <!-- Only show transactions where status_id is 1 -->
                                        <option value="{{ $transaction->id }}">{{ $transaction->transaction_number }}</option>
                                    @endif
                                @endforeach
                            </select>                            
                        </div>
                        <div>
                            <label for="transactionStatusID" class="font-weight-bold">Transaction Status</label>
                            <select name="status" id="transaction_status" class="form-control">
                                <option value="2">Approve</option>
                                <option value="3">Reject</option>
                            </select>
                        </div>
                        
                        
                        <div class="form-group" id="releaseTimeDivision" style="display:none;">
                            <label for="releaseTime" class="font-weight-bold">Release Time</label><br>
                            <input type="time" class="form-control" id="releaseTime" name="time" readonly>
                        </div>
                        
                        <div class="form-group" id="denialReasonDivision" style="display:none;">
                            <label for="reason" class="font-weight-bold">Reason</label><br>
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
<!-- Modal -->
<div class="modal fade" id="createItemModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title flex-grow-1 text-center"><strong>CREATE ITEM FORM</strong></h5>
                <button type="button" id="createItem-closeBtn" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                    &times;
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="createItem-form1" class="p-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <button type="button" id="addItem-btn" class="btn btn-primary rounded px-4 py-2" title="Add item row">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                    <div id="item-container">
                        <div class="row mb-3 mt-2 item-row">
                            <!-- Category -->
                            <div class="col-md-3 form-group">
                                <label for="category" class="font-weight-bold" >Category</label>
                                <input type="text" class="search-category form-control" name="category[]" placeholder="Search categories..." autocomplete="off"/>
                                <ul class="category-results" style="display: none; max-height: 300px; overflow-y: auto;"></ul>
                                <input type="text" class="selected-category-id" id="category" name="categoryId[]" hidden>
                            </div>
                            <!-- Item Name -->
                            <div class="col-md-2 form-group">
                                <label for="itemName" class="font-weight-bold">Item Name</label>
                                <textarea name="itemName[]" class="form-control" cols="5" rows="1"></textarea>
                            </div>
                            <!-- Item Unit -->
                            <div class="col-md-2 form-group">
                                <label for="itemUnit" class="font-weight-bold">UoM</label>
                                <input type="text" class="search-unit form-control" name="unit[]" placeholder="Search unit..." autocomplete="off"/>
                                <ul class="unit-results" style="display: none; max-height: 200px; overflow-y: auto;"></ul>
                                <input type="text" class="selected-unit-id" name="unitId[]" hidden>
                            </div>
                            <!-- Item Quantity -->
                            <div class="col-md-2 form-group">
                                <label for="quantity" class="font-weight-bold">Stock on Hand</label>
                                <input type="number" class="form-control" id="quantity" name="quantity[]" placeholder="Enter quantity" min="0">
                            </div>
                            <div class="col-md-2 form-group">
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
                            <div class="col-md-2 form-group">
                                <label for="" class="font-weight-bold">&nbsp</label>
                                <button type="reset" class="btn btn-secondary rounded px-4 py-2 me-3 form-control">Clear</button>
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="" class="font-weight-bold">&nbsp</label>
                                <button type="submit" id="addItemSubmit-btn" class="btn btn-success rounded px-4 py-2 form-control">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="requestForm" tabindex="-1" aria-labelledby="requestFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-success text-light">
                <h4 class="modal-title w-100 text-center" id="requestFormLabel"><strong>REQUEST ITEM FORM</strong></h4>
                <button type="button" id="requestItem-closeBtn" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <form action="" id="requestItem-form" class="mb-5">
                @csrf
                <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                    <div class="col-12 d-flex justify-content-end mb-3">
                        <button type="button" id="requestItemReceived-btn" class="btn btn-primary rounded px-4 py-2">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                    <div id="requestItem-container">
                        <div class="row mb-3 mt-2 request-item-row" style="margin-bottom: 5rem;">
                            <div class="col-md-5 form-group">
                                <label for="itemName" class="font-weight-bold">Item Name</label>
                                <input type="text" class="search-request-items form-control" name="requestItemName[]" id="requestItemName" placeholder="Search items..." autocomplete="off"/>
                                <ul class="item-results" style="display: none; max-height: 500px; overflow-y: auto;"></ul>
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
                                <button type="button" class="remove-request-item btn btn-danger">
                                    <i class="fa-solid fa-eraser mr-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="reset" class="btn btn-secondary rounded px-4 py-2 me-3">Clear</button>
                        <button type="submit" id="requestItemSubmit-btn" class="btn btn-success rounded px-4 py-2">Submit</button>
                    </div>
                </div>
               
            </form>
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
                        <div class="form-group">
                            <label for="editItemId" class="font-weight-bold">Select the item for editing</label>
                            <select name="edit-item-id[]" id="edit-item-id" class="form-control">
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="category" class="font-weight-bold">Category</label>
                            <input type="text" class="edit-search-category form-control" name="category[]" placeholder="Search categories..." autocomplete="off"/>
                            <ul class="edit-category-results" style="display: none; max-height: 200px; overflow-y: auto;"></ul>
                            <input type="text" class="edit-selected-category-id" id="edit-category" name="categoryId[]" hidden>
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
<div class="modal fade" id="deleteItemModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" style="color:white;">DELETE ITEM FORM</h5>
                </div>
            <div class="modal-body">
                <div class="row">
                <form id="delete-item-form">
                        <div class="form-group">
                            <label for="deleteItemId" class="font-weight-bold">Select Item to Delete</label>
                            <select name="delete-item-id" id="delete-item-id" class="form-control">
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
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
<div class="modal fade" id="receivedModal" tabindex="-1" aria-labelledby="requestFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="d-flex justify-content-between align-items-center bg-warning text-dark p-3 rounded-top">
                <h4 class="m-0 text-center flex-grow-1"><strong>DELIVERY FORM</strong></h4>
                <button type="button" id="receivedItem-closeBtn" class="btn btn-danger p-2">&times;</button>
            </div>
            <form id="receivedItem-form" class="p-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="button" id="addRequest-btn" class="btn btn-primary rounded px-4 py-2" title="add more delivery row">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
                <div id="receivedItem-container">
                    <div class="row mb-3 mt-2 receive-item-row">
                        <div class="col-md-2 form-group">
                            <label for="control_number_received" class="font-weight-bold">P.O Number</label>
                            <input type="text" class="form-control" name="control_number[]" id="control_number_received" placeholder="Enter control number">
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="supplier" class="font-weight-bold">Supplier</label>
                            <select name="supplier[]" id="supplier" class="form-control">
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->name }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="itemName" class="font-weight-bold">Item Name</label>
                            <input type="text" class="search-items form-control" name="receivedItemName[]" id="receiveItemName" placeholder="Search items..." autocomplete="off"/>
                            <ul class="item-results" style="display: none; max-height: 200px; overflow-y: auto;"></ul>
                            <input type="text" class="selected-item-id" id="receiveItemId" name="receivedItemId[]" hidden>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="quantity" class="font-weight-bold">Quantity</label>
                            <input type="number" class="form-control" name="receivedQuantity[]" id="received_quantity" placeholder="Enter quantity" min="0">
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="remaining_quantity" class="font-weight-bold">Stock on Hand</label>
                            <input type="number" class="form-control remaining_quantity" name="remainingQuantity[]" id="remaining_quantity" placeholder="Enter quantity" min="0" readonly>
                        </div>
                        <div class="col-md-1 form-group">
                            <label for="action" class="font-weight-bold">Action</label><br>
                            <button type="button" class="remove-deliver-item btn btn-danger"><i class="fa-solid fa-eraser"></i></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="col-md-2 form-group">
                            <label for="" class="font-weight-bold">&nbsp</label>
                            <button type="reset" class="btn btn-secondary rounded px-4 py-2 me-3 form-control">Clear</button>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="" class="font-weight-bold">&nbsp</label>
                            <button type="submit" id="receivedItemSubmit-btn" class="btn btn-warning rounded px-4 py-2 form-control">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" style="color:white;">ADD CATEGORY FORM</h5>
                    <button type="button" id="add-category-close-btn" data-dismiss="modal" class="btn btn-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                <form id="add-category-form">
                    @csrf
                        <div class="form-group">
                            <label for="addMainCategory" class="font-weight-bold">Main Category</label>
                            <select name="main_category" id="add-main-category" class="form-control">
                                <option value="">Select Main Category</option>
                                @foreach ($sub_categories as $sub_category)
                                    <option value="{{ $sub_category->id }}">{{ $sub_category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="reportName" class="font-weight-bold">Category Name</label>
                            <input type="text" class="form-control" name="category_name" id="add-category-name">
                        </div>
                        <div class="form-group">
                            <label for="reportName" class="font-weight-bold">Description</label>
                            <textarea type="text" class="form-control" name="category_description" id="add-category-description" placeholder="This is optional" cols="5" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="modal-footer">
                            <div class="col-md-3 form-group">
                                <button type="submit" class="btn btn-success" id="add-category-submit-btn">SUBMIT</button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" style="color:white;">EDIT CATEGORY FORM</h5>
                    <button type="button" id="edit-category-close-btn" data-dismiss="modal" class="btn bg-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark" style="color: white;"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <form id="edit-category-form">
                        @csrf
                        <div class="form-group">
                            <label for="category_id" class="font-weight-bold">Select category to edit...</label>
                            <select name="category_id" id="edit-category-id" class="form-control">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="category_control_number" class="font-weight-bold">Category Number</label>
                            <input type="text" class="form-control" name="category_control_number" id="edit-category-control_number" readonly>
                        </div>
                        <div class="form-group">
                            <label for="main_category" class="font-weight-bold">Main Category</label>
                            <select name="main_category" id="main_category" class="form-control">
                                <option value="">Select Main Category</option>
                                @foreach ($sub_categories as $sub_category)
                                    <option value="{{ $sub_category->id }}">{{ $sub_category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="category_name" class="font-weight-bold">Category Name</label>
                            <input type="text" class="form-control" name="category_name" id="edit-category-name">
                        </div>
                        <div class="form-group">
                            <label for="category_description" class="font-weight-bold">Description</label>
                            <textarea type="text" class="form-control" name="category_description" id="edit-category-description" cols="5" rows="5" placeholder="This is Optional"></textarea>
                        </div>
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-warning" id="edit-category-submit-btn">SUBMIT</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" style="color:white;">DELETE CATEGORY FORM</h5>
                </div>
            <div class="modal-body">
                <div class="row">
                <form id="delete-category-form">
                        <div class="form-group">
                            <label for="deleteCategoryId" class="font-weight-bold">Select category to delete...</label>
                            <select name="category_id" id="delete-category-id" class="form-control">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <strong>Are you sure you want to delete this category?</strong>
                </div>
                <div class="row">
                    <div class="modal-footer d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-success" id="delete-category-close-btn">NO</button>
                        <button type="submit" class="btn btn-danger" id="delete-category-submit-btn">YES</button>
                    </div>   
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addUnitModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" style="color:white;">ADD UNIT FORM</h5>
                    <button type="button" id="add-unit-close-btn" data-dismiss="modal" class="btn btn-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                <form id="add-unit-form">
                    @csrf
                        <div class="form-group">
                            <label for="addUnitName" class="font-weight-bold">Unit Name</label>
                            <input type="text" class="form-control" name="unit_name" id="add-unit-name">
                        </div>
                        <div class="form-group">
                            <label for="addUnitDescription" class="font-weight-bold">Description</label>
                            <textarea type="text" class="form-control" name="unit_description" id="add-unit-description" placeholder="This is optional" cols="5" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="modal-footer">
                            <div class="col-md-3 form-group">
                                <button type="submit" class="btn btn-success" id="add-unit-submit-btn">SUBMIT</button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editUnitModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" style="color:white;">EDIT UNIT FORM</h5>
                    <button type="button" id="edit-unit-close-btn" data-dismiss="modal" class="btn bg-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark" style="color: white;"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <form id="edit-unit-form">
                        @csrf
                        <div class="form-group">
                            <label for="unit_id" class="font-weight-bold">Select unit to edit...</label>
                            <select name="unit_id" id="edit_unit_id" class="form-control">
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="unit_control_number" class="font-weight-bold">Unit Number</label>
                            <input type="text" class="form-control" name="unit_control_number" id="edit-unit-control_number" readonly>
                        </div>
                        <div class="form-group">
                            <label for="category_name" class="font-weight-bold">Unit Name</label>
                            <input type="text" class="form-control" name="unit_name" id="edit-unit-name">
                        </div>
                        <div class="form-group">
                            <label for="unit_description" class="font-weight-bold">Description</label>
                            <textarea type="text" class="form-control" name="unit_description" id="edit-unit-description" cols="5" rows="5" placeholder="This is Optional"></textarea>
                        </div>
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-warning" id="edit-unit-submit-btn">SUBMIT</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteUnitModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" style="color:white;">DELETE UNIT FORM</h5>
                </div>
            <div class="modal-body">
                <div class="row">
                <form id="delete-unit-form">
                        <div class="form-group">
                            <label for="deleteUnitId" class="font-weight-bold">Select unit to delete...</label>
                            <select name="unit_id" id="delete-unit-id" class="form-control">
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <strong>Are you sure you want to delete this unit?</strong>
                </div>
                <div class="row">
                    <div class="modal-footer d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-success" id="delete-unit-close-btn">NO</button>
                        <button type="submit" class="btn btn-danger" id="delete-unit-submit-btn">YES</button>
                    </div>   
                </form>
                </div>
            </div>
        </div>
    </div>
</div>