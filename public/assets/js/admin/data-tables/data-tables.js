$(function () {
    var table = $('#itemsTable').DataTable({
        "aLengthMenu": [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "All"]],
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
        "order": [[0, 'asc']],
    });

    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        let min = parseFloat($('#min-quantity-filter').val()) || 0;
        let max = parseFloat($('#max-quantity-filter').val()) || Infinity;
        let quantity = parseFloat(data[3]) || 0;

        return quantity >= min && quantity <= max;
    });

    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        let stockFilter = $('#stock-level-filter').val();
        let quantity = parseFloat(data[3]) || 0;
        let buffer = parseFloat(data[4]) || 0;

        if (stockFilter === 'critical') {
            return quantity <= buffer;
        } else if (stockFilter === 'normal') {
            return quantity > buffer;
        }
        return true;
    });

    $('#category-filter').on('keyup', function () {
        var val = $(this).val();
        table.column(1).search(val, false, true).draw(); 
    });

    $('#unit-filter').on('keyup', function () {
        var val = $(this).val();
        table.column(2).search(val, false, true).draw(); 
    });

    $('#status-filter').on('change', function () {
        var statusVal = $(this).val().toLowerCase();

        table.rows().every(function () {
            var row = this.node();
            var quantity = parseInt($(row).find('td').eq(3).text().trim(), 10);
            var statusText = $(row).find('td').eq(5).text().trim().toLowerCase();

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
        // "lengthChange": false,
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
        "order": [[0, 'desc']], 
        "columns": [
            { "data": "transaction_number" },
            { "data": "stock_on_hand"},
            { "data": "quantity" },
            { "data": "unit" },
            { "data": "item_name" },
            { "data": "client_name" },
            { "data": "time_request" },
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
            },
        ],
        columnDefs: [
        {
            targets: 0, // Index of the column to hide
            visible: false,
            searchable: false
        }
    ]
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
        },
        "order": [[0, 'desc']], 
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
        "order": [[0, 'desc']], 
        "columns": [
            { "data": "transaction_number" },
            { "data": "stock_on_hand"},
            { "data": "quantity" },
            { "data": "unit" },
            { "data": "item_name" },
            { "data": "client_name" },
            { "data": "time_request" },
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
                data: null,
                render: function (data, type, row) {
                    const dateApproved = row.date_approved || '';
                    const timeReleased = row.time_released || '';
            
                    const combined = `${dateApproved} ${timeReleased}`.trim();
            
                    return combined === '' ? '--' : combined;
                },
                title: 'Date/Time Released'
            },       
            {
                data: "acceptance",
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
                "data": "remarks",
                "render": function(data, type, row) {
                    if (data === 'For Review') {
                        return '<span class="badge badge-forReview"><i class="fas fa-search"></i> For Review</span>';
                    } else if (data === 'Released') {
                        return '<span class="badge badge-released"><i class="fas fa-box-open"></i> Released</span>';
                    } else if (data === 'Ready for Release') {
                        return '<span class="badge badge-release"><i class="fas fa-cloud-upload-alt"></i> Ready for Release</span>';
                    }else if (data === 'Denied') {
                        return '<span class="badge badge-denied"><i class="fas fa-ban"></i> Disapproved</span>';
                    }else if (data === 'Canceled') {
                        return '<span class="badge badge-canceled"><i class="fas fa-times"></i> Canceled</span>';
                    } else {
                        return '<span class="badge badge-completed"><i class="fas fa-check-circle"></i> Completed</span>';
                    }
                }
            },
            {
                data: "reason",
                render: function(data) {
                    return data === null || data === "" ? "--" : data;
                }
            },     
        ],
        columnDefs: [
        {
            targets: 0, // Index of the column to hide
            visible: false,
            searchable: false
        }
        ],
        rowCallback: function(row, data) {
            $(row).removeClass('dt-row-denied dt-row-canceled');
            if (data.remarks === 'Denied') {
                $(row).addClass('dt-row-denied');
            } else if (data.remarks === 'Canceled') {
                $(row).addClass('dt-row-canceled');
            }
        } 
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
        "aLengthMenu": [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "All"]],
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
        "order": [[0, 'desc']]
        
    });
});
$(function () {
    var table = $('#receivesTable').DataTable({
        "aLengthMenu": [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "All"]],
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
        "order": [[0, 'desc']],
    });

    $('#po-filter').on('keyup', function () {
        var val = $(this).val();
        table.column(1).search(val, false, true).draw(); 
    });

    $('#supplier-filter').on('keyup', function () {
        var val = $(this).val();
        table.column(2).search(val, false, true).draw(); 
    });

});
$(function () {
    var table = $('#availableItemTable').DataTable({
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
         "order": [[3, 'asc']],
    });
});
$(function () {
    var table = $('#top10Table').DataTable({
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
    });
});
$(function () {
    var table = $('#reportsTable').DataTable({
        "aLengthMenu": [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "All"]],
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
        "order": [[1, 'desc']],
    });
});
$(function () {
    var table = $('#unitTable').dataTable({
        "aLengthMenu": [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "All"]],
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
        "order": [[0, 'desc']],
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
        "order": [[0, 'desc']],
    });
});