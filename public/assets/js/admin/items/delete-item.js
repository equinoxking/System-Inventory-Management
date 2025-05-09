function deleteItem(item){
    var data = JSON.parse(item);
    $('#delete-item-id').val(data.id);
    $('#deleteItemModal').modal('show');
}
$('#delete-item-close-btn').click(function(){
    $('#deleteItemModal').modal('hide');
});
$(document).ready(function(){
    $(document).on('submit', '#delete-item-form', function(event){
      event.preventDefault();
      var formData = $('#delete-item-form').serialize();
      console.log(formData);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/delete-item',
            type: 'POST',
            data: formData,
            beforeSend: function() {
                $('#delete-submit-btn').attr('disabled', true);
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
                            $('#delete-submit-btn').attr('disabled', false);
                        });
                }else if(response.status === 200){
                    Swal.fire({
                    icon: "success",
                    title: "All set!",
                    html: response.message,
                    showConfirmButton: true,
                    }).then(function(){
                        $('#delete-submit-btn').attr('disabled', false);
                        swal.close();
                        $('#deleteItemModal').modal('hide');
                        window.location.reload();
                    });
                }
            },error: function(error){
                console.log(error);
            }
        });
    });
});