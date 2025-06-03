<div class="container-fluid card w-100 shadow rounded p-4" id="itemForm" style="max-height: 500px; overflow-y: auto; background-color: #f8f9fa; display:none; border: 2px solid #ddd;">
    <!-- Form Header -->
    <div class="d-flex justify-content-between align-items-center bg-success text-white p-3 rounded-top">
        <h4 class="m-0 text-center flex-grow-1"><strong>ADD NEW ITEM FORM</strong></h4>
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
            <div class="row mt-3 justify-content-end">
                <div class="col-auto form-group text-end">
                    <label for="" class="font-weight-bold">&nbsp;</label>
                    <button type="reset" class="btn btn-secondary rounded px-4 py-2 form-control">CLEAR</button>
                </div>
                <div class="col-auto form-group text-end">
                    <label for="" class="font-weight-bold">&nbsp;</label>
                    <button type="submit" id="addItemSubmit-btn" class="btn btn-success rounded px-4 py-2 form-control">SAVE</button>
                </div>
            </div>

    </form>    
</div>
@php
    
@endphp
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-9" style="text-align: left">
            <h4><strong>ITEMS</strong></h4>
        </div>
        <div class="col-md-3" style="text-align: right">
            <button type="button" class="btn btn-success" id="addItemBtn" title="Add item button">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
    </div>
</div>
<div class="container-fluid card mb-1 w-100" style="max-height: 700px; overflow-y: auto;">
    <div class="row">
        <div class="col-md-2 form-group mt-3">
            <label for="unit-filter">Filter by UoM: </label>
            <input type="text" id="unit-filter" class="form-control" placeholder="Search UoM">
        </div>
        <div class="col-md-2 form-group mt-3">
            <label for="minimum-quantity-filter">Filter by Minimum Quantity: </label>
            <input type="number" class="form-control" id="min-quantity-filter" placeholder="Min Quantity" min="0" value="0">
        </div>
        <div class="col-md-2 form-group mt-3">
            <label for="maximum-quantity-filter">Filter by Maximum Quantity: </label>
            <input type="number" class="form-control" id="max-quantity-filter" placeholder="Max Quantity" min="0" value="0">
        </div>
        {{-- <div class="col-md-2 form-group mt-3">
            <label for="status-filter">Filter by Availability:</label>
            <select id="status-filter" class="form-control">
                <option value="">All</option>
                <option value="available">Available</option>
                <option value="unavailable">Unavailable</option>
            </select>
        </div> --}}
        <div class="col-md-2 form-group mt-3">
            <label for="stock-level-filter">Filter by Stock Level:</label>
            <select id="stock-level-filter" class="form-control">
                <option value="">All</option>
                <option value="critical">Critical</option>
                <option value="normal">Normal</option>
            </select>
        </div>        
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
                <table id="itemsTable" class="table-hover" style="font-size: 11px">
                    <thead class="bg-info">
                        <th width="10%">Item Number</th>
                        <th>Item Name</th>
                        <th width="7%">UoM</th>
                        <th width="7%">Stock on Hand</th>
                        <th width="7%">Buffer Stock</th>
                        <th width="5%" class="text-center">Status</th>
                        <th width="5%">Stock Level</th>
                        <th width="5%">Action</th>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            @php
                                $quantity = $item->inventory->quantity;
                                $minQuantity = $item->inventory->min_quantity;
                                $rowStyle = '';

                                if ($quantity == 0) {
                                    $rowStyle = 'background-color: #f8d7da;'; // Red - danger
                                } elseif ($quantity <= $minQuantity) {
                                    $rowStyle = 'background-color: #fff3cd;'; // Yellow - warning
                                }
                            @endphp

                            <tr style="{{ $rowStyle }}">
                                <td>{{ $item->controlNumber }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->inventory->unit->name ?? 'N/A' }}</td>
                                <td>{{ $quantity }}</td>
                                <td>{{ $minQuantity }}</td>
                                <td>
                                    @if ($quantity == 0 && $item->status->name == "Available")
                                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Unavailable</span>
                                    @elseif ($item->status->name == 'Available')
                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Available</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($quantity <= $minQuantity)
                                        <span class="badge badge-noStock"><i class="fas fa-times-circle"></i> Critical</span>
                                    @else
                                        <span class="badge badge-highStock"><i class="fas fa-check-circle"></i> Normal</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-warning" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" id="itemEditBtn" onclick="editItem('{{ addslashes(json_encode($item)) }}')"><i class="fa fa-edit" style="color: white"></i></button>
                                    <button class="btn btn-danger" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" id="itemDeleteBtn" onclick="deleteItem('{{ addslashes(json_encode($item)) }}')" ><i class="fa fa-trash" ></i></button>
                                </td>
                            </tr>
                        @endforeach

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
                            <label for="edit-itemName" class="font-weight-bold">Item Name (From)</label>
                            <textarea name="item_name-readonly" id="edit-item-name-readonly" class="form-control" cols="5" rows="1" readonly></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit-itemName" class="font-weight-bold">Item Name (To)</label>
                            <textarea name="item_name[]" id="edit-item-name" class="form-control" cols="5" rows="1"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="category" class="font-weight-bold">Category</label>
                            <select name="edit-category" id="edit-category" class="form-select">
                                <option value="">--Select Category--</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Item Unit -->
                        <div class="form-group">
                            <label for="itemUnit" class="font-weight-bold">UoM</label>
                            <select name="edit-unit" id="edit-unit" class="form-select">
                                <option value="">--Select UoM--</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="buffer" class="font-weight-bold">Buffer Stock</label>
                            <input type="number" class="form-control" id="edit-buffer" name="buffer[]" placeholder="Enter buffer stock" min="0">
                        </div>
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="form-group">
                            <button type="submit" class="btn btn-warning" id="delete-submit-btn">SAVE</button>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('set_critical') === 'true') {
            const stockFilter = document.getElementById('stock-level-filter');
            if (stockFilter) {
                stockFilter.value = 'critical';
                // Trigger change event
                stockFilter.dispatchEvent(new Event('change'));

                // Wait a moment then redraw DataTable
                setTimeout(() => {
                    if ($.fn.dataTable.isDataTable('#itemsTable')) {
                        $('#itemsTable').DataTable().draw();
                    }
                }, 100); // Delay ensures filter value is applied before redraw
            }
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.edit-button');

        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const itemId = this.getAttribute('data-item-id');
                const categoryId = this.getAttribute('data-category-id');

                // Set item ID
                document.getElementById('edit-item-id').value = itemId;

                // Set selected category
                const categorySelect = document.getElementById('edit-category');
                categorySelect.value = categoryId;
            });
        });
    });
</script>


    