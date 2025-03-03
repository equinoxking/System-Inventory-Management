<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Admin | Report |</title>
    <link rel="icon" href="{{ asset('assets/images/LOGO.webp') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background: #3a5a9a;
        }
        .navbar a {
            color: white;
            text-decoration: none;
        }
        .navbar a:hover {
            background: #2d4373;
        }
        .content {
            padding: 20px;
        }
        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }
        .navbar-nav {
            margin: auto;
        }
        .logout {
            margin-left: auto;
            position: absolute;
            right: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('assets/images/LOGO.webp') }}" alt="Logo"> Supplies Management System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/admin/dashboard') }}" >Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/admin/items') }}">Items</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/admin/transaction') }}">transaction</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/admin/request') }}">Request</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/admin/report') }}" style="background-color: #2d4373;">Report</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/admin/analytic') }}">Analytics</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/admin/account') }}">Accounts</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/admin/audit') }}">Audit</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/admin/profile') }}">Profile</a></li>
                </ul>
                <ul class="navbar-nav logout">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>



</body>
</html>
