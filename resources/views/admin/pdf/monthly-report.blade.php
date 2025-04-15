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
        .header, .footer {
            text-align: center;
            position: fixed;
            left: 0;
            right: 0;
        }
        .header {
            top: 0;
            padding: 10px;
            font-size: 14px;
            font-weight: bold;
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
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            padding: 10px;
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
    </style>
</head>
<body>
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
                <th width="10%">Delivered for Stocks</th>
                <th width="10%">Total Stock on Hand</th>
                <th width="10%">Total Withdrawn</th>
                <th width="10%">Available Stock as of {{ $formattedCurrentDate }}</th>
            </tr>
        </thead>
        <tbody>
            @php
                $groupedItems = $itemsPart1->groupBy('category.name');
                $groupedItemsPart2 = $itemsPart2->groupBy('category.name');
                $count = 1;
            @endphp
    
            <tr>
                <td colspan="8" style="font-weight: bold">Part I. Available at procurement services stores</td>
            </tr>
    
            @foreach ($groupedItems as $categoryName => $itemsInCategory)
                @php
                    $subCategories = $itemsInCategory->groupBy(function ($item) {
                        return $item->category->subCategory ? $item->category->subCategory->id : 'No Sub-Category';
                    });
                @endphp
                @foreach ($subCategories as $subCategoryId => $itemsInSubCategory)
                    <tr>
                        <td colspan="8"><strong>{{ $categoryName }}</strong></td>
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
                <td colspan="8" style="font-weight: bold">Part II. Other items not available at ps but regularly purchased from other sources</td>
            </tr>
    
            @foreach ($groupedItemsPart2 as $categoryName => $itemsInCategory)
                @php
                    $subCategories = $itemsInCategory->groupBy(function ($item) {
                        return $item->category->subCategory ? $item->category->subCategory->id : 'No Sub-Category';
                    });
                @endphp
                @foreach ($subCategories as $subCategoryId => $itemsInSubCategory)
                    <tr>
                        <td colspan="8"><strong>{{ $categoryName }}</strong></td>
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
                            <td colspan="2">Conducted by:</td>
                            <td colspan="2">Prepared by:</td>
                            <td colspan="2">Reviewed by:</td>
                            <td colspan="2">Noted by:</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2">
                                <p>
                                    <strong>{{ strToUpper($conductedBy->full_name)}}</strong><br>
                                    <span style="font-style: italic">{{ $conductedBy->position }}</span>
                                </p>
                            </td>
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
                    <tfoot>
                        <tr>
                            <td><span style="font-size: 9px " >Date Generated: {{ $now }}</span></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</body>
</html>