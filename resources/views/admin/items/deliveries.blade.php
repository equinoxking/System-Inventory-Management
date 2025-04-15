<div class="container-fluid card w-100 shadow rounded p-4" id="receivedItemForm" style="max-height: 300px; overflow-y: auto; background-color: #f8f9fa; display:none; border: 2px solid #ddd;">
    <!-- Form Header -->
    <div class="d-flex justify-content-between align-items-center bg-warning text-dark p-3 rounded-top">
        <h4 class="m-0 text-center flex-grow-1"><strong>DELIVERY FORM</strong></h4>
        <button type="button" id="receivedItem-closeBtn" class="btn btn-danger p-2">&times;</button>
    </div>
    <!-- Form Body -->
    <form action="" id="receivedItem-form" class="p-3">
        <div class="col-md-12 d-flex justify-content-end">
            <button type="button" id="addRequest-btn" class="btn btn-primary rounded px-4 py-2">
                Deliver more item
            </button>
        </div>
        <div id="receivedItem-container">
            <div class="row mb-3 mt-2 receive-item-row">
                <div class="col-md-2 form-group">
                    <label for="delivery_types" class="font-weight-bold">Delivery Type</label>
                    <select name="delivery_types[]" id="delivery_types" class="form-control">
                        <option value="">Select Delivery Type</option>
                        <option value="Inspection Delivery">Inspection Delivery</option>
                        <option value="Receipt for Stock">Receipt for Stock</option>
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <label for="control_number_received" class="font-weight-bold">Control Number</label>
                    <input type="text" class="form-control" name="control_number[]" id="control_number_received" placeholder="Enter control number">
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
                    <label for="action" class="font-weight-bold">Action</label>
                    <button type="button" class="remove-deliver-item btn btn-danger">Remove</button>
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
                    <button type="submit" id="receivedItemSubmit-btn" class="btn btn-warning rounded px-4 py-2 form-control">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-9" style="text-align: left">
            <h4><strong>DELIVERIES</strong></h4>
        </div>
        <div class="col-md-3" style="text-align: right">
            <button type="button" class="btn btn-warning" id="receivedBtn" title="Deliver item button">
                <i class="fa-solid fa-cart-plus" style="color: #ffffff;"></i>
            </button>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <table id="receivesTable" class="table-striped table-hover" style="font-size: 12px">
                <thead style="background-color: #3d5c99; color:white">
                    <tr>
                        <th width="12%">Date/Time Received</th>
                        <th width="12%">Date/Time Updated</th>
                        <th width="12%">Delivery Type</td>
                        <th width="10%">Control Number</th>
                        <th width="10%">Stock On Hand</th>
                        <th width="10%">Delivered Quantity</th>
                        <th width="5%">UoM</th>
                        <th>Item Name</th>
                        <th width="5%">Remarks</th>
                        <th width="5%">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="updateReceivedModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" style="color:white;">EDIT DELIVERIES FORM</h5>
                    <button type="button" id="edit-received-close-btn" data-dismiss="modal" class="btn btn-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <form id="update-received-status-form">
                        @csrf
                        <div class="form-group" hidden>
                            <label for="editReceivedId">Received ID</label>
                            <input type="text" class="form-control" name="edit-received-id" id="edit-received-id">
                        </div>
                        <div class="form-group" hidden>
                            <label for="editExistingQuantity" class="font-weight-bold">Item Id</label>
                            <textarea name="item_id" id="edit-received-item-id" class="form-control" cols="5" rows="1" readonly></textarea>
                        </div>
                        <!-- Item Unit -->
                        <div class="form-group" hidden>
                            <label for="editReceivedQuantity" class="font-weight-bold">Quantity</label>
                            <input type="text" class="form-control" name="edit-received-quantity" id="edit-received-quantity" />
                        </div>                  
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-danger" id="update-received-submit-btn">SUBMIT</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/admin/items/received-item-functions/search-item-name.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/received-item.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/received-item-functions/edit-received-item.js') }}"></script>