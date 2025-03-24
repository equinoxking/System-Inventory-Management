<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Quarterly Report Generation</title>
    <style>
        body{
            font-size: 11px;
        }
        .centerText{
            text-align: center;
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
        .content {
            margin-top: 60px; 
            margin-bottom: 30px; 
        }
        table {
            border-collapse: collapse;
        }
        th, td {
            padding: 0; 
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
        .title{
            text-align: center;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="title">
        <strong>{{ $title }}</strong><br>
        <strong>{{ $sub_title }}</strong> <br>
        <strong>As of {{ $endOfMonthFormatted }}</strong>
    </div>
    <table border="2" class="table table-responsive">
        <thead>
            <tr style="background-color:lightgrey">
                <th width="5%" colspan="2" rowspan="2">No.</th>
                <th style="text-align: center; width: 50%;" colspan="2" rowspan="2">Item Description</th>
                <th width="5%" colspan="2" rowspan="2">Units</th>
                <th width="5%" colspan="2" rowspan="2">Balance as of {{ $formatFinalSubMonth }}</th>
                <th width="5%" colspan="2" rowspan="2">Delivery</th>
                <th width="5%" colspan="4">MONTHLY UTILIZATION WITHDRAWAL</th>
                <th width="5%" colspan="2" rowspan="2">Available Stock as of {{ $formatFinalMonth }}</th>
            </tr>
            <!-- Second Header Row -->
            <tr style="background-color:lightgrey">
                @foreach ($explodeQuarters as $quarter)
                    @foreach ($quarters[$quarter] as $month)
                        <!-- Convert month name to numeric value -->
                        <th>{{ $monthAbbreviations[$month] }}</th>
                    @endforeach
                @endforeach
                <th >Total</th>
            </tr>
            <tr>
                <th  style="text-align: center;" colspan="4">(A)</th>
                <th  style="text-align: center;" colspan="2">(B)</th>
                <th  style="text-align: center;" colspan="2">(C)</th>
                <th  style="text-align: center;" colspan="2">(D)</th>
                <th  style="text-align: center;" colspan="1">(E)</th>
                <th  style="text-align: center;" colspan="1">(F)</th>
                <th  style="text-align: center;" colspan="1">(G)</th>
                <th  style="text-align: center;" colspan="1">(H=E+F+G)</th>
                <th  style="text-align: center;" colspan="2">(I=C+D-H)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $groupedItemsPart1 = $itemsPart1->groupBy('category.name');
                $groupedItemsPart2 = $itemsPart2->groupBy('category.name');
                $count = 1;
                $printedParts = []; 
            @endphp
            <tr>
                <td colspan="6" style="font-weight: bold">Part I. Available at procurement services stores</td>
                <td colspan="2" style="font-weight: bold"></td>
                <td colspan="2" style="font-weight: bold"></td>
                <td colspan="1" style="font-weight: bold"></td>
                <td colspan="1" style="font-weight: bold"></td>
                <td colspan="1" style="font-weight: bold"></td>
                <td colspan="1" style="font-weight: bold"></td>
                <td colspan="2" style="font-weight: bold"></td>
            </tr>
            @foreach ($groupedItemsPart1 as $categoryName => $itemsInCategory)
                @php
                    $subCategories = $itemsInCategory->groupBy(function ($item) {
                        return $item->category->sub_category ? $item->category->sub_category->name : 'No Sub-Category'; 
                    });
                @endphp
                @foreach ($subCategories as $subCategoryName => $itemsInSubCategory)
                    <tr>
                        <td colspan="4"><strong>{{ $categoryName }}</strong></td>
                        <td colspan="2" style="font-weight: bold"></td>
                        <td colspan="2" style="font-weight: bold"></td>
                        <td colspan="2" style="font-weight: bold"></td>
                        <td colspan="1" style="font-weight: bold"></td>
                        <td colspan="1" style="font-weight: bold"></td>
                        <td colspan="1" style="font-weight: bold"></td>
                        <td colspan="1" style="font-weight: bold"></td>
                        <td colspan="2" style="font-weight: bold"></td>
                    </tr>
                    @foreach ($itemsInCategory as $item)
                        <tr class="text-center">
                            <td colspan="2" style="text-align: center">{{ $count++ }}.</td>
                            <td colspan="2">{{ $item->name }}</td>
                            <td colspan="2" style="text-align: center">{{ $item->inventory->unit->name }}</td>
                            <td colspan="2" style="text-align: center">{{ $item->total_balances }}</td>
                            <td colspan="2" style="text-align: center">{{ $item->total_received_quantity }}</td>
                            <td colspan="1" style="text-align: center">0</td>
                            <td colspan="1" style="text-align: center">0</td>
                            <td colspan="1" style="text-align: center">0</td>
                            <td colspan="1" style="text-align: center">0</td>
                            <td colspan="2" style="text-align: center">{{ $item->inventory->quantity + $item->receives->sum('received_quantity') }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
            <tr>
                <td colspan="6" style="font-weight: bold">Part II. Other items not available at ps but regularly purchased from other sources</td>
                <td colspan="2" style="font-weight: bold"></td>
                <td colspan="2" style="font-weight: bold"></td>
                <td colspan="1" style="font-weight: bold"></td>
                <td colspan="1" style="font-weight: bold"></td>
                <td colspan="1" style="font-weight: bold"></td>
                <td colspan="1" style="font-weight: bold"></td>
                <td colspan="2" style="font-weight: bold"></td>
            </tr>
            @foreach ($groupedItemsPart2 as $categoryName => $itemsInCategory)
                @php
                    $subCategories = $itemsInCategory->groupBy(function ($item) {
                        return $item->category->subCategory ? $item->category->subCategory->id : 'No Sub-Category';
                    });
                @endphp
                @foreach ($subCategories as $subCategoryId => $itemsInSubCategory)
                    <tr>
                        <td colspan="4"><strong>{{ $categoryName }}</strong></td>
                        <td colspan="2" style="font-weight: bold"></td>
                        <td colspan="2" style="font-weight: bold"></td>
                        <td colspan="2" style="font-weight: bold"></td>
                        <td colspan="1" style="font-weight: bold"></td>
                        <td colspan="1" style="font-weight: bold"></td>
                        <td colspan="1" style="font-weight: bold"></td>
                        <td colspan="1" style="font-weight: bold"></td>
                        <td colspan="2" style="font-weight: bold"></td>
                    </tr>
                    @foreach ($itemsInCategory as $item)
                            <tr class="text-center">
                                <td colspan="2" style="text-align: center">{{ $count++ }}.</td>
                                <td colspan="2">{{ $item->name }}</td>
                                <td colspan="2" style="text-align: center">{{ $item->inventory->unit->name }}</td>
                                <td colspan="2" style="text-align: center">{{ $item->total_balances }}</td>
                                <td colspan="2" style="text-align: center">{{ $item->total_received_quantity }}</td>
                                <td colspan="1" style="text-align: center">0</td>
                                <td colspan="1" style="text-align: center">0</td>
                                <td colspan="1" style="text-align: center">0</td>
                                <td colspan="1" style="text-align: center">0</td>
                                <td colspan="2" style="text-align: center">{{ $item->inventory->quantity + $item->receives->sum('received_quantity') }}</td>
                            </tr>
                        @endforeach
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>