let currentIndex = -1;

$(document).on('keyup', '.search-unit', function(e) {
    const $input = $(this);
    const query = $input.val();
    const $results = $input.siblings('.unit-results');

    if (['ArrowUp', 'ArrowDown', 'Enter'].includes(e.key)) {
        return; // Let keydown handle navigation
    }

    if (query.length > 0) {
        $.ajax({
            url: '/search-units',
            method: 'GET',
            data: { query },
            success: function(data) {
                $results.empty();
                currentIndex = -1;

                if (data.length > 0) {
                    data.forEach((unit) => {
                        $results.append(`
                            <li class="list-group-item unit-item" data-id="${unit.id}">
                                <strong>${unit.name}</strong>
                            </li>
                        `);
                    });
                    $results.show();
                } else {
                    $results.append('<li class="list-group-item">No units found</li>').show();
                }
            },
            error: function(err) {
                console.error('Search error:', err);
            }
        });
    } else {
        $results.hide();
    }
});

$(document).on('keydown', '.search-unit', function(e) {
    const $input = $(this);
    const $results = $input.siblings('.unit-results');
    const $items = $results.find('.unit-item');

    if (!$results.is(':visible') || $items.length === 0) return;

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        currentIndex = (currentIndex + 1) % $items.length;
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        currentIndex = (currentIndex - 1 + $items.length) % $items.length;
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (currentIndex >= 0) {
            selectUnit($items.eq(currentIndex));
        }
        return;
    }

    $items.removeClass('highlighted');
    if (currentIndex >= 0) {
        $items.eq(currentIndex).addClass('highlighted');
    }
});

$(document).on('mouseover', '.unit-item', function() {
    $('.unit-item').removeClass('highlighted');
    $(this).addClass('highlighted');
    currentIndex = $(this).index();
});

$(document).on('mouseout', '.unit-item', function() {
    $(this).removeClass('highlighted');
});

$(document).on('click', '.unit-item', function() {
    selectUnit($(this));
});

function selectUnit($item) {
    const unitName = $item.text().trim();
    const unitId = $item.data('id');
    const $row = $item.closest('.item-row');

    $row.find('.search-unit').val(unitName);
    $row.find('.selected-unit-id').val(unitId);
    $row.find('.unit-results').hide();

    currentIndex = -1;
}

$(document).on('click', function(e) {
    if (!$(e.target).closest('.search-unit, .unit-results').length) {
        $('.unit-results').hide();
    }
});
