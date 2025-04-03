$(document).ready(function() {
    $('#receivedItemReceived-btn').click(function() {
        var newRow = $('.receive-item-row:first').clone(); 
        newRow.find('input').val(''); 
        $('#receivedItem-container').append(newRow); 
    });

    $(document).on('keyup', '.search-items', function() {
        var query = $(this).val();
        var $itemNameResults = $(this).siblings('.item-results');
        if (query.length > 0) {
            $.ajax({
                url: '/searchItem',
                method: 'GET',
                data: { query: query },
                success: function(data) {
                    $itemNameResults.empty();
                    if (data.length > 0) {
                        data.forEach(function(item) {
                            $itemNameResults.append(`
                                <li class="list-group-item itemName-item" data-id="${item.id}" data-max_quantity="${item.inventory.max_quantity || 0}"  data-quantity="${item.inventory.quantity || 0}">
                                    <strong>${item.name}</strong>
                                </li>
                            `);
                        });
                        $itemNameResults.show();
                    } else {
                        $itemNameResults.append('<li class="list-group-item">No items found</li>');
                        $itemNameResults.show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        } else {
            $itemNameResults.hide();
        }
    });
    $(document).on('click', '.itemName-item', function() {
        var itemName = $(this).text().trim();
        var itemId = $(this).data('id');
        var $parentRow = $(this).closest('.receive-item-row'); 
        var remaining_quantity = $(this).data('quantity');
        var max_quantity = $(this).data('max_quantity');
        $parentRow.find('.search-items').val(itemName); 
        $parentRow.find('.selected-item-id').val(itemId); 
        $parentRow.find('#max_quantity').val(max_quantity);
        $parentRow.find('.remaining_quantity').val(remaining_quantity);
        $parentRow.find('.item-results').hide();
    });

    $(document).on('click', '.remove-received-item', function() {
        if ($('.receive-item-row').length > 1) {
            $(this).closest('.receive-item-row').remove();
        }
    });
    
});
