<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SMS</title>
    <link rel="icon" href="{{ asset('assets/images/LOGO.webp') }}" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 400px;
        }
        .seal {
            width: 225px;
        }
        .pgis-title-container {
            background-color: green;
            color: white;
            padding: 10px;
            font-weight: bold;
            display: inline-block;
            width: 100%;
        }
        .pgis-title {
            margin: 0;
        }
        .pgis-subtitle {
            color: gold;
            font-weight: bold;
        }
        .login-btn {
            background-color: green;
            color: white;
            border-radius: 0;
            transition: background-color 0.3s;
        }
        .login-btn:hover {
            background-color: darkgreen;
            color: white;
        }
        .btn-light {
            border-radius: 0;
        }
        #currentDateTime {
            margin-bottom: 10px;
            font-weight: bold;
        }
        .card {
            border-radius: 0;
        }
        .form-check-input:checked + .form-check-label {
            color: blue;
        }
        .register-btn {
            background-color: rgb(200, 200, 11);
            color: black;
            border-radius: 0;
            transition: background-color 0.3s;
        }
        .register-btn:hover {
            background-color: skyblue;
            color: black;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="{{ asset('assets/images/LOGO.webp') }}" alt="Seal" class="seal mb-2">
        <div class="pgis-title-container text-center p-2 mb-2">
            <h1 class="pgis-title"><b>.PGIS.</b></h1>
            <p class="pgis-subtitle">Provincial Government Information System</p>
        </div>

        <p class="text-muted">Login to Access SMS v.1.1.1.1</p>

        <div class="card bg-light p-3">
            <form id="login-form">
                @csrf
                <p id="currentDateTime" class="text-muted"></p>
                <div class="mb-3">
                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                </div>
                <div class="mb-3 input-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">👁️</button>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                        <label class="form-check-label" for="rememberMe">Remember Me?</label>
                    </div>
                    <button type="button" class="btn btn-outline-secondary">Advance Server Setup</button>
                </div>
                <button type="submit" class="btn login-btn w-100">Login</button>
                @php
                    $user = $users;
                @endphp
                @if ($user <= 0)
                    <button type="button" class="btn register-btn w-100 mt-1" data-toggle="modal" data-target="#registerModal">Register</button>
                @endif
            </form>
        </div>

    </div>

    <script>
        function updateDateTime() {
            const now = new Date();
            const formattedDateTime = now.toLocaleString();
            document.getElementById('currentDateTime').innerText = formattedDateTime;
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();

        document.getElementById('togglePassword').addEventListener('click', function () {
            let passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }); 
    </script>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/main_js/modal.js') }}"></script>
<script src="{{ asset('assets/js/external/registration.js') }}"></script>
<script src="{{ asset('assets/js/external/login.js') }}"></script>
</html>
{{-- Modals --}}

<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #3a5a9a">
                <h5 class="modal-title" id="registerLabel" >Register Form</h5>
            </div>
            <div class="modal-body">
                <form id="registration-form">
                    @csrf
                    <div class="form-group">
                        <label for="firstName">Full Name</label>
                        <input type="text" class="form-control" id="fullNameRegister" name="fullName" placeholder="Enter first name">
                    </div>
                    <div class="form-group">
                        <label for="office">Office</label>
                        <select name="office" id="officeRegister" class="form-control">
                            <option value="phrmo">PHRMO</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="position">Position</label>
                        <input type="text" class="form-control" id="positionRegister" name="position" placeholder="Enter here your position">
                    </div>
                    <div class="form-group">
                        <label for="emailAddress">Email Address</label>
                        <input type="email" class="form-control" id="emailAddressRegister" name="email" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="usernameRegister" name="username" placeholder="Enter username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="passwordRegister" name="password" placeholder="Enter passowrd">
                    </div>
                    <div class="form-group">
                        <label for="re-password">Re-type Password</label>
                        <input type="password" class="form-control" id="re-passwordRegister" name="re-password" placeholder="Enter re-type passowrd">
                    </div>
            </div>
                <div class="modal-footer" style="background-color: #3a5a9a">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="register-btn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="interfaceModal" tabindex="-1" role="dialog"
     data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #3a5a9a">
                <h5 class="modal-title" id="registerLabel">Interface Form</h5>
            </div>
            <div class="modal-body">
                <strong>Select your interface</strong><br>
                <button id="goToAdminInterface" class="btn btn-info" type="button">Admin Interface</button>
                <button id="goToUserInterface" class="btn btn-success" type="button">User Interface</button>
            </div>
        </div>
    </div>
</div>

