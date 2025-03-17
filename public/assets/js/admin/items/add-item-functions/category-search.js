/* Search for categories */
$(document).ready(function() {
    // Add new item row
    $('#addItem-btn').click(function() {
        var newRow = $('.item-row:first').clone(); // Clone the first row to create a new one
        newRow.find('input').val(''); // Clear the values of inputs
        $('#item-container').append(newRow); // Append the new row
    });

    // Search for categories in each dynamically added category field
    $(document).on('keyup', '.search-category', function() {
        var query = $(this).val();
        var $categoryResults = $(this).siblings('.category-results');
        if (query.length > 0) {
            $.ajax({
                url: '/search-categories',
                method: 'GET',
                data: { query: query },
                success: function(data) {
                    $categoryResults.empty();
                    if (data.length > 0) {
                        data.forEach(function(category) {
                            $categoryResults.append(`
                                <li class="list-group-item category-item" data-id="${category.id}">
                                    <strong>${category.name}</strong>
                                </li>
                            `);
                        });
                        $categoryResults.show();
                    } else {
                        $categoryResults.append('<li class="list-group-item">No categories found</li>');
                        $categoryResults.show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        } else {
            $categoryResults.hide();
        }
    });

    $(document).on('click', '.category-item', function() {
        var categoryName = $(this).text().trim();
        var categoryId = $(this).data('id');
        $(this).closest('.item-row').find('.search-category').val(categoryName);
        $(this).closest('.item-row').find('.selected-category-id').val(categoryId);
        $(this).closest('.item-row').find('.category-results').hide();
        $('#search').val(categoryName); // Set category name in input
        $('#selected-category-id').val(categoryId); // Set category ID in hidden input

        // Append this selected category ID to the list of selected categories
        var selectedCategoryIds = $('input[name="categoryId[]"]').map(function() {
            return $(this).val();
        }).get();  // Get all the current category IDs in the array

        // Check if the categoryId is already in the array, if not, add it
        if (!selectedCategoryIds.includes(categoryId.toString())) {
            $('<input>', {
                type: 'text',
                name: 'categoryId[]',
                value: categoryId,
                hidden: true
            }).appendTo('#createItem-form'); // Append new hidden input for selected category ID
        }
    });
});