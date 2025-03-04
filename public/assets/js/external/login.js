$(document).ready(function(){
    $(document).on('submit', '#login-form', function(event){
        event.preventDefault();
        var formData = $('#login-form').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/login-user',
            type: 'POST',
            data: formData,
            success: function(response){
                if(response.status === 404){
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        html: response.message,
                        showConfirmButton: true,
                    });
                }else if(response.status === 423){
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        html: response.message,
                        showConfirmButton: true,
                    });
                }else if(response.status === 400){
                     console.log(response.message);
                        var errorMessages = Object.values(response.message).join('<br>');
                        Swal.fire({
                            icon: 'error',
                            title: 'Login validation failed!',
                            html: errorMessages,
                            showConfirmButton: true,
                        }).then(function() {
                            $('#login-btn').attr('disabled', false);
                        });
                }else if(response.status === 500){
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        html: response.message,
                        showConfirmButton: true,
                    });
                }else if(response.status === 200){
                    console.log(response.roleId);
                    Swal.fire({
                    icon: "success",
                    title: "All set!",
                    html: response.message,
                    showConfirmButton: true,
                    }).then(function(){
                        const roleUrls = {
                            1: "/admin/",
                            2: "/checker_admin/",
                            3: "/head_admin/",
                            4: "/user/"
                        };
                        const redirectUrl = roleUrls[response.roleId];
                        console.log(redirectUrl);
                        if (redirectUrl) {
                            window.location = redirectUrl;
                        } else {
                            console.error("Unknown roleId:", response.roleId);
                        }
                    });
                   
                }
            },error: function(error){
                console.log(error);
            }
        });
    });
});