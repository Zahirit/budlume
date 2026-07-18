<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->order_number }}</title>

    <style>

        body{
            font-family:Arial,Helvetica,sans-serif;
            margin:40px;
            color:#333;
        }

        .header{
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            margin-bottom:40px;
        }

        h1{
            margin:0;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        table th,
        table td{
            border:1px solid #ddd;
            padding:10px;
        }

        table th{
            background:#f5f5f5;
        }

        .text-right{
            text-align:right;
        }

        .total{
            font-size:18px;
            font-weight:bold;
        }

        .footer{
            margin-top:60px;
            text-align:center;
            color:#888;
            font-size:13px;
        }

        @media print{

            .no-print{
                display:none;
            }

        }

    </style>

</head>

<body>

<div class="header">

    <div>

        <h1>Budlume</h1>

        <p>
            Cannabis Store<br>
            www.budlume.com
        </p>

    </div>

    <div class="text-right">

        <h2>Invoice</h2>

        <strong>#{{ $order->order_number }}</strong><br>

        {{ $order->created_at->format('d M Y') }}

    </div>

</div>

<hr>

<h3>Customer Information</h3>

<p>

<strong>Name:</strong>
{{ $order->customer->name ?? '' }}

<br>

<strong>Email:</strong>
{{ $order->customer->email ?? '' }}

<br>

<strong>Phone:</strong>
{{ $order->customer->phone ?? '' }}

<br>

<strong>Address:</strong>
{{ $order->customer->address ?? '' }}

</p>

<table>

<thead>

<tr>

<th>Product</th>

<th width="120">Price</th>

<th width="80">Qty</th>

<th width="140">Subtotal</th>

</tr>

</thead>

<tbody>

@foreach($order->items as $item)

<tr>

<td>

{{ $item->product->name ?? 'Deleted Product' }}

</td>

<td class="text-right">

${{ number_format($item->price,2) }}

</td>

<td class="text-right">

{{ $item->quantity }}

</td>

<td class="text-right">

${{ number_format($item->price * $item->quantity,2) }}

</td>

</tr>

@endforeach

</tbody>

<tfoot>

<tr>

<td colspan="3" class="text-right total">

Grand Total

</td>

<td class="text-right total">

${{ number_format($order->total_amount,2) }}

</td>

</tr>

</tfoot>

</table>

<div class="footer">

Thank you for shopping with Budlume.

</div>

<div class="no-print" style="margin-top:40px;">

<button onclick="window.print()">

🖨 Print Invoice

</button>

<button onclick="window.close()">

Close

</button>

</div>

<script>

window.onload=function(){

    window.print();

}

</script>

</body>

</html>