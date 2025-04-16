@extends('admin.layout.admin-layout')
@section('content')
<div class="container-fluid mt-3 mb-3">
    <div class="row align-items-center">
        <div class="col-md-2">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Lookup Tables</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-1 text-right">
            <label for="container">Tables</label>
        </div>
        <div class="col-md-2">
            <select name="container" id="container" class="form-control">
                <option value="items" {{ $activeSection == 'items' ? 'selected' : '' }}>Items</option>
                <option value="deliveries" {{ $activeSection == 'deliveries' ? 'selected' : '' }}>Deliveries</option>
                <option value="reports" {{ $activeSection == 'reports' ? 'selected' : '' }}>Reports</option>
                <option value="accounts" {{ $activeSection == 'accounts' ? 'selected' : '' }}>Accounts</option>
                <option value="categories" {{ $activeSection == 'categories' ? 'selected' : '' }}>Categories</option>
                <option value="units" {{ $activeSection == 'units' ? 'selected' : '' }}>Units</option>
                <option value="admins" {{ $activeSection == 'admins' ? 'selected' : '' }}>Admins</option>
            </select>
        </div>
        <div class="col-md-7 text-end">
            
        </div>
    </div>
</div>
<div id="items-container" class="table-container" style="display: block;">
    @include('admin.items.items')
</div>
<div class="table-container" id="deliveries-container">
    @include('admin.items.deliveries')
</div>
<div class="table-container" id="reports-container">
    @include('admin.items.reports')
</div>

<div class="table-container" id="accounts-container">
    @include('admin.items.accounts')
</div>
<div class="table-container" id="categories-container">
    @include('admin.items.categories')
</div>
<div class="table-container" id="units-container">
    @include('admin.items.units')
</div>
<div class="table-container" id="admins-container">
    @include('admin.items.admins')
</div>
<script src="{{ asset('assets/js/admin/items/other-functions.js') }}"></script>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
