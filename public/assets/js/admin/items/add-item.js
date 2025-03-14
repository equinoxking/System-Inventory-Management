$(document).ready(function() {
    $("#addItemBtn").click(function() {
      $("#itemForm").css({
        "display": "flex",        
      });
    });
    $('#createItem-closeBtn').click(function(){
      $("#itemForm").css({
        "display": "none",        
      });
    })
});
$(document).ready(function(){
  $(document).on('submit', '#createItem-form', function(event){
      event.preventDefault();
      var formData = $('#createItem-form').serialize();
      $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: '/add-item',
          type: 'POST',
          data: formData,
          beforeSend: function() {
              $('#addItem-btn').attr('disabled', true);
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