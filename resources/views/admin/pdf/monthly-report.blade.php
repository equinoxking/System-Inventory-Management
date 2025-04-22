<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Monthly Report Generation</title>
    <style>
        
        .rightText{
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
            margin-bottom: 50px;
        }
        table.signatories-table th, table.signatories-table td {
            border: none;
        }
        table.date-generated th, table.date-generated td {
            border: none;
        }
        body{
            font-size: 12px;
        }
        .header {
            text-align: center;
            padding-bottom: 10px;
            font-size: 14px;
            position: absolute;
            top: 30px; /* Space between the header and top of the page */
            left: 0;
            right: 0;
        }

        /* Footer style */
        .footer {
            position: fixed;
            bottom: 30px; /* Distance from the bottom of the page */
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            padding-top: 10px;
        }

        /* Page number style */
        .pagenum:before {
            content: counter(page);
        }

        /* Hide header on all pages except first page */
        .first-page-header {
            display: block;
        }

        .not-first-page .first-page-header {
            display: none;
        }

    </style>
</head>
<body>
    @php
        $imagePath = public_path('assets/images/LOGO-PH.png');
        $imageData = base64_encode(file_get_contents($imagePath));
        $src = 'data:image/png;base64,' . $imageData;
        $imagePath1 = public_path('assets/images/LOGO.webp');
        $imageData1 = base64_encode(file_get_contents($imagePath1));
        $src1 = 'data:image/png;base64,' . $imageData1;
    @endphp
<div class="header first-page-header">
    <table style="border: none; border-collapse: collapse;">
        <tbody>
            <tr>
                <th style="border: none;">
                    <img style="width: 60px; height: 60px; margin: 0 auto; margin-left: 40rem" src="{{ $src1 }}">
                </th>
                <td style="text-align: center; border: none;" width="80%">
                    <p style="margin: 0; font-weight: bold">Republic of the Philippines</p>
                    <p style="margin: 0; font-weight: none">Province of Nueva Vizcaya</p>
                    <p style="margin: 0; font-weight: none">Bayombong</p>
                    <span style="margin: 0; font-size: 13px; font-weight:bold">PROVINCIAL HUMAN RESOURCE AND MANAGEMENT OFFICE</span>
                </td>
                <th style="border: none;">
                    <img style="width: 80px; height: 80px; margin: 0 auto; margin-right: 40rem" src="{{ $src }}">
                </th>
            </tr>
        </tbody>
    </table>    
</div>
<main style="margin-top: 7rem">
    <table class="table date-generated">
        <thead>
            <tr>
                <td>
                    <strong style="font-size: 24px;">{{ $title }}</strong><br>
                    as of {{ $formatLegalCurrentDate }}
                </td>
            </tr>
        </thead>
    </table>
    <table class="table table-responsive content">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="50%">Item Description</th>
                <th width="5%">Units</th>
                <th width="10%">Balance as of {{ $formattedSubDate }}</th>
                <th width="10%">New Deliveries</th>
                <th width="10%">Total Stock on Hand</th>
                <th width="10%">Withdrawal</th>
                <th width="10%">Available Stock</th>
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
                            <td class="rightText">{{ $item->remaining_quantity + $item->total_received_in_selected_month }}</td>
                            <td class="rightText">{{ $item->total_received_in_selected_month }}</td>
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
    <span>Date Generated: {{ $now }}</span> | Page <span class="pagenum"></span>
</div>
</body>
</html>