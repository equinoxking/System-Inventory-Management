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
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/user/') }}" style="{{ (Request::is('user/')) ? 'background-color: #3d5c99;' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/user/transactions') }}" 
                       style="{{ Request::is('user/transactions*') ? 'background-color: #3d5c99;' : '' }}">
                       <i class="fas fa-credit-card"></i> Transactions
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/user/history') }}"><i class="fas fa-history"></i> History</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/user/profile') }}"><i class="fas fa-id-card"></i> Profile</a></li>
            </ul>
            <ul class="navbar-nav logout">
                <button class="btn btn-danger" id="signOutButton" title="Logout button"><i class="fa fa-power-off"></i></button>
            </ul>
        </div>
    </div>
</nav>
