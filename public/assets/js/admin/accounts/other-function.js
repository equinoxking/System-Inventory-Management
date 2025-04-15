$(document).ready(function() {
    var currentRole = "{{ $client->role->name }}";  
    var roleDropdown = $('#role-id');
    roleDropdown.find('option').each(function() {
        var option = $(this);
        var roleName = option.text().trim();

        if (currentRole === 'HeadAdmin') {
            if (roleName === 'HeadAdmin') {
                option.prop('hidden', true);
            } else {
                option.prop('hidden', false);
            }
        } else if (currentRole === 'InventoryAdmin') {
            if (roleName === 'InventoryAdmin') {
                option.prop('hidden', true);
            } else {
                option.prop('hidden', false);
            }
        } else if (currentRole === 'CheckerAdmin') {
            if (roleName === 'CheckerAdmin') {
                option.prop('hidden', true);
            } else {
                option.prop('hidden', false);
            }
        } else if (currentRole === 'User') {
            if (roleName === 'User') {
                option.prop('hidden', true);
            } else {
                option.prop('hidden', false);
            }
        } else {
            option.prop('hidden', false);
        }
    });
});