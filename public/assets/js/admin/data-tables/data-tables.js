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
    var table = $('#transactionTable').dataTable({
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