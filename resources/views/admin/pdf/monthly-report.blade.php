<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Monthly Report</title>
</head>
<body>
    <table border="2">
       <thead>
            <tr>
                <th>No.</th>
                <th></th>
                <th>Units</th>
                <th>Balance as of </th>
                <th>Total Supply Received</th>
                <th>Total Supply Withdrawn</th>
                <th>Balance as of</th>
            </tr>
       </thead>
       <tbody>
            @php
                $groupedItems = $items->groupBy('category.name');
                $count = 1;
            @endphp
            
            @foreach ($groupedItems as $categoryName => $itemsInCategory)
                <tr>
                    <td colspan="2"><strong>{{ $categoryName }}</strong></td>
                </tr>
                @foreach ($itemsInCategory as $item)
                    <tr>
                        <td>{{ $count++ }}</td> 
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->inventory->unit->name }}</td>
                        <td>{{ $item->inventory->quantity}}</td>
                        <td>{{ $item->receives->sum('received_quantity') }}</td>
                    </tr>
                @endforeach
            @endforeach
       </tbody>
       <tfoot>

       </tfoot>
    </table>
</body>
</html>