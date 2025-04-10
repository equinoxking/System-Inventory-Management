
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href=" {{ url('/admin/') }}">
            <img src="{{ asset('assets/images/LOGO.webp') }}" alt="Logo"> Office Supplies Inventory Management System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/admin/') }}" 
                       style="{{ (Request::is('admin/')) ? 'background-color: #3d5c99;' : '' }}">
                       Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/admin/transaction') }}" 
                       style="{{ (Request::is('admin/transaction')) ? 'background-color: #3d5c99;' : '' }}">
                       Transactions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/admin/lookup-tables') }}" 
                       style="{{ (Request::is('admin/lookup-tables')) ? 'background-color: #3d5c99;' : '' }}">
                       Lookup Tables
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/admin/audit') }}" 
                       style="{{ (Request::is('admin/audit')) ? 'background-color: #3d5c99;' : '' }}">
                       Logs
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav logout ml-3">
                <li class="nav-item mr-3 mt-1 text-light">
                    <strong>Hi, {{ session('loggedInInventoryAdmin')['full_name'] }}</strong>
                </li>
                <li class="nav-item">
                    <button type="button" class="btn btn-danger" style="background-color: #FFB200 ; border-color: #FFB200;" id="signOutButton">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </li>
            </ul>
        </div>        
    </div>
</nav>
<div style="background: linear-gradient(to right, #dd9f03, #eabe03, #dd9f03); height: 10px; width: 100%;"></div>

