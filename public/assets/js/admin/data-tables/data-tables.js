$(document).ready(function () {
    var table = $('#itemsTable').DataTable({
        "aLengthMenu": [[5, 10, 25, 50, 75, 100], [5, 10, 25, 50, 75, 100]],
        "pageLength": 5,
        "responsive": true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: '/admin/refreshItems',
            type: 'GET',
            data: function(d) {
                // Add the filter values to the data sent to the server
                d.category = $('#category-filter').val();
                d.unit = $('#unit-filter').val();
                d.status = $('#status-filter').val();
                d.minQuantity = $('#min-quantity-filter').val();
                d.maxQuantity = $('#max-quantity-filter').val();
                d.level = $('#level-filter').val();
            },
            "dataSrc": function (json) {
                return json.data; // Return the data from the JSON response
            }
        },
        "columns": [
            { "data": "control_number" },
            { "data": "category_name" },
            { "data": "item_name" },
            { "data": "quantity" },
            { "data": "max_quantity" },
            { "data": "unit_name" },
            { "data": "created_at" },
            { "data": "updated_at" },
            {
                "data": "status_name",
                "render": function(data, type, row) {
                    if (data === 'Available') {
                        return '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Available</span>';
                    } else {
                        return '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Unavailable</span>';
                    }
                }
            },
            {
                "data": "percentage",
                "render": function(data, type, row) {
                    if(data == 0){
                        return '<span class="badge badge-noStock"><i class="fas fa-times-circle"></i> No Stock</span>';
                    } else if (data <= 20) {
                        return '<span class="badge badge-lowStock"><i class="fas fa-times-circle"></i> Low Stock</span>';
                    } else if (data <= 50) {
                        return '<span class="badge badge-moderateStock"><i class="fas fa-times-circle"></i> Moderate Stock</span>';
                    } else {
                        return '<span class="badge badge-highStock"><i class="fas fa-check-circle"></i> High Stock</span>';
                    }
                }
            },
            {
                "data": null,
                "render": function(data, type, row) {
                    return '<button type="button" class="btn btn-warning edit-btn" title="Item edit button" id="editItemBtn"><i class="fa fa-edit"></i></button>' + 
                        '<button type="button" class="btn btn-danger delete-btn ml-2" title="Item delete button" id="deleteItemBtn"><i class="fa fa-trash"></i></button>';
                },
                "orderable": false,
                "class": "text-center"
            }
        ],
        "order": [[1, 'desc']],   
    });
    setInterval(function() {
        table.ajax.reload();  // This reloads the data from the server
    }, 1000);
    $('#category-filter, #unit-filter, #status-filter, #level-filter').on('change', function () {
        table.ajax.reload();  // Reload the table data based on new filters
    });
    
    $('#min-quantity-filter, #max-quantity-filter').on('change', function() {
        table.ajax.reload();  // Reload the table data based on new quantity filters
    });  
    $('#itemsTable').on('click', '.edit-btn', function() {
        var data = table.row($(this).closest('tr')).data();
        editItem(data);
    });  
    $('#itemsTable').on('click', '.delete-btn', function() {
        var data = table.row($(this).closest('tr')).data();
        deleteItem(data);
    });  
});
$(function () {
    var table = $('#transactionTable').DataTable({
        "aLengthMenu": [[5, 10, 25, 50, 75, 100, -1], [5, 10, 25, 50, 75, 100, "All"]],
        "pageLength": 5,
        "responsive": {
            breakpoints: [
                { name: 'xl', width: Infinity },
                { name: 'lg', width: 1200 },
                { name: 'md', width: 992 },
                { name: 'sm', width: 768 },
                { name: 'xs', width: 576 }
            ]
        },
        "order": [[1, 'desc']],
        "autoWidth": false,
        "processing": true,
        "serverSide": false,
        "ajax": {
            url: '/admin/refreshTransactions',  // Replace with your actual endpoint
            type: 'GET',
            "dataSrc": function (json) {
                return json.data;
            } // Ensure that 'data' is the key where the array of rows is located
        },
        "columns": [
            { "data": "time_request" },
            { "data": "transaction_number" },
            { "data": "client_name" },
            { "data": "item_name" },
            { "data": "unit" },
            { "data": "quantity" },
            { "data": "time_approved" },
            { "data": "date_approved" },
            { "data": "released_by" },
            { "data": "time_released" },
            {
                "data": "status",
                "render": function(data, type, row) {
                    if (data === 'Accepted') {
                        return '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Accepted</span>';
                    } else if (data === 'Pending') {
                        return '<span class="badge badge-pending"><i class="fas fa-clock"></i> Pending</span>';
                    } else {
                        return '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Rejected</span>';
                    }
                }
            },
            {
                "data": "remarks",
                "render": function(data, type, row) {
                    if (data === 'For Review') {
                        return '<span class="badge badge-forReview"><i class="fas fa-search"></i> For Review</span>';
                    } else if (data === 'For Release') {
                        return '<span class="badge badge-release"><i class="fas fa-cloud-upload-alt"></i> For Release</span>';
                    } else {
                        return '<span class="badge badge-completed"><i class="fas fa-check-circle"></i> Completed</span>';
                    }
                }
            },
            {
                "data": null,  // This column will have the action buttons
                "render": function(data, type, row) {
                    if (row.status === 'Pending') {
                        return '<button type="button" class="btn btn-warning edit-btn"><i class="fa fa-edit"></i></button>';
                    } else {
                        return ''; 
                    }
                },
                "orderable": false,
                "class": "text-center"
            }
        ],
    });
    $('#transactionTable').on('click', '.edit-btn', function() {
        var data = table.row($(this).closest('tr')).data();
        changeStatus(data);
    });
    setInterval(function() {
        table.ajax.reload(null, false);  
    }, 6000);
});


$(function () {
    var table = $('#accountTable').dataTable({
        "aLengthMenu": [[10, 15, 25, 50, 75 , 100, -1],[10, 15, 25, 50, 75 , 100, "All"]],
        "pageLength": 10,
        "responsive": {
            breakpoints: [
                { name: 'xl', width: Infinity },
                { name: 'lg', width: 1200 },
                { name: 'md', width: 992 },
                { name: 'sm', width: 768 },
                { name: 'xs', width: 576 }
            ]
        }
    });
});
$(function () {
    var table = $('#transactionHistoryTable').dataTable({
        "aLengthMenu": [[5, 10, 25, 50, 75, 100, -1], [5, 10, 25, 50, 75, 100, "All"]],
        "pageLength": 5,
        "responsive": {
            breakpoints: [
                { name: 'xl', width: Infinity },
                { name: 'lg', width: 1200 },
                { name: 'md', width: 992 },
                { name: 'sm', width: 768 },
                { name: 'xs', width: 576 }
            ]
        },
        "order": [[1, 'desc']]
    });
    
});
$(function () {
    var table = $('#auditTable').dataTable({
        "aLengthMenu": [[10, 15, 25, 50, 75 , 100, -1],[10, 15, 25, 50, 75 , 100, "All"]],
        "pageLength": 10,
        "responsive": {
            breakpoints: [
                { name: 'xl', width: Infinity },
                { name: 'lg', width: 1200 },
                { name: 'md', width: 992 },
                { name: 'sm', width: 768 },
                { name: 'xs', width: 576 }
            ]
        }
    });
});
$(function () {
    var table = $('#categoryTable').dataTable({
        "aLengthMenu": [[10, 15, 25, 50, 75 , 100, -1],[10, 15, 25, 50, 75 , 100, "All"]],
        "pageLength": 10,
        "responsive": {
            breakpoints: [
                { name: 'xl', width: Infinity },
                { name: 'lg', width: 1200 },
                { name: 'md', width: 992 },
                { name: 'sm', width: 768 },
                { name: 'xs', width: 576 }
            ]
        }
    });
});
$(function () {
    var table = $('#receivesTable').DataTable({
        "aLengthMenu": [[5, 10, 25, 50, 75, 100, -1], [5, 10, 25, 50, 75, 100, "All"]],
        "pageLength": 5,
        "responsive": true,
        "order": [[0, 'desc']],
        "autoWidth": false,
        "processing": true,
        "serverSide": false,  // Use true if you want server-side processing
        "ajax": {
            url: '/admin/refreshReceivables',  
            type: 'GET',
            "dataSrc": function (json) {
                return json.data;  // Ensure the response has a "data" key
            }
        },
        "columns": [
            { "data": "control_number" },
            { "data": "item_name" },
            { "data": "unit_name" },
            { "data": "received_quantity" },
            { "data": "created_at" },
            { "data": "updated_at" },
            {
                "data": null,
                "render": function(data, type, row) {
                    return '<button type="button" class="btn btn-warning" id="editReceivedItem" title="Supply received edit button"><i class="fa fa-edit"></i></button>';
                },
                "orderable": false,
                "class": "text-center"
            }
        ]
    });

    // Click event for edit button
    $('#receivesTable').on('click', '#editReceivedItem', function() {
        var data = table.row($(this).closest('tr')).data();
        editReceivedItem(data);
    });

    // Automatically reload the data every 6 seconds
    setInterval(function() {
        table.ajax.reload(null, false);  // Reload the table without resetting pagination
    }, 6000);
});
