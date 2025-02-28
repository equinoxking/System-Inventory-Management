<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            display: flex;
        }
        .sidebar {
            width: 250px;
            background: #3a5a9a;
            color: white;
            height: 100vh;
            padding: 15px;
            position: fixed;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
        }
        .sidebar a:hover {
            background: #2d4373;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
            width: 100%;
        }
        .navbar {
            background: #3a5a9a;
            color: white;
        }
        .navbar a {
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4>Admin Panel</h4>
        <a href="#">Dashboard</a>
        <a href="#">Users</a>
        <a href="#">Reports</a>
        <a href="#">Settings</a>
        <a href="#">Log Out</a>
    </div>
    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Admin Dashboard</a>
            </div>
        </nav>
        <div class="container mt-4">
            <h2>Welcome to the Admin Dashboard</h2>
            <p>Manage users, view reports, and configure settings here.</p>
        </div>
    </div>
</body>
</html>
