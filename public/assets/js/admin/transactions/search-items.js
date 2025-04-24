let currentRequestItemIndex = -1;

$(document).ready(function() {
    $('#requestItemReceived-btn').click(function() {
        var firstRow = $('.request-item-row:first');
        var newRow = firstRow.clone();
        newRow.find('input').val('');
        newRow.find('textarea').val('');
        $('#requestItem-container').prepend(newRow);
        $('#requestItem-container').append(firstRow);
    });

    $(document).on('keyup', '.search-request-items', function(e) {
        const $input = $(this);
        const query = $input.val();
        const $results = $input.siblings('.item-results');

        // Skip arrow/enter keys here (they're handled in keydown)
        if (['ArrowUp', 'ArrowDown', 'Enter'].includes(e.key)) return;

        if (query.length > 0) {
            $.ajax({
                url: '/searchRequestItem',
                method: 'GET',
                data: { query },
                success: function(data) {
                    $results.empty();
                    currentRequestItemIndex = -1;

                    if (data.length > 0) {
                        data.forEach(function(item) {
                            $results.append(`
                                <li class="list-group-item requestItemName" 
                                    data-id="${item.id}" 
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
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        } else {
            $results.hide();
        }
    });

    $(document).on('keydown', '.search-request-items', function(e) {
        const $input = $(this);
        const $results = $input.siblings('.item-results');
        const $items = $results.find('.requestItemName');

        if (!$results.is(':visible') || $items.length === 0) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            currentRequestItemIndex = (currentRequestItemIndex + 1) % $items.length;
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            currentRequestItemIndex = (currentRequestItemIndex - 1 + $items.length) % $items.length;
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (currentRequestItemIndex >= 0) {
                selectRequestItem($items.eq(currentRequestItemIndex));
            }
            return;
        }

        $items.removeClass('request-highlight');
        if (currentRequestItemIndex >= 0) {
            $items.eq(currentRequestItemIndex).addClass('request-highlight');
        }

    });

    $(document).on('mouseover', '.requestItemName', function() {
        $('.requestItemName').removeClass('request-highlight');
        $(this).addClass('request-highlight');
        currentRequestItemIndex = $(this).index();
    });
    
    $(document).on('mouseout', '.requestItemName', function() {
        $(this).removeClass('request-highlight');
    });    

    $(document).on('click', '.requestItemName', function() {
        selectRequestItem($(this));
    });

    function selectRequestItem($item) {
        const itemName = $item.text().trim();
        const itemId = $item.data('id');
        const quantity = $item.data('quantity');
        const $row = $item.closest('.request-item-row');

        $row.find('.search-request-items').val(itemName);
        $row.find('.selected-item-id').val(itemId);
        $row.find('.quantity').val(quantity);
        $row.find('.item-results').hide();

        currentRequestItemIndex = -1;
    }

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-request-items, .item-results').length) {
            $('.item-results').hide();
        }
    });

    $(document).on('click', '.remove-request-item', function() {
        if ($('.request-item-row').length > 1) {
            $(this).closest('.request-item-row').remove();
        }
    });
    $(document).on('input', '.search-request-items', function () {
        const $input = $(this);
        const $row = $input.closest('.request-item-row');
    
        if ($input.val().trim() === '') {
            $row.find('.quantity').val(''); // or set to 0
            $row.find('.selected-item-id').val('');
        }
    });
    
});
