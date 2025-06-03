function editItem(item) {
    var item = JSON.parse(item);
    $('#editItemModal').modal('show'); 
    $('#edit-item-id').val(item.id); 
    $('#edit-item-name').val(item.name);
    $('#edit-buffer').val(item.inventory.min_quantity);
    $('#edit-category').val(item.category_id);
    $('#edit-unit').val(item.inventory.unit_id)
    $('#edit-item-name-readonly').val(item.name);
}

$('#edit-item-close-btn').click(function(){
    $('#editItemModal').modal('hide');
});
/* Search for categories */
$(document).ready(function() {
    $(document).on('submit', '#edit-item-form', function(event){
        event.preventDefault();
        var formData = $('#edit-item-form').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/update-item',
            type: 'PATCH',
            data: formData,
            beforeSend: function() {
                $('#edit-submit-btn').attr('disabled', true);
                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait while we process your request.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response){
                if(response.status === 500){
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: response.message,
                        showConfirmButton: true,
                    })
                }else if(response.status === 400){
                        var errorMessages = Object.values(response.message).join('<br>');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMessages,
                            showConfirmButton: true,
                        }).then(function() {
                            $('#addItem-btn').attr('disabled', false);
                        });
                }else if(response.status === 200){
                    Swal.fire({
                    icon: "success",
                    title: "All set!",
                    html: response.message,
                    showConfirmButton: true,
                    }).then(function(){
                        window.location.reload();
                    });
                }
            },error: function(error){
                console.log(error);
            }
        });
    });
// });
// let currentEditCategoryIndex = -1;

// $(document).on('keyup', '.edit-search-category', function(e) {
//     const $input = $(this);
//     const query = $input.val();
//     const $results = $input.siblings('.edit-category-results');

//     if (['ArrowDown', 'ArrowUp', 'Enter'].includes(e.key)) return;

//     if (query.length > 0) {
//         $.ajax({
//             url: '/search-edit-categories',
//             method: 'GET',
//             data: { query },
//             success: function(data) {
//                 $results.empty();
//                 currentEditCategoryIndex = -1;

//                 if (data.length > 0) {
//                     data.forEach((category) => {
//                         $results.append(`
//                             <li class="list-group-item edit-category-item" data-id="${category.id}">
//                                 <strong>${category.name}</strong>
//                             </li>
//                         `);
//                     });
//                     $results.show();
//                 } else {
//                     $results.append('<li class="list-group-item">No categories found</li>').show();
//                 }
//             },
//             error: function(err) {
//                 console.error('Edit category search error:', err);
//             }
//         });
//     } else {
//         $results.hide();
//     }
// });

// $(document).on('keydown', '.edit-search-category', function(e) {
//     const $input = $(this);
//     const $results = $input.siblings('.edit-category-results');
//     const $items = $results.find('.edit-category-item');

//     if (!$results.is(':visible') || $items.length === 0) return;

//     if (e.key === 'ArrowDown') {
//         e.preventDefault();
//         currentEditCategoryIndex = (currentEditCategoryIndex + 1) % $items.length;
//     } else if (e.key === 'ArrowUp') {
//         e.preventDefault();
//         currentEditCategoryIndex = (currentEditCategoryIndex - 1 + $items.length) % $items.length;
//     } else if (e.key === 'Enter') {
//         e.preventDefault();
//         if (currentEditCategoryIndex >= 0) {
//             selectEditCategory($items.eq(currentEditCategoryIndex));
//         }
//         return;
//     }

//     $items.removeClass('highlighted');
//     if (currentEditCategoryIndex >= 0) {
//         $items.eq(currentEditCategoryIndex).addClass('highlighted');
//     }
// });

// $(document).on('mouseover', '.edit-category-item', function() {
//     $('.edit-category-item').removeClass('highlighted');
//     $(this).addClass('highlighted');
//     currentEditCategoryIndex = $(this).index();
// });

// $(document).on('mouseout', '.edit-category-item', function() {
//     $(this).removeClass('highlighted');
// });

// $(document).on('click', '.edit-category-item', function() {
//     selectEditCategory($(this));
// });

// function selectEditCategory($item) {
//     const categoryName = $item.text().trim();
//     const categoryId = $item.data('id');
//     const $row = $item.closest('.edit-item-row');

//     $row.find('.edit-search-category').val(categoryName);
//     $row.find('.edit-selected-category-id').val(categoryId);
//     $row.find('.edit-category-results').hide();

//     // Optional: Set values on main form
//     $('.edit-search-category').val(categoryName);
//     $('.edit-selected-category-id').val(categoryId);

//     const selectedCategoryIds = $('input[name="categoryId[]"]').map(function() {
//         return $(this).val();
//     }).get();

//     if (!selectedCategoryIds.includes(categoryId.toString())) {
//         $('<input>', {
//             type: 'text',
//             name: 'categoryId[]',
//             value: categoryId,
//             hidden: true
//         }).appendTo('#edit-item-form');
//     }

//     currentEditCategoryIndex = -1;
// }

// $(document).on('click', function(e) {
//     if (!$(e.target).closest('.edit-search-category, .edit-category-results').length) {
//         $('.edit-category-results').hide();
//     }
// });
// let currentEditUnitIndex = -1;

// $(document).on('keyup', '.edit-search-unit', function(e) {
//     const $input = $(this);
//     const query = $input.val();
//     const $results = $input.siblings('.edit-unit-results');

//     if (['ArrowDown', 'ArrowUp', 'Enter'].includes(e.key)) return;

//     if (query.length > 0) {
//         $.ajax({
//             url: '/edit-search-units',
//             method: 'GET',
//             data: { query },
//             success: function(data) {
//                 $results.empty();
//                 currentEditUnitIndex = -1;

//                 if (data.length > 0) {
//                     data.forEach((unit) => {
//                         $results.append(`
//                             <li class="list-group-item edit-unit-item" data-id="${unit.id}">
//                                 <strong>${unit.name}</strong>
//                             </li>
//                         `);
//                     });
//                     $results.show();
//                 } else {
//                     $results.append('<li class="list-group-item">No units found</li>').show();
//                 }
//             },
//             error: function(err) {
//                 console.error('Edit unit search error:', err);
//             }
//         });
//     } else {
//         $results.hide();
//     }
// });

// $(document).on('keydown', '.edit-search-unit', function(e) {
//     const $input = $(this);
//     const $results = $input.siblings('.edit-unit-results');
//     const $items = $results.find('.edit-unit-item');

//     if (!$results.is(':visible') || $items.length === 0) return;

//     if (e.key === 'ArrowDown') {
//         e.preventDefault();
//         currentEditUnitIndex = (currentEditUnitIndex + 1) % $items.length;
//     } else if (e.key === 'ArrowUp') {
//         e.preventDefault();
//         currentEditUnitIndex = (currentEditUnitIndex - 1 + $items.length) % $items.length;
//     } else if (e.key === 'Enter') {
//         e.preventDefault();
//         if (currentEditUnitIndex >= 0) {
//             selectEditUnit($items.eq(currentEditUnitIndex));
//         }
//         return;
//     }

//     $items.removeClass('highlighted');
//     if (currentEditUnitIndex >= 0) {
//         $items.eq(currentEditUnitIndex).addClass('highlighted');
//     }
// });

// $(document).on('mouseover', '.edit-unit-item', function() {
//     $('.edit-unit-item').removeClass('highlighted');
//     $(this).addClass('highlighted');
//     currentEditUnitIndex = $(this).index();
// });

// $(document).on('mouseout', '.edit-unit-item', function() {
//     $(this).removeClass('highlighted');
// });

// $(document).on('click', '.edit-unit-item', function() {
//     selectEditUnit($(this));
// });

// function selectEditUnit($item) {
//     const unitName = $item.text().trim();
//     const unitId = $item.data('id');
//     const $row = $item.closest('.edit-item-row');

//     $row.find('.edit-search-unit').val(unitName);
//     $row.find('.edit-selected-unit-id').val(unitId);
//     $row.find('.edit-unit-results').hide();

//     // Optional: Set values on main form
//     $('#edit-search-unit').val(unitName);
//     $('#edit-selected-unit-id').val(unitId);

//     const selectedUnitIds = $('input[name="unitId[]"]').map(function() {
//         return $(this).val();
//     }).get();

//     if (!selectedUnitIds.includes(unitId.toString())) {
//         $('<input>', {
//             type: 'text',
//             name: 'unitId[]',
//             value: unitId,
//             hidden: true
//         }).appendTo('#createItem-form');
//     }

//     currentEditUnitIndex = -1;
// }

// $(document).on('click', function(e) {
//     if (!$(e.target).closest('.edit-search-unit, .edit-unit-results').length) {
//         $('.edit-unit-results').hide();
//     }
});

