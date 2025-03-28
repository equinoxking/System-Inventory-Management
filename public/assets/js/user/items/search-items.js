$(document).ready(function() {
    $('#requestItemReceived-btn').click(function() {
        var newRow = $('.request-item-row:first').clone(); 
        newRow.find('input').val(''); 
        $('#requestItem-container').append(newRow); 
    });
    $(document).on('keyup', '.search-request-items', function() {
        var query = $(this).val();
        var $itemNameResults = $(this).siblings('.item-results');
        if (query.length > 0) {
            $.ajax({
                url: '/searchRequestItem',
                method: 'GET',
                data: { query: query },
                success: function(data) {
                    $itemNameResults.empty();
                    if (data.length > 0) {
                        data.forEach(function(item) {
                            $itemNameResults.append(`
                                <li class="list-group-item requestItemName" data-id="${item.id}" data-quantity="${item.inventory.quantity || 0}"">
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
    $(document).on('click', '.requestItemName', function() {
        var itemName = $(this).text().trim();
        var itemId = $(this).data('id');
        var quantity = $(this).data('quantity');
        var $parentRow = $(this).closest('.request-item-row'); 
        var requestQuantity = parseInt($(this).val()) || 0; 
        $parentRow.find('.search-request-items').val(itemName); 
        $parentRow.find('.selected-item-id').val(itemId); 
        $parentRow.find('.quantity').val(quantity)
        $parentRow.find('.item-results').hide();
    });
    $(document).on('click', '.remove-request-item', function() {
        if ($('.request-item-row').length > 1) {
            $(this).closest('.request-item-row').remove();
        }
    });
});
