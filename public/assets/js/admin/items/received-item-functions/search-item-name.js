$(document).ready(function() {
    $('#addRequest-btn').click(function() {
        var firstRow = $('.receive-item-row:first'); 
        var newRow = firstRow.clone();
        newRow.find('input').val('');
        newRow.find('textarea').val(''); 
        $('#receivedItem-container').prepend(newRow);
        $('#receivedItem-container').append(firstRow);
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

    $items.removeClass('highlighted');
    if (currentItemIndex >= 0) {
        $items.eq(currentItemIndex).addClass('highlighted');
    }
});

$(document).on('mouseover', '.itemName-item', function() {
    $('.itemName-item').removeClass('highlighted');
    $(this).addClass('highlighted');
    currentItemIndex = $(this).index();
});

$(document).on('mouseout', '.itemName-item', function() {
    $(this).removeClass('highlighted');
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
