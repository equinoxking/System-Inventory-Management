<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Quarterly Report Generation</title>
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
            text-align: center;
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
<main class="content" >
<div class="title">
    <strong>{{ $sub_title }}</strong> <br>
    <strong>As of {{ $endOfMonthFormatted }}</strong>
</div>
    <table class="table table-responsive" width="100%" border="2" style="margin-top: 1rem;">
        <thead>
            <tr style="background-color:lightgrey">
                <th width="5%" colspan="2" rowspan="2">No.</th>
                <th style="text-align: center; width: 50%;" colspan="2" rowspan="2">Item Description</th>
                <th width="5%" colspan="2" rowspan="2">Units</th>
                <th width="5%" colspan="2" rowspan="2">Stock on Hand</th>
                <th width="5%" colspan="2" rowspan="2">
                    Total Delivered 
                </th>
                <th width="5%" colspan="2" rowspan="2">Total Stock on Hand</th>
                <th width="5%" colspan="4">MONTHLY UTILIZATION WITHDRAWAL</th>
                <th width="5%" colspan="2" rowspan="2">Available Stock</th>
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
                <th  style="text-align: center;" colspan="2">(E=C+D)</th>
                <th  style="text-align: center;" colspan="1">(F)</th>
                <th  style="text-align: center;" colspan="1">(G)</th>
                <th  style="text-align: center;" colspan="1">(H)</th>
                <th  style="text-align: center;" colspan="1">(I=E+F+G)</th>
                <th  style="text-align: center;" colspan="2">(J=E-I)</th>
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
                            <td colspan="2">{{ $item->inventory->unit->name }}</td>
                            <td colspan="2" style="text-align: center">{{ $item->remaining_quantity }}</td>
                            <td colspan="2" style="text-align: center">{{ $item->total_received_in_selected_quarter }}</td>
                            <td colspan="2" style="text-align: center">{{ $item->remaining_quantity + $item->total_received_in_selected_quarter }}</td>
                            <td colspan="1" style="text-align: center">{{ $item->total_transactions_first_month }}</td>
                            <td colspan="1" style="text-align: center">{{ $item->total_transactions_second_month }}</td>
                            <td colspan="1" style="text-align: center">{{ $item->total_transactions_third_month }}</td>
                            <td colspan="1" style="text-align: center">{{ $item->total_transactions_first_month + $item->total_transactions_second_month +  $item->total_transactions_third_month}}</td>
                            <td colspan="2" style="text-align: center">{{ $item->remaining_quantity + $item->total_received_in_selected_quarter -  ($item->total_transactions_first_month + $item->total_transactions_second_month +  $item->total_transactions_third_month)}}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
            <tr>
                <td colspan="6" style="font-weight: bold">Part II. Other items not available at ps but regularly purchased from other sources</td>
                <td colspan="2" style="font-weight: bold"></td>
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
                            <td colspan="2" >{{ $item->inventory->unit->name }}</td>
                            <td colspan="2" style="text-align: center">{{ $item->remaining_quantity }}</td>
                            <td colspan="2" style="text-align: center">{{ $item->total_received_in_selected_quarter }}</td>
                            <td colspan="2" style="text-align: center">{{ $item->remaining_quantity + $item->total_received_in_selected_quarter }}</td>
                            <td colspan="1" style="text-align: center">{{ $item->total_transactions_first_month }}</td>
                            <td colspan="1" style="text-align: center">{{ $item->total_transactions_second_month }}</td>
                            <td colspan="1" style="text-align: center">{{ $item->total_transactions_third_month }}</td>
                            <td colspan="1" style="text-align: center">{{ $item->total_transactions_first_month + $item->total_transactions_second_month +  $item->total_transactions_third_month}}</td>
                            <td colspan="2" style="text-align: center">{{ $item->remaining_quantity + $item->total_received_in_selected_quarter -  ($item->total_transactions_first_month + $item->total_transactions_second_month +  $item->total_transactions_third_month)}}</td>
                        </tr>
                        @endforeach
                @endforeach
            @endforeach
        </tbody>
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