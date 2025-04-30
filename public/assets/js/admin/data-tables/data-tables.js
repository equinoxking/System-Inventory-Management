$(function () {
    var table = $('#itemsTable').DataTable({
        "pageLength": -1,
        "lengthChange": false,
        "responsive": {
            breakpoints: [
                { name: 'xl', width: Infinity },
                { name: 'lg', width: 1200 },
                { name: 'md', width: 992 },
                { name: 'sm', width: 768 },
                { name: 'xs', width: 576 }
            ]
        },
        "order": [[0, 'asc']],
    });

    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        let min = parseFloat($('#min-quantity-filter').val()) || 0;
        let max = parseFloat($('#max-quantity-filter').val()) || Infinity;
        let quantity = parseFloat(data[4]) || 0;

        return quantity >= min && quantity <= max;
    });

    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        let stockFilter = $('#stock-level-filter').val();
        let quantity = parseFloat(data[4]) || 0;
        let buffer = parseFloat(data[5]) || 0;

        if (stockFilter === 'critical') {
            return quantity < buffer;
        } else if (stockFilter === 'normal') {
            return quantity >= buffer;
        }
        return true;
    });

    $('#category-filter').on('change', function () {
        var val = $(this).val();
        table.column(1).search(val ? '^' + val + '$' : '', true, false).draw();
    });

    $('#unit-filter').on('change', function () {
        var val = $(this).val();
        table.column(3).search(val ? '^' + val + '$' : '', true, false).draw();
    });

    $('#status-filter').on('change', function () {
        var statusVal = $(this).val().toLowerCase();

        table.rows().every(function () {
            var row = this.node();
            var quantity = parseInt($(row).find('td').eq(4).text().trim(), 10);
            var statusText = $(row).find('td').eq(7).text().trim().toLowerCase();

            var showRow = false;

            if (statusVal === '') {
                if (quantity > 0) {
                    showRow = true;
                }
            } else {
                if (statusVal === 'available' && quantity > 0 && statusText === 'available') {
                    showRow = true;
                } else if (statusVal === 'unavailable' && quantity === 0 && statusText === 'unavailable') {
                    showRow = true;
                }
            }

            if (showRow) {
                $(row).show();
            } else {
                $(row).hide();
            }
        });
    });

    $('#min-quantity-filter, #max-quantity-filter, #stock-level-filter').on('input change', function () {
        table.draw();
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
        "processing": false,
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
            { "data": "stock_on_hand"},
            { "data": "quantity" },
            { "data": "unit" },
            { "data": "item_name" },
            { "data": "client_name" },
            {
                data: null, // No direct data
                render: function(data, type, row) {
                    return row.date_approved + ' ' + row.time_approved;
                },
                title: 'Date/Time Acted' // Title for the merged column
            },
            { "data": "request_aging" },
            { "data": "released_by" },
            { "data": "time_released" },
            {
                "data": "status",
                "render": function(data, type, row) {
                    if (data === 'Accepted') {
                        return '<span class="badge-success"><i class="fas fa-check-circle"></i></span>';
                    } else if (data === 'Pending') {
                        return '<span class="badge-pending"><i class="fas fa-clock"></i></span>';
                    } else {
                        return '<span class="badge-danger"><i class="fas fa-times-circle"></i></span>';
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
                        return '<button type="button" class="btn btn-warning edit-btn" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;"><i class="fa fa-edit"></i></button>';
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
        }
    });
});
$(function () {
    var table = $('#transactionHistoryTable').DataTable({
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
        "processing": false,
        "serverSide": false,
        "ajax": {
            url: '/admin/refreshActedTransactions',  // Replace with your actual endpoint
            type: 'GET',
            "dataSrc": function (json) {
                return json.data;
            } // Ensure that 'data' is the key where the array of rows is located
        },
        "columns": [
            { "data": "time_request" },
            { "data": "transaction_number" },
            { "data": "stock_on_hand"},
            { "data": "quantity" },
            { "data": "unit" },
            { "data": "item_name" },
            { "data": "client_name" },
            {
                data: null,
                render: function(data, type, row) {
                    let date = row.date_approved || '--';
                    let time = row.time_approved;
                    return date + ' ' + time;
                },
                title: 'Date/Time Acted'
            },
            {
                data: "request_aging",
                render: function(data) {
                    return data === null || data === "" ? "--" : data;
                }
            },
            {
                data: "released_by",
                render: function(data) {
                    return data === null || data === "" ? "--" : data;
                }
            },
            {
                data: "time_released",
                render: function(data) {
                    return data === null || data === "" ? "--" : data;
                }
            },
            {
                data: "released_aging",
                render: function(data) {
                    return data === null || data === "" ? "--" : data;
                }
            },            
            {
                "data": "status",
                "render": function(data, type, row) {
                    if (data === 'Accepted') {
                        return '<span class="badge-success"><i class="fas fa-check-circle"></i></span>';
                    } else if (data === 'Pending') {
                        return '<span class="badge-pending"><i class="fas fa-clock"></i></span>';
                    } else {
                        return '<span class="badge-danger"><i class="fas fa-times-circle"></i></span>';
                    }
                }
            },
            {
                "data": "remarks",
                "render": function(data, type, row) {
                    if (data === 'For Review') {
                        return '<span class="badge badge-forReview"><i class="fas fa-search"></i> For Review</span>';
                    } else if (data === 'Released') {
                        return '<span class="badge badge-released"><i class="fas fa-box-open"></i> Released</span>';
                    } else if (data === 'Ready for Release') {
                        return '<span class="badge badge-release"><i class="fas fa-cloud-upload-alt"></i> Ready for Release</span>';
                    }else if (data === 'Denied') {
                        return '<span class="badge badge-denied"><i class="fas fa-ban"></i> Denied</span>';
                    }else if (data === 'Canceled') {
                        return '<span class="badge badge-canceled"><i class="fas fa-times"></i> Canceled</span>';
                    } else {
                        return '<span class="badge badge-completed"><i class="fas fa-check-circle"></i> Completed</span>';
                    }
                }
            },
        ],
    });
    setInterval(function() {
        table.ajax.reload(null, false);  
    }, 6000);
});
$(function () {
    var table = $('#auditTable').DataTable({
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
        },
        "order": [[0, 'desc']], // Keeps the first column (0) sorted by default
        "columnDefs": [
            {
                "targets": 0, // Target the first column
                "visible": false, // Hide the column
                "searchable": false, // Make it non-searchable
                "orderable": false // Optional: Make it non-sortable (remove if you want to keep sorting)
            }
        ]
    });
});

$(function () {
    var table = $('#categoryTable').dataTable({
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
        "order": [[2, 'desc']]
        
    });
});
$(function () {
    var table = $('#receivesTable').DataTable({
        "aLengthMenu": [[5, 10, 25, 50, 75, 100, -1], [5, 10, 25, 50, 75, 100, "All"]],
        "pageLength": 5,
        "responsive": true,
        "order": [[0, 'desc']],
        "autoWidth": false,
        "processing": false,
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
            { "data": "category" }, 
            { "data": "item_name" }, 
            { "data": "unit_name" },
            { "data": "received_quantity" },
            { "data": "remaining_quantity" },
            { "data": "supplier" },
            { "data": "created_at" }, 
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
$(function () {
    var table = $('#availableItemTable').DataTable({
        "aLengthMenu": [[5, 10, 25, 50, 75, 100], [5, 10, 25, 50, 75, 100]],
        "pageLength": 5,
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

    $('#category-filter').on('change', function () {
        var selected = $(this).val();
        if (selected) {
            table.column(0) // 1 = index of 'Category' column
                 .search('^' + selected + '$', true, false)
                 .draw();
        } else {
            table.column(0).search('').draw(); // Reset filter
        }
    });
});
$(function () {
    var table = $('#notificationTable').DataTable({
        "aLengthMenu": [[5, 10, 25, 50, 75, 100], [5, 10, 25, 50, 75, 100]],
        "pageLength": 10,
        "lengthChange": false ,
        "responsive": {
            breakpoints: [
                { name: 'xl', width: Infinity },
                { name: 'lg', width: 1200 },
                { name: 'md', width: 992 },
                { name: 'sm', width: 768 },
                { name: 'xs', width: 576 }
            ]
        },
    });
});
$(function () {
    var table = $('#reportsTable').DataTable({
        "aLengthMenu": [[5, 10, 25, 50, 75, 100], [5, 10, 25, 50, 75, 100]],
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
        "order": [[3, 'desc']],
    });
});
$(function () {
    var table = $('#unitTable').dataTable({
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
    });
});
$(function () {
    var table = $('#adminTable').dataTable({
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
        "order": [[2, 'desc']],
    });
});