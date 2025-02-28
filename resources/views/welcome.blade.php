<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
            color: rgb(4, 80, 4);
            border-radius: 0;
        }
        .btn-light {
            border-radius: 0;
        }
        #currentDateTime {
            margin-bottom: 10px;
            font-weight: bold;
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

        <div class="card p-3">
            <form method="POST" action="">
                <p id="currentDateTime" class="text-muted"></p>
                <div class="mb-3">
                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                </div>
                <div class="mb-3 input-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">👁️</button>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                    <label class="form-check-label" for="rememberMe">Remember Me?</label>
                </div>
                <button type="submit" class="btn login-btn w-100">Login</button>
                <button type="button" class="btn btn-light w-100 mt-2">Advance Server Setup</button>
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
</html>
