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
                <th width="10%">Balance as of {{ $formattedDate }}</th>
                <th width="10%">Total Supply Received</th>
                <th width="10%">Total Supply Withdrawn</th>
                <th width="10%">Balance as of {{ $formattedCurrentDate }}</th>
            </tr>
        </thead>
        @php
            $groupedItems = $itemsPart1->groupBy('category.name');
            $groupedItemsPart2 = $itemsPart2->groupBy('category.name');
            $count = 1;
        @endphp
        <tr>
            <td colspan="2" style="font-weight: bold">Part I. Available at procurement services stores</td>
            <td colspan=""></td>
            <td colspan=""></td>
            <td colspan=""></td>
            <td colspan=""></td>
            <td colspan=""></td>
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
                    <td colspan=""></td>
                    <td colspan=""></td>
                    <td colspan=""></td>
                    <td colspan=""></td>
                    <td colspan=""></td>
                </tr>
                @foreach ($itemsInSubCategory as $item)
                    <tr>
                        <td class="rightText">{{ $count++ }}.</td>
                        <td>{{ $item->name }}</td>
                        <td class="rightText">{{ $item->inventory->unit->name }}</td>
                        <td class="rightText">{{ $item->total_received_quantity }}</td>
                        <td class="rightText">{{ $item->receives->sum('received_quantity') }}</td>
                        <td class="rightText">{{ $item->inventory->quantity }}</td>
                        <td class="rightText">{{ $item->receives->sum('received_quantity') - $item->inventory->quantity }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
        <tr>
            <td colspan="2" style="font-weight: bold">Part II. Other items not available at ps but regularly purchased from other sources</td>
            <td colspan=""></td>
            <td colspan=""></td>
            <td colspan=""></td>
            <td colspan=""></td>
            <td colspan=""></td>
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
                    <td colspan=""></td>
                    <td colspan=""></td>
                    <td colspan=""></td>
                    <td colspan=""></td>
                    <td colspan=""></td>
                </tr>
                @foreach ($itemsInSubCategory as $item)
                    <tr>
                        <td class="rightText">{{ $count++ }}.</td>
                        <td>{{ $item->name }}</td>
                        <td class="rightText">{{ $item->inventory->unit->name }}</td>
                        <td class="rightText">{{ $item->total_received_quantity }}</td>
                        <td class="rightText">{{ $item->receives->sum('received_quantity') }}</td>
                        <td class="rightText">{{ $item->inventory->quantity }}</td>
                        <td class="rightText">{{ $item->receives->sum('received_quantity') - $item->inventory->quantity }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
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
                                    <strong>{{ $client->full_name }}</strong><br>
                                    <span style="font-style: italic">{{ $client->position }}</span>
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