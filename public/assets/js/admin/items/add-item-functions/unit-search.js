 // Search for units in each dynamically added unit field
 $(document).on('keyup', '.search-unit', function() {
    var query = $(this).val();
    var $unitResults = $(this).siblings('.unit-results');
    if (query.length > 0) {
        $.ajax({
            url: '/search-units',
            method: 'GET',
            data: { query: query },
            success: function(data) {
                $unitResults.empty();
                if (data.length > 0) {
                    data.forEach(function(unit) {
                        $unitResults.append(`
                            <li class="list-group-item unit-item" data-id="${unit.id}">
                                <strong>${unit.name}</strong>
                            </li>
                        `);
                    });
                    $unitResults.show();
                } else {
                    $unitResults.append('<li class="list-group-item">No units found</li>');
                    $unitResults.show();
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    } else {
        $unitResults.hide();
    }
});

$(document).on('click', '.unit-item', function() {
    var unitName = $(this).text().trim();
    var unitId = $(this).data('id');
    $(this).closest('.item-row').find('.search-unit').val(unitName);
    $(this).closest('.item-row').find('.selected-unit-id').val(unitId);
    $(this).closest('.item-row').find('.unit-results').hide();
    $('#search-unit').val(unitName); // Set category name in input
    $('#selected-unit-id').val(unitId); // Set category ID in hidden input

    // Append this selected category ID to the list of selected categories
    var selectedUnitIds = $('input[name="unitId[]"]').map(function() {
        return $(this).val();
    }).get();  // Get all the current category IDs in the array

    // Check if the categoryId is already in the array, if not, add it
    if (!selectedUnitIds.includes(unitId.toString())) {
        $('<input>', {
            type: 'text',
            name: 'unitId[]',
            value: unitId,
            hidden: true
        }).appendTo('#createItem-form'); // Append new hidden input for selected category ID
    }
});

// Remove item row
$(document).on('click', '.remove-item', function() {
    if ($('.item-row').length > 1) {
        $(this).closest('.item-row').remove();
    }
});
// Hide results if clicked outside of search box
$(document).on('click', function(e) {
    if (!$(e.target).closest('.search-category').length) {
        $('.category-results').hide();
    }
    if (!$(e.target).closest('.search-unit').length) {
        $('.unit-results').hide();
    }
});
