function signOut() {
    Swal.fire({
        title: 'Logging Out...',
        text: 'Are you sure you want to Signout?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#1abc02',
        cancelButtonColor: 'red',
        confirmButtonText: 'Yes, Signout!',
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                icon: "success",
                title: "Successfully logged out!",
                text: "You will now be redirected to landing page",
                showConfirmButton: false,
                timer: 3000,
            })
            setTimeout(function () {
                window.location = "/logoutUser";
            }, 2000);
        }
    });
}
document.getElementById('signOutButton').addEventListener('click', signOut);