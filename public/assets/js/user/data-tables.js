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
            "order": [[0, 'desc']],
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
                { "data": "transaction_number" },
                { "data": "stock_on_hand"},
                { "data": "quantity" },
                { "data": "unit" },
                { "data": "item_name" },
                { "data": "time_request" },
                {
                    data: null, // No direct data
                    render: function(data, type, row) {
                        return row.date_approved + ' ' + row.time_approved;
                    },
                    title: 'Date/Time Acted' // Title for the merged column
                },
                { "data": "request_aging" },
                { "data": "released_by" },
                {
                    data: null, // No direct data
                    render: function (data, type, row) {
                        return row.date_approved + ' ' + row.time_released;
                    },
                    title: 'Date/Time Released' // Title for the merged column
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
                },
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
                { "data": "transaction_number" },
                { "data": "stock_on_hand" },
                { "data": "quantity" },
                { "data": "unit" },
                { "data": "item_name" },
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
                            return '<span class="badge badge-denied"><i class="fas fa-ban"></i> Denied</span>';
                        }else if (data === 'Canceled') {
                            return '<span class="badge badge-canceled"><i class="fas fa-times"></i> Canceled</span>';
                        } else {
                            return '<span class="badge badge-completed"><i class="fas fa-check-circle"></i> Completed</span>';
                        }
                    }
                },
                { "data": "reason"},
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