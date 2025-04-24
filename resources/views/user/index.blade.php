@extends('user.layout.layout')
@section('content')
<div class="container-fluid mt-4">  
    <div class="row align-items-stretch">  
        <!-- Notifications Section -->  
        <div class="col-12 mb-2">  
            <div class="card p-3">  
                <h4 class="card-title mb-3">Notifications</h4>  
                <div class="table-responsive">  
                    <table class="table table-bordered table-hover" style="font-size: 14px; width: 100%;" id="notificationTable">  
                        <thead class="thead-light">  
                            <tr>  
                                <th style="width: 18%; text-align:left">Date/Time</th>
                                <th style="width: 12%;">Control #</th> 
                                <th style="width: 70%;">Message</th>  
                            </tr>  
                        </thead>  
                        <tbody>  
                            @foreach ($notifications as $notification)
                                <tr>
                                    <td class="text-left">{{ \Carbon\Carbon::parse($notification->created_at)->format('F d, Y h:i A') }}</td>
                                    <td>{{ $notification->control_number }}</td>
                                    <td class="text-left">
                                        {!! highlightStatusWords($notification->message) !!}
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>  
                    </table>  
                </div>  
            </div>  
        </div>
    </div>
</div>
@php
function highlightStatusWords($message)
{
    $statuses = [
        'Pending'   => [
            'style' => 'background-color: #ffc107; color: #000;',   
            'icon'  => '<i class="fas fa-clock"></i>'               
        ],
        'Issued'    => [
            'style' => 'background-color: #28A745; color: #fff;',   
            'icon'  => '<i class="fas fa-check-circle"></i>'        
        ],
        'Completed' => [
            'style' => 'background-color: #009688; color: #fff;',   
            'icon'  => '<i class="fas fa-check"></i>'                
        ],
        'Canceled'  => [
            'style' => 'background-color: #6c757d; color: #fff;',   
            'icon'  => '<i class="fas fa-times-circle"></i>'         
        ],
        'Denied'  => [
            'style' => 'background-color: #dc3545; color: #fff;',  
            'icon'  => '<i class="fas fa-ban"></i>'                 
        ],
        'For Review'  => [
            'style' => 'background-color: #2196F3; color: #fff;',  
            'icon'  => '<i class="fas fa-magnifying-glass"></i>'                
        ],
        'Received'  => [
            'style' => 'background-color: #28A745; color: #fff;',  
            'icon'  => '<i class="fas fa-box-open"></i>'                
        ],
        'Accepted'  => [
            'style' => 'background-color: #28A745; color: #fff;',  
            'icon'  => '<i class="fas fa-check-circle"></i>'                
        ],
        'Released'  => [
            'style' => 'background-color: #17A2B8; color: #fff;',  
            'icon'  => '<i class="fas fa-truck"></i>'                
        ]
    ];

    foreach ($statuses as $word => $data) {
        $message = preg_replace_callback(
            "/\b($word)\b/i",  // Regex to find the exact word
            function ($matches) use ($data) {
                // Wrap the matched word with the corresponding style and icon
                return "<span style=\"{$data['style']}\" class=\"px-1 rounded\">{$data['icon']} {$matches[0]}</span>";
            },
            $message
        );
    }

    return $message;
}



@endphp
@endsection