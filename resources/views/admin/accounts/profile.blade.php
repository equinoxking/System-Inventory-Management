@extends('admin.layout.admin-layout')

@section('content')
<div class="container-fluid mt-3 mb-3">
    <div class="row">
        <div class="col-md-12">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb"> 
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profile</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
    <div style="display: flex; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); width: 60%;">
        <!-- Left Section (Profile) -->
        <div style="width: 30%; background: linear-gradient(135deg, #2f4a7f, #3b5fa7d5); padding: 20px; text-align: center; color: white; display: flex; flex-direction: column; justify-content: center; align-items: center;">
            <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Profile Picture" style="width: 150px; border-radius: 50%; margin-bottom: 10px;">
            <h4 style="margin: 5px 0;">John Doe</h4>
            <p style="margin: 0;">Provincial Human Resource Management Office</p>
        </div>

        <!-- Right Section (User Info) -->
        <div style="width: 70%; padding: 20px;">
            <h5>Personal Information</h5>
            <hr>
            <form id="update-account-form">
                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <div style="width: 48%;">
                        <label for="full_name"><strong>Full Name:</strong></label>
                        <input type="text" name="full_name" id="first_name" value="{{ $client->full_name }}"  style="width: 100%; padding: 5px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    </div>
                    <div style="width: 48%;">
                        <label for="username"><strong>Username:</strong></label>
                        <input type="text" name="username" id="username" value="{{ $client->username }}" readonly style="width: 100%; padding: 5px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px; background: #f5f5f5;">
                    </div>
                    <div style="width: 48%;">
                        <label for="email"><strong>Email:</strong></label>
                        <input type="email" name="email" id="email" value="{{ $client->email }}" style="width: 100%; padding: 5px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    </div>
                    <div style="width: 48%;">
                        <label for="status"><strong>Status:</strong></label>
                        <input type="text" name="status" id="status" value="{{ $client->status }}" readonly style="width: 100%; padding: 5px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px; background: #f5f5f5;">
                    </div>
                    <div style="width: 48%;">
                        <label for="position"><strong>Position:</strong></label>
                        <input type="text" id="position" name="position" value="{{ $client->position }}"  style="width: 100%; padding: 5px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    </div>
                    <div style="width: 48%;">
                        <label for="division"><strong>Division:</strong></label>
                        <input type="text" id="division" value="Provincial Human Resource Management Office" readonly style="width: 100%; padding: 5px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px; background: #f5f5f5;">
                    </div>
                    <div style="width: 48%; position: relative;" >
                        <label for="role"><strong>Old Password:</strong></label>
                        <input type="password" id="old_password" name="old_password" style="width: 100%; padding: 5px; border: 1px solid #ccc; border-radius: 5px;" placeholder="Enter old password">
                        <span onclick="toggleOldPassword()" style="position: absolute; right: 10px; top: 35px; cursor: pointer;">👁️</span>
                    </div>
                    <div style="width: 48%;">
                        <label for="role"><strong>Role:</strong></label>
                        <input type="text" id="role" value="{{ $client->role->name }}" readonly style="width: 100%; padding: 5px; border: 1px solid #ccc; border-radius: 5px; background: #f5f5f5;">
                    </div>  
                    <div style="width: 48%; position: relative;">
                        <label for="password"><strong>Password:</strong></label>
                        <input type="password" id="new_password" name="new_password" placeholder="Enter new password" style="width: 100%; padding: 5px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px;">
                        <span onclick="toggleNewPassword()" style="position: absolute; right: 10px; top: 35px; cursor: pointer;">👁️</span>
                    </div>
                    <div style="width: 48%; position: relative;">
                        <label for="password"><strong>Re-type Password:</strong></label>
                        <input type="password" id="re_password" name="re_password" placeholder="Enter new password" style="width: 100%; padding: 5px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px;">
                        <span onclick="toggleRePassword()" style="position: absolute; right: 10px; top: 35px; cursor: pointer;">👁️</span>
                    </div>
                </div>
                <div style="margin-top: 20px; text-align: right;">
                    <button type="submit" id="update-account-btn" style="padding: 10px 15px; background: #2f4a7f; color: white; border: none; border-radius: 5px; cursor: pointer;">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleOldPassword() {
        var passwordField = document.getElementById("old_password");
        if (passwordField.type === "password") {
            passwordField.type = "text";
        } else {
            passwordField.type = "password";
        }
    }
    function toggleNewPassword() {
        var passwordField = document.getElementById("new_password");
        if (passwordField.type === "password") {
            passwordField.type = "text";
        } else {
            passwordField.type = "password";
        }
    }
    function toggleRePassword() {
        var passwordField = document.getElementById("re_password");
        if (passwordField.type === "password") {
            passwordField.type = "text";
        } else {
            passwordField.type = "password";
        }
    }
</script>

@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/js/admin/accounts/update-account.js') }}"></script>