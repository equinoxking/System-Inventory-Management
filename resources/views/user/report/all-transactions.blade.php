<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Transaction Generation</title>
    <style>
        /* Ensure html and body take full height */
        html, body {
            height: 100%;
            margin: 0;
        }

        .rightText {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 0.5px solid #000;
            padding: 8px;
        }

        .content {
            margin-bottom: 70px; /* Space to ensure footer doesn't overlap */
        }

        table.signatories-table th, table.signatories-table td {
            border: none;
        }

        table.date-generated th, table.date-generated td {
            border: none;
        }

        body {
            font-size: 12px;
        }

        .header {
            text-align: center;
            padding-bottom: 10px;
            font-size: 14px;
            position: relative;
            top: 0px;
            left: 0;
            right: 0;
        }

        /* Footer style */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            padding: 10px;
            background-color: #fff;
            border-top: 1px solid #ccc;
        }

        .footer .left {
            text-align: left;
        }

        .footer .right {
            text-align: right;
        }

        /* Page number style */
        .pagenum:before {
            content: counter(page);
        }

        main {
            margin-bottom: 70px;/* Ensure space for footer */
            margin-right: 20px;
            margin-left: 20;
        }
    </style>
</head>
<body>
    <div class="header first-page-header">
        <table style="border: none; border-collapse: collapse;">
            <tbody>
                <tr>
                    <th style="border: none;">
                        <img style="width: 100px; height: 100px; margin: 0 auto; margin-left: 40rem" src="{{ $logoPh }}">
                    </th>
                    <td style="text-align: center; border: none;" width="80%">
                        <p style="margin: 0; font-weight: bold; font-size: 20px; font-family: 'Garamond', serif;">Republic of the Philippines</p>
                        <p style="margin: 0; font-weight: none">Province of Nueva Vizcaya</p>
                        <p style="margin: 0; font-weight: none">Bayombong</p>
                        <span style="margin: 0; font-size: 13px; font-weight:bold">PROVINCIAL HUMAN RESOURCE AND MANAGEMENT OFFICE</span>
                        <p style="margin: 0; font-size: 18px; font-weight:bold; margin-top:0.5rem;">Transaction Records 
                            of {{ $clientName }}
                        </p>
                    </td>
                    <th style="border: none;">
                        <img style="width: 80px; height: 80px; margin: 0 auto; margin-right: 39rem" src="{{ $logo }}">
                    </th>
                </tr>
            </tbody>
        </table>    
    </div>

    <main>
        <div class="container-fluid">
            <table class="table" style="font-size: 9px">
                <thead>
                    <tr>
                        <th>Number of Items Requested</th>
                        <th>UoM</th>
                        <th>Item Name</th>
                        <th>Date/Time Request</th>
                        <th>Date/Time Acted</th>
                        <th>Request Aging</th>
                        <th>Released by</th>
                        <th>Date/Time Released</th>
                        <th>Date/Time Receive</th>
                        <th>Receive Aging</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->transactionDetail->request_quantity }}</td>
                            <td>{{ $transaction->item->inventory->unit->name }}</td>
                            <td>{{ $transaction->item->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('F d, Y h:i A') }}</td>
                            <td>
                                {{ $transaction->approved_date ? \Carbon\Carbon::parse($transaction->approved_date)->format('F d, Y') : '--' }}
                                {{ $transaction->approved_time ? \Carbon\Carbon::parse($transaction->approved_time)->format('h:i A') : '--' }}
                            </td>
                            <td>{{ $transaction->request_aging ? $transaction->request_aging : '--' }}</td>
                            <td>{{ $transaction->adminBy ? $transaction->adminBy->full_name : '--' }}</td>
                            <td>{{ $transaction->approved_date ? \Carbon\Carbon::parse($transaction->approved_date)->format('F d, Y') : '--' }} {{ $transaction->released_time ? \Carbon\Carbon::parse($transaction->released_time)->format('h:i A') : '--' }}</td>
                            <td>{{ $transaction->accepted_date_time ? \Carbon\Carbon::parse($transaction->accepted_date_time)->format('F d, Y h:i A') : '--' }}</td>
                            <td>{{ $transaction->released_aging ? $transaction->released_aging : '--' }}</td>
                            <td>{{ $transaction->remark }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="container" style="margin-top: 2rem">
            <div class="row">
                <div class="col-md-12">
                    <table class="signatories-table table" style="border: none;">
                        <thead class="signatories-table">
                            <tr>
                                <td colspan="2">Generated by:</td>
                                <td colspan="2">Reviewed by:</td>
                                <td colspan="2">Noted by:</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2">
                                    <p>
                                        <strong>{{ strToUpper($clientName) }}</strong><br>
                                        <span style="font-style: italic">{{ strToUpper($client->position) }}</span>
                                    </p>
                                </td>
                                <td colspan="2">
                                    <p>
                                        <strong>CHRISTINE JOY C. BARTOLOME</strong><br>
                                        <span style="font-style: italic">AAIV/ACTING AO</span>
                                    </p>
                                </td>
                                <td colspan="2">
                                    <p>
                                        <strong>MA. CARLA LUCIA M. TORRALBA</strong><br>
                                        <span style="font-style: italic">PHRMO</span>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <div class="footer">
        <div class="left">
            <span>Generated By: {{ $generatedBy->full_name }} | Date/Time Generated: {{ $now }}</span>
        </div>
        <div class="right">
            <span>Page <span class="pagenum"></span></span>
        </div>
    </div>
</body>
</html>
