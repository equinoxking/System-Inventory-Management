$(document).ready(function () {
    // Initialize DataTable
    var table = $('#itemsTable').DataTable({
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
        "order": [[0, 'desc']]
    });
    $('#category-filter').on('change', function () {
        var category = this.value;
        table.column(1).search(category).draw();  
    });
    $('#unit-filter').on('change', function () {
        var unit = this.value;
        table.column(4).search(unit).draw();  
    });
    $('#min-quantity-filter, #max-quantity-filter').on('change', function() {
        var minQuantity = parseInt($('#min-quantity-filter').val()) || 0; 
        var maxQuantity = parseInt($('#max-quantity-filter').val()) || Infinity; 
    
        table.rows().every(function() {
            var row = this.node();
            var quantity = parseInt($(row).find('td').eq(3).text()); 
    
            if ((minQuantity && quantity < minQuantity) || (maxQuantity && quantity > maxQuantity)) {
                $(row).hide();
            } else {
                $(row).show(); 
            }
        });
    
        table.draw(); 
    });
    $('#status-filter').on('change', function () {
        var status = this.value;
        table.column(5).search(status).draw();  
    });
    $('#level-filter').on('change', function () {
        var level = this.value;
        table.column(6).search(level).draw();  
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
        "order": [[0, 'desc']],
        "autoWidth": false,
        "processing": true,
        "serverSide": false,
        "ajax": {
            url: '/admin/refreshTransactions',  // Replace with your actual endpoint
            type: 'GET',
            "dataSrc": function (json) {
                console.log('Full Response:', json);
                console.log('Data:', json.data);
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
            { "data": "released_by" },
            { "data": "time_released" },
            { "data": "time_approved" },
            { "data": "date_approved" },
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
                "defaultContent": '<button type="button" class="btn btn-warning edit-btn"><i class="fa fa-edit"></i></button>',
                "orderable": false,
                "class": "text-center"
            }
        ],
    });
    $('#transactionTable').on('click', '.edit-btn', function() {
        // Get the data for the clicked row
        var data = table.row($(this).closest('tr')).data();
        // Call the changeStatus function with the transaction data
        changeStatus(data);
    });
    // Refresh table data every minute
    setInterval(function() {
        table.ajax.reload(null, false);  // Reload the data without resetting pagination
    }, 6000); // Refresh every 60 seconds (1 minute)
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