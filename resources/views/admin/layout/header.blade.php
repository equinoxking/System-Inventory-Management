
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
                       style="{{ (Request::is('admin/')) ? 'background-color: #2d4373;' : '' }}">
                       Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/admin/items') }}" 
                       style="{{ (Request::is('admin/items')) ? 'background-color: #2d4373;' : '' }}">
                       Items
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/admin/transaction') }}" 
                       style="{{ (Request::is('admin/transaction')) ? 'background-color: #2d4373;' : '' }}">
                       Transactions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/admin/request') }}" 
                       style="{{ (Request::is('admin/request')) ? 'background-color: #2d4373;' : '' }}">
                       Requests
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/admin/report') }}" 
                       style="{{ (Request::is('admin/report')) ? 'background-color: #2d4373;' : '' }}">
                       Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/admin/account') }}" 
                       style="{{ (Request::is('admin/account')) ? 'background-color: #2d4373;' : '' }}">
                       Accounts
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/admin/audit') }}" 
                       style="{{ (Request::is('admin/audit')) ? 'background-color: #2d4373;' : '' }}">
                       Audits
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/admin/profile') }}" 
                       style="{{ (Request::is('admin/profile')) ? 'background-color: #2d4373;' : '' }}">
                       Profile
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav logout">
                <li class="nav-item"><button type="button" class="btn btn-danger" id="signOutButton"><i class="fa-solid fa-right-from-bracket"></i></button></li>
            </ul>
        </div>
    </div>
</nav>
