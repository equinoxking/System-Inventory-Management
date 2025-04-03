/* Search for categories */
$(document).ready(function() {

    // Add new item row
    $('#addItem-btn').click(function() {
        // Get the first row and clone it
        var firstRow = $('.item-row:first'); // Get the first row
        var newRow = firstRow.clone(); // Clone the first row
        
        // Clear the input values of the new row
        newRow.find('input').val('');
        newRow.find('textarea').val(''); // Clear the textarea if there is one
        
        // Insert the new row at the top of the container (making it the first row)
        $('#item-container').prepend(newRow);
        
        // Move the original first row to the last position
        $('#item-container').append(firstRow);
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

    // $(document).on('click', '.category-item', function() {
    //     var categoryName = $(this).text().trim();
    //     var categoryId = $(this).data('id');
    //     $(this).closest('.item-row').find('.search-category').val(categoryName);
    //     $(this).closest('.item-row').find('.selected-category-id').val(categoryId);
    //     $(this).closest('.item-row').find('.category-results').hide();
    //     $('#search').val(categoryName); // Set category name in input
    //     $('#selected-category-id').val(categoryId); // Set category ID in hidden input

    //     // Append this selected category ID to the list of selected categories
    //     var selectedCategoryIds = $('input[name="categoryId[]"]').map(function() {
    //         return $(this).val();
    //     }).get();  // Get all the current category IDs in the array

    //     // Check if the categoryId is already in the array, if not, add it
    //     if (!selectedCategoryIds.includes(categoryId.toString())) {
    //         $('<input>', {
    //             type: 'text',
    //             name: 'categoryId[]',
    //             value: categoryId,
    //             hidden: true
    //         }).appendTo('#createItem-form'); // Append new hidden input for selected category ID
    //     }
    // });
    var currentIndex = -1;
    $('#createItem-form').on('keydown', function(e) {
        if (e.key === 'Enter' ) {
            e.preventDefault(); // Prevent form submission for all inputs except the .search-category field
        }
    });
    $(document).on('click', '.category-item', function() {
        selectCategory($(this));
    });

    // Keyboard navigation (arrow keys and Enter)
    $(document).on('keydown', '.search-category', function(e) {
        var $categoryResults = $(this).siblings('.category-results');
        var $items = $categoryResults.find('.category-item');
        
        // Only handle arrow keys when category results are visible
        if ($items.length === 0 || !$categoryResults.is(':visible')) return;

        // Clear the previous highlighting
        $items.removeClass('highlighted');

        if (e.key === 'ArrowDown') {
            // Navigate down through the list
            currentIndex = Math.min(currentIndex + 1, $items.length - 1);
        } else if (e.key === 'ArrowUp') {
            // Navigate up through the list
            currentIndex = Math.max(currentIndex - 1, 0);
        } else if (e.key === 'Enter') {
            // Select the currently highlighted item
            selectCategory($items.eq(currentIndex));
            return; // Prevent further propagation
        }

        // Highlight the current item
        $items.eq(currentIndex).addClass('highlighted');
    });

    // Function to handle category selection
    function selectCategory($item) {
        var categoryName = $item.text().trim();
        var categoryId = $item.data('id');
        
        var $row = $item.closest('.item-row');
        $row.find('.search-category').val(categoryName);
        $row.find('.selected-category-id').val(categoryId);
        $row.find('.category-results').hide();

        // Set category name and ID in hidden inputs
        $('#search').val(categoryName);
        $('#selected-category-id').val(categoryId);

        // Append this selected category ID to the list of selected categories
        var selectedCategoryIds = $('input[name="categoryId[]"]').map(function() {
            return $(this).val();
        }).get();

        if (!selectedCategoryIds.includes(categoryId.toString())) {
            $('<input>', {
                type: 'text',
                name: 'categoryId[]',
                value: categoryId,
                hidden: true
            }).appendTo('#createItem-form');
        }
    }

    // Optional: Highlight category item on hover (optional for better UX)
    $(document).on('mouseover', '.category-item', function() {
        $(this).addClass('highlighted');
    }).on('mouseout', '.category-item', function() {
        $(this).removeClass('highlighted');
    });
});