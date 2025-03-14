@extends('admin.layout.admin-layout')
@section('content')
    <div class="container-fluid mt-3 mb-3">
        <div class="row align-items-center">
            <div class="col-md-2">
                <!-- Breadcrumb Navigation -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Items</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-10 text-end">
                <button type="button" class="btn btn-success" id="addItemBtn" title="Add item button">
                    <i class="fa-solid fa-plus"></i>
                </button>
                <button type="button" class="btn btn-info" title="Generate PDF button">
                    <i class="fa-solid fa-file-pdf"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="container-fluid card mb-5 w-100" style="max-height: 700px; overflow-y: auto;">
        <div class="row">
            <div class="col-md-12">
                <table id="itemsTable" class="table-striped table-hover">
                    <thead class="bg-info">
                        <th width="10%">Control Number</th>
                        <th width="20%">Category</th>
                        <th width="20%">Name</th>
                        <th width="5%">Quantity</th>
                        <th width="5%">Unit</th>
                        <th width="10%">Status</th>
                        <th width="10%">Stock Level</th>
                        <th width="10%">Action</th>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item->controlNumber }}</td>
                                <td>{{ $item->category->name }}</td>
                                <td>{{ $item->name}}</td>
                                <td>{{ $item->inventory->quantity }}</td>
                                <td>{{ $item->inventory->unit->name }}</td>
                                <td>
                                    @if($item->status && $item->status->name == 'Available')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i> Available
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times-circle"></i> Unavailable
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $quantity = $item->inventory->quantity;
                                        $stockStatus = '';
                                        $statusClass = '';
                                        $icon = '';
                                
                                        if ($quantity <= 20) {
                                            $stockStatus = 'Low Stock';
                                            $statusClass = 'stock-low';
                                            $icon = 'fa-exclamation-triangle';
                                        } elseif ($quantity <= 50) {
                                            $stockStatus = 'Moderate Stock';
                                            $statusClass = 'stock-moderate';
                                            $icon = 'fa-exclamation-circle';
                                        } else {
                                            $stockStatus = 'High Stock';
                                            $statusClass = 'stock-high';
                                            $icon = 'fa-check-circle';
                                        }
                                    @endphp
                                
                                    <span class="{{ $statusClass }}">
                                        <i class="fa {{ $icon }}"></i>
                                        {{ $stockStatus }}
                                    </span>
                                </td>                                
                                <td>
                                    <button type="button" id="viewItem" class="btn btn-warning" title="Item edit button"><i class="fa-solid fa-edit"></i></button>
                                    <button type="button" id="viewItem" class="btn btn-danger" title="Item delete button"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                    
                    </tfoot>
                </table>
            </div>
        </div>
    </div>



<div class="container-fluid card w-100 shadow rounded p-4" id="itemForm" style="max-height: 300px; overflow-y: auto; background-color: #f8f9fa; display:none; border: 2px solid #ddd;">
     
    <!-- Form Header -->
    <div class="d-flex justify-content-between align-items-center bg-primary text-white p-3 rounded-top">
        <h4 class="m-0 text-center flex-grow-1"><strong>CREATE ITEM FORM</strong></h4>
        <button type="button" id="createItem-closeBtn" class="btn btn-danger p-2">
            &times;
        </button>
    </div>
    <!-- Form Body -->
        <form action="" id="createItem-form" class="p-3">
            <div class="row mb-3 mt-2">
                <!-- Category -->
                <div class="col-md-3 form-group">
                    <label for="category" class="font-weight-bold">Category</label>
                    <input type="text" id="search" name="category" class="form-control" placeholder="Search categories..." autocomplete="off"/>
                    <ul id="category-results" class="list-group mt-2" style="display: none; max-height: 200px; overflow-y: auto;"></ul>
                    <input type="text" id="selected-category-id" name="categoryId" hidden>
                </div>
                <!-- Item Name -->
                <div class="col-md-3 form-group">
                    <label for="itemName" class="font-weight-bold">Item Name</label>
                    <textarea name="itemName" id="itemName" class="form-control" cols="5" rows="1"></textarea>
                    {{-- <input type="text" class="form-control" name="itemName" id="itemName" placeholder="Enter item name"> --}}
                </div>
                <!-- Item Unit -->
                <div class="col-md-1 form-group">
                    <label for="itemUnit" class="font-weight-bold">Unit</label>
                    <input type="text" id="search-unit" name="unit" class="form-control" placeholder="Search unit..." autocomplete="off"/>
                    <ul id="unit-results" class="list-group mt-2" style="display: none; max-height: 200px; overflow-y: auto;"></ul>
                    <input type="text" id="selected-unit-id" name="unitId" hidden>
                </div>
                <!-- Item Quantity -->
                <div class="col-md-1 form-group">
                    <label for="itemQuantity" class="font-weight-bold">Quantity</label>
                    <input type="number" class="form-control" name="quantity" id="itemQuantity" placeholder="Enter quantity" min="0">
                </div>
                <!-- Buttons on One Side (Right) -->
                <div class="col-md-4 d-flex align-items-center mt-4">
                    <button type="reset" class="btn btn-secondary rounded px-4 py-2 me-2">Clear</button>
                    <button type="submit" id="addItem-btn" class="btn btn-primary rounded px-4 py-2">Submit</button>
                </div>
            </div>
        </form>
</div>

<script>
/* Prevent item quantity to value of negative */
document.addEventListener('DOMContentLoaded', function () {
    const quantity = document.getElementById("itemQuantity");
    quantity.addEventListener('input', function () {
        if (Number(quantity.value) < 0 || isNaN(quantity.value)) {
            $('#itemQuantity').val('0');
        }
    });
});
/* Search for categories */
$(document).ready(function() {
    $('#search').on('keyup', function() {
        let query = $(this).val();
        if (query.length > 0) {
            $.ajax({
                url: '{{ route("search.categories") }}',
                method: 'GET',
                data: { query: query },
                success: function(data) {
                    $('#category-results').empty();
                    if (data.length > 0) {
                        data.forEach(function(category) {
                            $('#category-results').append(`
                                <li class="list-group-item category-item" data-id="${category.id}">
                                    <strong>${category.name}</strong> 
                                </li>
                            `);
                        });
                        $('#category-results').show();
                    } else {
                        $('#category-results').append('<li class="list-group-item">No categories found</li>');
                        $('#category-results').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        } else {
            $('#category-results').hide();
        }
});
$(document).on('click', '.category-item', function() {
    var categoryName = $(this).text().trim();  
    var categoryId = $(this).data('id'); 
    $('#search').val(categoryName);
    $('#selected-category-id').val(categoryId);
    $('#category-results').hide();
});
$(document).on('click', function(e) {
    if (!$(e.target).closest('#search').length) {
        $('#category-results').hide();
    }
    });
});
// Search for units
$(document).ready(function() {
    $('#search-unit').on('keyup', function() {
        let query = $(this).val();
        if (query.length > 0) {
            $.ajax({
                url: '{{ route("search.units") }}',
                method: 'GET',
                data: { query: query },
                success: function(data) {
                    $('#unit-results').empty();
                    if (data.length > 0) {
                        data.forEach(function(unit) {
                            $('#unit-results').append(`
                                <li class="list-group-item unit-item" data-id="${unit.id}">
                                    <strong>${unit.name}</strong> 
                                </li>
                            `);
                        });
                        $('#unit-results').show();
                    } else {
                        $('#unit-results').append('<li class="list-group-item">No categories found</li>');
                        $('#unit-results').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        } else {
            $('#unit-results').hide();
        }
});
$(document).on('click', '.unit-item', function() {
    var unitName = $(this).text().trim();  
    var unitId = $(this).data('id'); 
    $('#search-unit').val(unitName);
    $('#selected-unit-id').val(unitId);
    $('#unit-results').hide();
});
$(document).on('click', function(e) {
    if (!$(e.target).closest('#search-unit').length) {
        $('#unit-results').hide();
    }
    });
});
</script>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>