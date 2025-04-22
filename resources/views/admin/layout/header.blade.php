
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
                    <a class="nav-link d-flex align-items-center" href="{{ url('/admin/') }}" 
                    style="{{ (Request::is('admin/')) ? 'background-color: #3d5c99;' : '' }}">
                        <i class="fas fa-chart-line mr-1"></i>Dashboard
                    </a>

                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/admin/transaction') }}" 
                       style="{{ (Request::is('admin/transaction')) ? 'background-color: #3d5c99;' : '' }}">
                       <i class="fas fa-receipt mr-1"></i>Transactions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/admin/reports') }}" 
                       class="reports-link" 
                       style="{{ (Request::is('admin/reports')) ? 'background-color: #3d5c99;' : '' }}">
                       <i class="fas fa-chart-bar mr-1"></i>Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/admin/lookup-tables') }}?section=items" 
                       class="lookup-tables-link" 
                       style="{{ (Request::is('admin/lookup-tables')) ? 'background-color: #3d5c99;' : '' }}">
                       <i class="fas fa-database mr-1"></i>Lookup Tables
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/admin/audit') }}" 
                       style="{{ (Request::is('admin/audit')) ? 'background-color: #3d5c99;' : '' }}">
                       <i class="fas fa-history mr-1"></i>Activity Logs   
                    </a>
                </li>
            </ul>
            @php
                $position = ucwords(session('loggedInInventoryAdmin')['admin_position'] ?? '');
            @endphp
            <ul class="navbar-nav logout ml-3">
                <li class="nav-item mr-3 mt-1 text-light">
                    <strong>
                        <div>
                            <div>
                                <u>{{ session('loggedInInventoryAdmin')['admin_full_name'] }}</u>
                            </div>
                            <div style="text-align: center">
                                <span style="font-weight: normal;">Admin, {{ $position }}</span>
                            </div>                            
                        </div>
                       
                    </strong>
                </li>
                <li class="nav-item">
                    <button type="button" class="btn btn-danger" id="signOutButton" title="Sign out button">
                        <i class="fa-solid fas fa-power-off"></i>
                    </button>
                </li>
            </ul>
        </div>        
    </div>
</nav>
<div style="background: linear-gradient(to right, #dd9f03, #eabe03, #dd9f03); height: 10px; width: 100%;"></div>

