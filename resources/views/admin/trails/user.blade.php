@extends('admin.layout.admin-layout')
@section('content')
<div class="container-fluid mt-3 mb-3">
    <div class="row align-items-center">
        <div class="col-md-12">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb"> 
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Activity Logs</li>
                </ol>
            </nav>
        </div>
        <div class="row mt-2">
            <div class="col-md-12" style="text-align: left">
                <h4><strong >USER ACTIVITY LOGS</strong></h4>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid card w-100 p-3">
    <div class="row">
        <div class="col-md-12">
            <table id="auditTable">
                <thead>
                    <th width="5%">ID</th>
                    <th width="13%">Date/Time Created</th>
                    <th width="25%">Acted</th>
                    <th>Activity</th>
                </thead>
                <tbody>
                    @foreach ($trails as $trail)
                        @if ($trail->client && optional($trail->client->role)->name === 'User')
                            <tr>
                                <td>{{ $trail->id }}</td>
                                <td>{{ $trail->created_at->format('F d, Y h:i A') }}</td>
                                <td>{{ $trail->client->full_name }}</td>
                                <td>{{ $trail->activity }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
