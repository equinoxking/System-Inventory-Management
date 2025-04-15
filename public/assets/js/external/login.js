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
                }else if(response.status === 401){
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
                    Swal.fire({
                        icon: "success",
                        title: "All set!",
                        html: response.message + ' ' + response.username,
                        showConfirmButton: true,
                      }).then(function () {
                        const roleUrls = {
                          1: "/admin/",
                          2: "/checker_admin/",
                          3: "/head_admin/",
                          4: "/user/"
                        };
                      
                        const redirectUrl = roleUrls[response.roleId];
                      
                        if (response.roleId === 1) {
                          // Only show admin selection modal if roleId is 1 (admin)
                          fetch('/get-available-admins')
                            .then(res => res.json())
                            .then(admins => {
                                let adminOptions = admins.map(admin => 
                                `<option value="${admin.id}">${admin.full_name}</option>`
                                ).join('');

                                Swal.fire({
                                title: 'Select Admin',
                                html: `
                                    <label for="adminSelect">Choose Admin:</label>
                                    <select id="adminSelect" class="swal2-input">
                                    ${adminOptions}
                                    </select>
                                `,
                                confirmButtonText: 'Continue',
                                focusConfirm: false,
                                preConfirm: () => {
                                    const selectedAdminId = document.getElementById('adminSelect').value;
                                    if (!selectedAdminId) {
                                    Swal.showValidationMessage('Please select an admin');
                                    }
                                    return fetch('/set-selected-admin', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({ admin_id: selectedAdminId })
                                    });
                                }
                                }).then(() => {
                                // Redirect or whatever comes next
                                window.location = '/admin/';
                                });
                            })
                            .catch(error => {
                                console.error('Error fetching admins:', error);
                                Swal.fire('Oops!', 'Failed to load admin list.', 'error');
                            });
                        } else {
                          // Just redirect for other roles
                          if (redirectUrl) {
                            window.location = redirectUrl;
                          } else {
                            console.error("Unknown roleId:", response.roleId);
                          }
                        }
                      }); 
                }
            },error: function(error){
                console.log(error);
            }
        });
    });
});