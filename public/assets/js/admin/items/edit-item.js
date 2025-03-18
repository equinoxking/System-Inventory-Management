function editItem(item){
    $('#editItemModal').modal('show');
    var data = JSON.parse(item); 
    $('#edit-item-id').val(data.id);
    $('#edit-item-name').val(data.name);
}
$('#edit-item-close-btn').click(function(){
    $('#editItemModal').modal('hide');
});
/* Search for categories */
$(document).ready(function() {
    $(document).on('keyup', '.edit-search-category', function() {
        var query = $(this).val();
        var $categoryResults = $(this).siblings('.edit-category-results');
        if (query.length > 0) {
            $.ajax({
                url: '/search-edit-categories',
                method: 'GET',
                data: { query: query },
                success: function(data) {
                    $categoryResults.empty();
                    if (data.length > 0) {
                        data.forEach(function(category) {
                            $categoryResults.append(`
                                <li class="list-group-item edit-category-item" data-id="${category.id}">
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

    $(document).on('click', '.edit-category-item', function() {
        var categoryName = $(this).text().trim();
        var categoryId = $(this).data('id');
        $(this).closest('.edit-item-row').find('.edit-search-category').val(categoryName);
        $(this).closest('.edit-item-row').find('.edit-selected-category-id').val(categoryId);
        $(this).closest('.edit-item-row').find('.edit-category-results').hide();
        $('.edit-search-category').val(categoryName);
        $('.edit-selected-category-id').val(categoryId); 

 
        var selectedCategoryIds = $('input[name="edit-categoryId[]"]').map(function() {
            return $(this).val();
        }).get(); 
        if (!selectedCategoryIds.includes(categoryId.toString())) {
            $('<input>', {
                type: 'text',
                name: 'edit-categoryId[]',
                value: categoryId,
                hidden: true
            }).appendTo('#edit-item-form'); 
        }
    });
    $(document).on('keyup', '.edit-search-unit', function() {
        var query = $(this).val();
        var $unitResults = $(this).siblings('.edit-unit-results');
        if (query.length > 0) {
            $.ajax({
                url: '/edit-search-units',
                method: 'GET',
                data: { query: query },
                success: function(data) {
                    $unitResults.empty();
                    if (data.length > 0) {
                        data.forEach(function(unit) {
                            $unitResults.append(`
                                <li class="list-group-item edit-unit-item" data-id="${unit.id}">
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
    
    $(document).on('click', '.edit-unit-item', function() {
        var unitName = $(this).text().trim();
        var unitId = $(this).data('id');
        $(this).closest('.edit-item-row').find('.edit-search-unit').val(unitName);
        $(this).closest('.edit-item-row').find('.edit-selected-unit-id').val(unitId);
        $(this).closest('.edit-item-row').find('.edit-unit-results').hide();
        $('#edit-search-unit').val(unitName); // Set category name in input
        $('#edit-selected-unit-id').val(unitId); // Set category ID in hidden input
    
        // Append this selected category ID to the list of selected categories
        var selectedUnitIds = $('input[name="edit-unitId[]"]').map(function() {
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
                            title: 'Adding an item validation failed!',
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
});
