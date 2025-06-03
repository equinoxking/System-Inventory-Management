
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
           <a class="navbar-brand d-flex align-items-center" href="{{ url('/admin/') }}">
                <img src="{{ asset('assets/images/LOGO.webp') }}" alt="Logo" style="height: 40px; width: auto;">
                <span class="brand-text ms-2" style="white-space: nowrap; font-weight: 600;">
                    Office Supplies Inventory Management System
                </span>
            </a>

            <style>
                @media (max-width: 1200px) {
                    .brand-text {
                        font-size: 1.25rem; /* Smaller font on large screens */
                    }
                }
                @media (max-width: 992px) {
                    .brand-text {
                        font-size: 1rem; /* Even smaller on medium */
                    }
                }
                @media (max-width: 768px) {
                    .brand-text {
                        font-size: 0.875rem;
                    }
                }
                @media (max-width: 576px) {
                    .brand-text {
                        font-size: 0.75rem;
                    }
                }
            </style>


        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse justify-content-left" id="navbarNav">
            <ul class="navbar-nav d-flex justify-content-center align-items-center">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ Request::is('admin/') ? 'active' : '' }}"
                    href="{{ url('/admin/') }}"
                    style="{{ Request::is('admin/') ? 'background-color: #3d5c99;' : '' }}">
                        <i class="fas fa-chart-line me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/transaction') ? 'active' : '' }}"
                    href="{{ url('/admin/transaction') }}"
                    style="{{ Request::is('admin/transaction') ? 'background-color: #3d5c99;' : '' }}">
                        <i class="fas fa-receipt me-1"></i>Transactions
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ Request::is('admin/reports*') ? 'active' : '' }}"
                    href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-chart-bar me-1"></i>Reports
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="reportsDropdown">
                        <li><a class="dropdown-item text-dark" href="{{ url('/admin/reports/monthly-report') }}">Monthly Report (Summary)</a></li>
                        <li><a class="dropdown-item text-dark generateReportBtn">Inventory Report</a></li>
                        <li><a class="dropdown-item text-dark" href="{{ url('/admin/reports/quarterly-report') }}">Quarterly Report (Summary)</a></li>
                        <li><a class="dropdown-item text-dark pdfTransactionGenerationBtn">User Ledger Report</a></li>
                    </ul>
                </li>                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ Request::is('admin/lookup-tables*') ? 'active' : '' }}"
                    href="#" id="lookupTablesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-database me-1"></i>Lookup Tables
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="lookupTablesDropdown">
                        <li><a class="dropdown-item text-dark" href="{{ url('/admin/lookup-tables/categories') }}">Categories</a></li>
                        <li><a class="dropdown-item text-dark" href="{{ url('/admin/lookup-tables/deliveries') }}">Deliveries</a></li>
                        <li><a class="dropdown-item text-dark" href="{{ url('/admin/lookup-tables/items') }}">Items</a></li>
                        <li><a class="dropdown-item text-dark" href="{{ url('/admin/lookup-tables/units') }}">Units</a></li>
                        <li><a class="dropdown-item text-dark" href="{{ url('/admin/lookup-tables/user-accounts') }}">User Accounts</a></li>
                        <li><a class="dropdown-item text-dark" href="{{ url('/admin/lookup-tables/suppliers') }}">Suppliers</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ Request::is('admin/trails*') ? 'active' : '' }}"
                    href="#" id="activityLogsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-history me-1"></i>Activity Logs
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="activityLogsDropdown">
                        <li>
                            <a class="dropdown-item text-dark {{ Request::is('admin/trails/admin') ? 'active' : '' }}"
                            href="{{ url('/admin/trails/admin') }}">
                                Admin Activity Logs
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-dark {{ Request::is('admin/trails/user') ? 'active' : '' }}"
                            href="{{ url('/admin/trails/user') }}">
                                User Activity Logs
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>



            @php
                $position = ucwords(session('loggedInInventoryAdmin')['admin_position'] ?? '');
            @endphp

            <ul class="navbar-nav logout ml-3 d-flex flex-row align-items-center" style="white-space: nowrap;">
                <li class="nav-item mr-3 mt-1 text-light" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                    <div class="d-flex flex-column align-items-end" style="font-weight: 700; min-width: 0; max-width: 300px;">
                        <div style="word-break: break-word;">
                            <u>{{ session('loggedInInventoryAdmin')['admin_full_name'] }}</u>
                        </div>
                        <div style="text-align: right;">
                            Admin, {{ $position }}
                        </div>
                    </div>

                </li>
                <li class="nav-item">
                    <button type="button" class="btn btn-danger ml-2" id="signOutButton" title="Sign out button">
                        <i class="fa-solid fas fa-power-off"></i>
                    </button>
                </li>
            </ul>

        </div>        
    </div>
</nav>
<div style="background: linear-gradient(to right, #dd9f03, #eabe03, #dd9f03); height: 10px; width: 100%;"></div>

