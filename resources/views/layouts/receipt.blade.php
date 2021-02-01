<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            /* @page-break {
                page-break-after:avoid;
            } */
            body, table, tr, td {
                font-size: 9px;
            }
            .tiny {
                font-size: 6px;
            }
            .small {
                font-size: 7px;
            }
            .large {
                font-size: 10px;
            }
            @page {
                margin: 0cm;
                font-size: 12px;
            }
            @media only screen and (max-width: 600) {
                
                table {
                    transform: scale(0.9);
                }
            }
        </style>
    </head>
    <body>
        @php
            $items = $total = $subtotal = $discount = 0;
        @endphp
        <table id="salesReceipt" style="width: 100%">
            <tr>
                <td colspan="2"><center><b>XXXXXXXXXXXXXXXXXXXXXX</b></center></td>
            </tr>
            <tr>
                <td colspan="2"><center>XXXXXXXXXXXXXXX, Laoag City,</center></td>
            </tr>
            <tr>
                <td colspan="2"><center>XXXXXXXXXXXXX, Philippines</center></td>
            </tr>
            <tr id="salesOrderNum">
                <td class="large" colspan="2">Order #{{ $data->transaction_id }}</td>
            </tr>
            <tr>
                <td class="small" colspan="2">Date: {{Carbon\Carbon::now()->toDateString()}} Time: {{Carbon\Carbon::now()->toTimeString()}}</td>
            </tr>
            <tr>
                <td class="small" colspan="2">Cashier: {{ $data->user->first_name }}. {{ $data->user->last_name }}</td>
            </tr>
            <tr>
                <td style="font-size:6px" colspan="2"><center>**************************************************</center></td>
            </tr>
            <tr>
                <td colspan="3">
                    
                </td>
            </tr>
            <tr>
                <td style="font-size:6px" colspan="2"><center>**************************************************</center></td>
            </tr>
            <tr>
                <td>Total Items: </td>
                <td align="right">{{ $items }}</td>
            </tr>
            <tr>
                <td style="font-size: 12px">Grand Total: </td>
                <td style="font-size: 12px" align="right"><b>Php {{ number_format($total,2) }}</b></td>
            </tr>
            <tr>
                <td>Total Discount:</td>
                <td align="right"><b>{{ number_format($discount,2) }}</b></td>
            </tr>
            <tr>
                <td>Cash Tendered:</td>
                <td  align="right"><b>{{ number_format($data->cash_tendered,2) }}</b></td>
            </tr>
            <tr>
                <td>Change: </td>
                <td  align="right"><b>{{ number_format($data->cash_tendered - $total,2) }}</b></td>
            </tr>
            <tr>
                <td style="font-size:6px" colspan="2"><center>**************************************************</center></td>
            </tr>
            <tr>
                <td class="small" colspan="2">Card Number: {{ $data->member->card_num ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="small" colspan="2">Customer Name: 
                    @if($data->member)
                        {{ $data->member->first_name }} {{ $data->member->last_name }}
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            <tr>
                <td class="small" colspan="2">Total Points: {{ $data->member->total_points ?? "0.00" }}</td>
            </tr>
            <tr>
                <td class="small" colspan="2"><center>Thank you!</center></td>
            </tr>
        </table>
        {{-- <div class="row">
            <div class="col-12">
                <center><b>XXXXXXXXXXXXXXXXXXXXXX</b></center>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                XXXXXXXXXXXXXXXXXXXXXX
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                XXXXXXXXXXXXXXXXXXXXXX
            </div>
        </div>
        <div class="row">
           <div class="col-12">
                Transaction #
           </div>
        </div>
        <div class="row">
            <div class="col-12">
                Date:
           </div>
        </div>
        <div class="row">
            <div class="col-12">
                Cashier:
           </div>
        </div>
        <div class="row">
            <div class="col-12">
                Date:
           </div>
        </div> --}}
    </body>
</html>
