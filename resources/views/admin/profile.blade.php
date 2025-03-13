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
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                <div style="width: 48%;">
                    <label for="first_name"><strong>First Name:</strong></label>
                    <input type="text" id="first_name" value="John" disabled style="width: 100%; padding: 5px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px; background: #f5f5f5;">
                </div>
                <div style="width: 48%;">
                    <label for="last_name"><strong>Last Name:</strong></label>
                    <input type="text" id="last_name" value="Doe" disabled style="width: 100%; padding: 5px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px; background: #f5f5f5;">
                </div>
                <div style="width: 48%;">
                    <label for="username"><strong>Username:</strong></label>
                    <input type="text" id="username" value="johndoe" disabled style="width: 100%; padding: 5px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px; background: #f5f5f5;">
                </div>
                <div style="width: 48%;">
                    <label for="status"><strong>Status:</strong></label>
                    <input type="text" id="status" value="Active" disabled style="width: 100%; padding: 5px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px; background: #f5f5f5;">
                </div>
                <div style="width: 48%;">
                    <label for="email"><strong>Email:</strong></label>
                    <input type="email" id="email" value="johndoe@example.com" disabled style="width: 100%; padding: 5px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px; background: #f5f5f5;">
                </div>
                <div style="width: 48%;">
                    <label for="division"><strong>Division:</strong></label>
                    <input type="text" id="division" value="Provincial Human Resource Management Office" disabled style="width: 100%; padding: 5px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px; background: #f5f5f5;">
                </div>
                <div style="width: 48%;">
                    <label for="role"><strong>Role:</strong></label>
                    <input type="text" id="role" value="Administrator" disabled style="width: 100%; padding: 5px; border: 1px solid #ccc; border-radius: 5px; background: #f5f5f5;">
                </div>
                <div style="width: 48%; position: relative;">
                    <label for="password"><strong>Password:</strong></label>
                    <input type="password" id="password" placeholder="Enter new password" style="width: 100%; padding: 5px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    <span onclick="togglePassword()" style="position: absolute; right: 10px; top: 35px; cursor: pointer;">👁️</span>
                </div>
            </div>
            <div style="margin-top: 20px; text-align: right;">
                <button style="padding: 10px 15px; background: #2f4a7f; color: white; border: none; border-radius: 5px; cursor: pointer;">Update Password</button>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        var passwordField = document.getElementById("password");
        if (passwordField.type === "password") {
            passwordField.type = "text";
        } else {
            passwordField.type = "password";
        }
    }
</script>

@endsection
