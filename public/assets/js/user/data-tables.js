$(document).ready(function () {
    $(function () {
        var table = $('#transactionsTable').DataTable({
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
                url: '/user/refreshTransactions',  // Replace with your actual endpoint
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
                        } else if (data === 'Released') {
                            return '<span class="badge badge-released"><i class="fas fa-box-open"></i> Released</span>';
                        } else if (data === 'Ready for Release') {
                            return '<span class="badge badge-release"><i class="fas fa-cloud-upload-alt"></i> Ready for Release</span>';
                        } else {
                            return '<span class="badge badge-completed"><i class="fas fa-check-circle"></i> Completed</span>';
                        }
                    }
                },
                {
                    "data": null,  // This column will have the action buttons
                    "render": function(data, type, row) {
                        if (row.remarks === 'Released') {
                            return '<button type="button" class="btn btn-warning updateBtn" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;"><i class="fa fa-edit"></i></button>';
                        }else if(row.status == 'Pending'){
                            return '<button type="button" class="btn btn-danger cancelBtn" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;"><i class="fa fa-trash"></i></button>';
                        } else {
                            return ''; 
                        }
                    },
                    "orderable": false,
                    "class": "text-center"
                }
            ],
        });
        $('#transactionsTable').on('click', '.updateBtn', function() {
            var data = table.row($(this).closest('tr')).data();
            userAcceptance(data);
        });
        $('#transactionsTable').on('click', '.cancelBtn', function() {
            var data = table.row($(this).closest('tr')).data();
            userCancel(data);
        });
        setInterval(function() {
            table.ajax.reload(null, false);  
        }, 6000);
    });
});
$(function () {
        var table = $('#historyTransactionTable').DataTable({
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
                url: '/user/refreshActedTransactions',  // Replace with your actual endpoint
                type: 'GET',
                "dataSrc": function (json) {
                    console.log("Response Data: ", json); // Log the entire JSON response
                    return json.data; // Ensure that 'data' is the key where the array of rows is located
                }
            },
            "columns": [
                { "data": "time_request" },
                { "data": "transaction_number" },
                { "data": "stock_on_hand" },
                { "data": "quantity" },
                { "data": "unit" },
                { "data": "item_name" },
                {
                    data: null, // No direct data
                    render: function (data, type, row) {
                        return row.date_approved + ' ' + row.time_approved;
                    },
                    title: 'Date/Time Acted' // Title for the merged column
                },
                { "data": "request_aging" },
                { "data": "released_by" },
                { "data": "time_released" },
                { "data": "released_aging" },
                {
                    "data": "status",
                    "render": function (data, type, row) {
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
                    "render": function (data, type, row) {
                        if (data === 'For Review') {
                            return '<span class="badge badge-forReview"><i class="fas fa-search"></i> For Review</span>';
                        } else if (data === 'Released') {
                            return '<span class="badge badge-released"><i class="fas fa-box-open"></i> Released</span>';
                        } else if (data === 'Ready for Release') {
                            return '<span class="badge badge-release"><i class="fas fa-cloud-upload-alt"></i> Ready for Release</span>';
                        } else {
                            return '<span class="badge badge-completed"><i class="fas fa-check-circle"></i> Completed</span>';
                        }
                    }
                },
            ],
        });
    setInterval(function() {
        table.ajax.reload(null, false);  
    }, 6000)
});


$(function () {
    var table = $('#transactionsVoided').dataTable({
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
    var table = $('#notificationTable').DataTable({
        "aLengthMenu": [[ 10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "All"]],
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
        "columnDefs": [
            {
                "targets": 1,
                "visible": false,
                "searchable": false
            }
        ]
    });
});