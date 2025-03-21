<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body{
            font-size: 10px;
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
            width: 100%;
        }

        th, td {
            border: 2px solid #000;
            padding: 8px;
            text-align: left;
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
    </style>
</head>
<body>
    <table border="2" width="100%" class="table table-responsive">
        <thead>
            <tr>
                <th colspan="2">No.</th>
                <th colspan="2">Item Description</th>
                <th colspan="2">Units</th>
                <th colspan="2">Balance as of {{ $formatFinalSubMonth }}</th>
                <th colspan="2">Delivered</th>
                <th colspan="4">MONTHLY UTILIZATION WITHDRAWAL</th>
                <th colspan="2">Available Stock as of {{ $formatFinalMonth }}</th>
              </tr>
              <tr>
                <th colspan="10"></th>
                @foreach ($explodeQuarters as $quarter)
                    <td>{{ $quarter }}</td>
                @endforeach
                <td>Total</td>
                <th colspan="2"></th>
              </tr>
        </thead>
        <tbody>
            @php
                $groupedItems = $items->groupBy('category.name');
                $count = 1;
                $printedParts = []; 
            @endphp
            @foreach ($groupedItems as $categoryName => $itemsInCategory)
                @php
                    $subCategories = $itemsInCategory->groupBy(function ($item) {
                        return $item->category->sub_category ? $item->category->sub_category->name : 'No Sub-Category'; 
                    });
                @endphp
                @foreach ($subCategories as $subCategoryName => $itemsInSubCategory)
                    @php
                        if ($subCategoryName == 'Part I. Available at procurement services stores') {
                            $partName = 'Part I. Available at procurement services stores';
                        } else {
                            $partName = 'Part II. Other items not available at ps but regularly purchased from other sources';
                        }
                    @endphp
                    @if (!isset($printedParts[$subCategoryName]))
                        <tr>
                            <td colspan="16"><strong>{{ $partName }}</strong></td>
                        </tr>
                        @php
                            $printedParts[$subCategoryName] = true; 
                        @endphp
                    @endif
                    {{-- <tr>
                        <td colspan="16"><strong>{{ $subCategoryName }}</strong></td>
                    </tr> --}}
                    <tr>
                        <td colspan="16"><strong>{{ $categoryName }}</strong></td>
                    </tr>
                    @foreach ($itemsInCategory as $item)
                        <tr class="text-center">
                            <td colspan="2">{{ $count++ }}.</td>
                            <td colspan="2">{{ $item->name }}</td>
                            <td colspan="2">{{ $item->inventory->unit->name }}</td>
                            <td colspan="2">{{ $item->inventory->quantity }}</td>
                            <td colspan="2">{{ $item->receives->sum('received_quantity') }}</td>
                            <td colspan="1">0</td>
                            <td colspan="1">0</td>
                            <td colspan="1">0</td>
                            <td colspan="1">0</td>
                            <td colspan="2">{{ $item->inventory->quantity + $item->receives->sum('received_quantity') }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
        </tfoot>
    </table>
</body>
</html>