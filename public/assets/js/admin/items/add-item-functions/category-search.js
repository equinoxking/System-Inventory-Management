$(document).ready(function() {
    $('#addItem-btn').click(function() {
        var firstRow = $('.item-row:first'); 
        var newRow = firstRow.clone();
        newRow.find('input').val('');
        newRow.find('textarea').val(''); 
        $('#item-container').prepend(newRow);
        $('#item-container').append(firstRow);
    });
});
let currentCategoryIndex = -1;

$(document).on('keyup', '.search-category', function(e) {
    const $input = $(this);
    const query = $input.val();
    const $results = $input.siblings('.category-results');

    if (['ArrowUp', 'ArrowDown', 'Enter'].includes(e.key)) {
        return; // Let keydown handle navigation
    }

    if (query.length > 0) {
        $.ajax({
            url: '/search-categories',
            method: 'GET',
            data: { query },
            success: function(data) {
                $results.empty();
                currentCategoryIndex = -1;

                if (data.length > 0) {
                    data.forEach((category) => {
                        $results.append(`
                            <li class="list-group-item category-item" data-id="${category.id}">
                                <strong>${category.name}</strong>
                            </li>
                        `);
                    });
                    $results.show();
                } else {
                    $results.append('<li class="list-group-item">No categories found</li>').show();
                }
            },
            error: function(err) {
                console.error('Category search error:', err);
            }
        });
    } else {
        $results.hide();
    }
});

$(document).on('keydown', '.search-category', function(e) {
    const $input = $(this);
    const $results = $input.siblings('.category-results');
    const $items = $results.find('.category-item');

    if (!$results.is(':visible') || $items.length === 0) return;

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        currentCategoryIndex = (currentCategoryIndex + 1) % $items.length;
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        currentCategoryIndex = (currentCategoryIndex - 1 + $items.length) % $items.length;
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (currentCategoryIndex >= 0) {
            selectCategory($items.eq(currentCategoryIndex));
        }
        return;
    }

    $items.removeClass('highlighted');
    if (currentCategoryIndex >= 0) {
        $items.eq(currentCategoryIndex).addClass('highlighted');
    }
});

$(document).on('mouseover', '.category-item', function() {
    $('.category-item').removeClass('highlighted');
    $(this).addClass('highlighted');
    currentCategoryIndex = $(this).index();
});

$(document).on('mouseout', '.category-item', function() {
    $(this).removeClass('highlighted');
});

$(document).on('click', '.category-item', function() {
    selectCategory($(this));
});

function selectCategory($item) {
    const categoryName = $item.text().trim();
    const categoryId = $item.data('id');
    const $row = $item.closest('.item-row');

    $row.find('.search-category').val(categoryName);
    $row.find('.selected-category-id').val(categoryId);
    $row.find('.category-results').hide();

    currentCategoryIndex = -1;
}

$(document).on('click', function(e) {
    if (!$(e.target).closest('.search-category, .category-results').length) {
        $('.category-results').hide();
    }
});
$(document).on('click', '.remove-add-item', function() {
    if ($('.item-row').length > 1) {
        $(this).closest('.item-row').remove();
    }
});