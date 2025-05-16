<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Monthly Report Generation</title>
    <style>
        /* Ensure html and body take full height */
        html, body {
            height: 100%;
            margin: 10;
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
            margin-bottom: 0px;/* Ensure space for footer */
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
                </td>
                <th style="border: none;">
                    <img style="width: 80px; height: 80px; margin: 0 auto; margin-right: 40rem" src="{{ $logo }}">
                </th>
            </tr>
        </tbody>
    </table>    
</div>
<main>
    <div class="title" style="text-align: center">
        <strong>{{ $title }}</strong> <br>
        <strong>As of {{ $formatLegalCurrentDate }}</strong>
    </div>
    <table class="table table-responsive content">
        <thead style="margin-top: 30px;">
            <tr style="background-color:lightgrey">
                <th width="5%">No.</th>
                <th width="50%">Item Description</th>
                <th width="5%">Units</th>
                <th width="10%">Balance as of {{ $formattedSubDate }}</th>
                <th width="10%">New Deliveries</th>
                <th width="10%">Total Stock on Hand</th>
                <th width="10%">Withdrawal</th>
                <th width="10%">Available Stock</th>
            </tr>
            <tr style="background-color:lightgrey">
                <th colspan="2">(A)</th>
                <th>(B)</th>
                <th>(C)</th>
                <th>(D)</th>
                <th>(E=C+D)</th>
                <th>(F)</th>
                <th>(G=E-F)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $groupedItems = $itemsPart1->groupBy('category.name');
                $groupedItemsPart2 = $itemsPart2->groupBy('category.name');
                $count = 1;
            @endphp
    
            <tr>
                <td colspan="2" style="font-weight: bold">Part I. Available at procurement services stores</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
    
            @foreach ($groupedItems as $categoryName => $itemsInCategory)
                @php
                    $subCategories = $itemsInCategory->groupBy(function ($item) {
                        return $item->category->subCategory ? $item->category->subCategory->id : 'No Sub-Category';
                    });
                @endphp
                @foreach ($subCategories as $subCategoryId => $itemsInSubCategory)
                    <tr>
                        <td colspan="2"><strong>{{ $categoryName }}</strong></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @foreach ($itemsInSubCategory as $item)
                        <tr>
                            <td class="rightText">{{ $count++ }}.</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->inventory->unit->name }}</td>
                            <td class="rightText">{{ $item->remaining_quantity }}</td>
                            <td class="rightText">{{ $item->total_received_in_selected_month }}</td>
                            <td class="rightText">{{ $item->remaining_quantity + $item->total_received_in_selected_month }}</td>
                            <td class="rightText">{{ $item->total_transactions_in_selected_month }}</td>
                            <td class="rightText">
                                {{ $item->remaining_quantity + $item->total_received_in_selected_month - $item->total_transactions_in_selected_month }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
    
            <tr>
                <td colspan="2" style="font-weight: bold">Part II. Other items not available at ps but regularly purchased from other sources</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
    
            @foreach ($groupedItemsPart2 as $categoryName => $itemsInCategory)
                @php
                    $subCategories = $itemsInCategory->groupBy(function ($item) {
                        return $item->category->subCategory ? $item->category->subCategory->id : 'No Sub-Category';
                    });
                @endphp
                @foreach ($subCategories as $subCategoryId => $itemsInSubCategory)
                    <tr>
                        <td colspan="2"><strong>{{ $categoryName }}</strong></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @foreach ($itemsInSubCategory as $item)
                        <tr>
                            <td class="rightText">{{ $count++ }}.</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->inventory->unit->name }}</td>
                            <td class="rightText">{{ $item->remaining_quantity }}</td>
                            <td class="rightText">{{ $item->total_received_in_selected_month }}</td>
                            <td class="rightText">{{ $item->remaining_quantity + $item->total_received_in_selected_month }}</td>
                            <td class="rightText">{{ $item->total_transactions_in_selected_month }}</td>
                            <td class="rightText">
                                {{ $item->remaining_quantity + $item->total_received_in_selected_month - $item->total_transactions_in_selected_month }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
    </table>
</table>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <table class="signatories-table table" style="border: none;">
                    <thead class="signatories-table">
                        <tr>
                            <td colspan="2">Prepared by:</td>
                            <td colspan="2">Reviewed by:</td>
                            <td colspan="2">Noted by:</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2">
                                <p>
                                    <strong>{{ strToUpper($preparedBy->full_name) }}</strong><br>
                                    <span style="font-style: italic">{{ $preparedBy->position }}</span>
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