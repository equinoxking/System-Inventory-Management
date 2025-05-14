$(document).ready(function() {
    $('#addRequest-btn').click(function () {
        var lastRow = $('.receive-item-row:last');
        var poNumber = lastRow.find('.control_number_received').val();
        var supplier = lastRow.find('.supplier').val();

        // Check if P.O. Number or Supplier is empty
        if (!poNumber || !supplier) {
            alert("Please enter both P.O. Number and Supplier before adding another row.");
            return;
        }

        var newRow = lastRow.clone();

        // Clear other fields except P.O and Supplier
        newRow.find('input').not('.control_number_received').val('');

        // Set values and disable in the new row
        newRow.find('.control_number_received').val(poNumber).prop('readonly', true);
        newRow.find('.supplier').val(supplier).prop('disabled', true);

        // Add hidden input to keep supplier value
        newRow.find('.supplier').after('<input type="hidden" name="supplier[]" value="' + supplier + '">');

        // Insert before the last row
        newRow.insertBefore('.receive-item-row:last');

        // Disable the original row fields
        $('.receive-item-row:first').find('.control_number_received').prop('readonly', true);
        $('.receive-item-row:first').find('.supplier').prop('disabled', true);

        // Remove button functionality
        newRow.find('.remove-deliver-item').off('click').on('click', function () {
        var row = $(this).closest('.receive-item-row');
            if (row.is($('.receive-item-row:last'))) {
                alert("You cannot delete the original row.");
            } else {
                row.remove();
            }
        });
    });
});
let currentItemIndex = -1;

$(document).on('keyup', '.search-items', function(e) {
    const $input = $(this);
    const query = $input.val();
    const $results = $input.siblings('.item-results');

    if (['ArrowUp', 'ArrowDown', 'Enter'].includes(e.key)) {
        return; // Navigation handled in keydown
    }

    if (query.length > 0) {
        $.ajax({
            url: '/searchItem',
            method: 'GET',
            data: { query },
            success: function(data) {
                $results.empty();
                currentItemIndex = -1;

                if (data.length > 0) {
                    data.forEach((item) => {
                        $results.append(`
                            <li class="list-group-item itemName-item" 
                                data-id="${item.id}" 
                                data-max_quantity="${item.inventory?.max_quantity || 0}" 
                                data-quantity="${item.inventory?.quantity || 0}">
                                <strong>${item.name}</strong>
                            </li>
                        `);
                    });
                    $results.show();
                } else {
                    $results.append('<li class="list-group-item">No items found</li>').show();
                }
            },
            error: function(err) {
                console.error('Item search error:', err);
            }
        });
    } else {
        $results.hide();
    }
});

$(document).on('keydown', '.search-items', function(e) {
    const $input = $(this);
    const $results = $input.siblings('.item-results');
    const $items = $results.find('.itemName-item');

    if (!$results.is(':visible') || $items.length === 0) return;

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        currentItemIndex = (currentItemIndex + 1) % $items.length;
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        currentItemIndex = (currentItemIndex - 1 + $items.length) % $items.length;
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (currentItemIndex >= 0) {
            selectItem($items.eq(currentItemIndex));
        }
        return;
    }

    $items.removeClass('admin-highlighted');
    if (currentItemIndex >= 0) {
        $items.eq(currentItemIndex).addClass('admin-highlighted');
    }
});

$(document).on('mouseover', '.itemName-item', function() {
    $('.itemName-item').removeClass('admin-highlighted');
    $(this).addClass('admin-highlighted');
    currentItemIndex = $(this).index();
});

$(document).on('mouseout', '.itemName-item', function() {
    $(this).removeClass('admin-highlighted');
});

$(document).on('click', '.itemName-item', function() {
    selectItem($(this));
});

function selectItem($item) {
    const itemName = $item.text().trim();
    const itemId = $item.data('id');
    const remaining_quantity = $item.data('quantity');
    const max_quantity = $item.data('max_quantity');
    const $row = $item.closest('.receive-item-row');

    $row.find('.search-items').val(itemName);
    $row.find('.selected-item-id').val(itemId);
    $row.find('#max_quantity').val(max_quantity);
    $row.find('.remaining_quantity').val(remaining_quantity);
    $row.find('.item-results').hide();

    currentItemIndex = -1;
}

$(document).on('click', function(e) {
    if (!$(e.target).closest('.search-items, .item-results').length) {
        $('.item-results').hide();
    }
});
$(document).on('input', '.search-items', function () {
    const $input = $(this);
    const $row = $input.closest('.receive-item-row');

    if ($input.val().trim() === '') {
        $row.find('.remaining_quantity').val('');
        $row.find('#max_quantity').val('');
        $row.find('.selected-item-id').val('');
    }
});
