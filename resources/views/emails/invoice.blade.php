<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>
        Invoice {{ $order->order_number }}
    </title>
</head>

<body style="
    margin:0;
    padding:0;
    background:#f4f4f4;
    font-family:Arial,Helvetica,sans-serif;
    color:#333;
">

<table width="100%"
       cellpadding="0"
       cellspacing="0"
       style="background:#f4f4f4;padding:30px 10px;">

<tr>
<td align="center">

<table width="650"
       cellpadding="0"
       cellspacing="0"
       style="
           max-width:650px;
           width:100%;
           background:#ffffff;
           border-collapse:collapse;
       ">

    {{-- Header --}}
    <tr>
        <td style="
            background:#111;
            color:#fff;
            padding:25px 30px;
        ">
            <h1 style="margin:0;font-size:26px;">
                Budlume
            </h1>

            <p style="margin:6px 0 0;">
                Order Invoice
            </p>
        </td>
    </tr>

    {{-- Greeting --}}
    <tr>
        <td style="padding:30px;">

            <h2 style="margin-top:0;">
                Thank you for your order!
            </h2>

            <p>
                Hello
                <strong>
                    {{ $order->customer_name
                        ?: optional($order->customer)->name
                        ?: 'Customer' }}
                </strong>,
            </p>

            <p>
                We have received your order.
                Below are your invoice details.
            </p>

            <p>
                <strong>Order Number:</strong>
                {{ $order->order_number }}
                <br>

                <strong>Order Date:</strong>
                {{ $order->created_at->format('d M Y') }}
                <br>

                <strong>Status:</strong>
                {{ ucfirst($order->status) }}
            </p>

        </td>
    </tr>

    {{-- Customer Information --}}
    <tr>
        <td style="padding:0 30px 25px;">

            <h3 style="
                border-bottom:1px solid #ddd;
                padding-bottom:10px;
            ">
                Customer Information
            </h3>

            <p style="line-height:1.7;">

                <strong>Name:</strong>
                {{ $order->customer_name
                    ?: optional($order->customer)->name
                    ?: 'N/A' }}

                <br>

                <strong>Email:</strong>
                {{ $order->customer_email
                    ?: optional($order->customer)->email
                    ?: 'N/A' }}

                <br>

                <strong>Phone:</strong>
                {{ $order->customer_phone
                    ?: optional($order->customer)->phone
                    ?: 'N/A' }}

            </p>

        </td>
    </tr>

    {{-- Products --}}
    <tr>
        <td style="padding:0 30px 30px;">

            <h3>Order Details</h3>

            <table width="100%"
                   cellpadding="10"
                   cellspacing="0"
                   style="
                       border-collapse:collapse;
                       border:1px solid #ddd;
                   ">

                <thead>
                <tr style="background:#f5f5f5;">

                    <th align="left"
                        style="border:1px solid #ddd;">
                        Product
                    </th>

                    <th align="right"
                        style="border:1px solid #ddd;">
                        Price
                    </th>

                    <th align="center"
                        style="border:1px solid #ddd;">
                        Qty
                    </th>

                    <th align="right"
                        style="border:1px solid #ddd;">
                        Subtotal
                    </th>

                </tr>
                </thead>

                <tbody>

                @foreach($order->items as $item)

                    <tr>

                        <td style="border:1px solid #ddd;">
                            {{ optional($item->product)->name
                                ?: 'Product' }}
                        </td>

                        <td align="right"
                            style="border:1px solid #ddd;">
                            ${{ number_format($item->price, 2) }}
                        </td>

                        <td align="center"
                            style="border:1px solid #ddd;">
                            {{ $item->quantity }}
                        </td>

                        <td align="right"
                            style="border:1px solid #ddd;">
                            ${{ number_format(
                                $item->price * $item->quantity,
                                2
                            ) }}
                        </td>

                    </tr>

                @endforeach

                </tbody>

                <tfoot>

                <tr>

                    <td colspan="3"
                        align="right"
                        style="
                            border:1px solid #ddd;
                            font-weight:bold;
                        ">
                        Grand Total
                    </td>

                    <td align="right"
                        style="
                            border:1px solid #ddd;
                            font-weight:bold;
                        ">
                        ${{ number_format(
                            $order->total_amount,
                            2
                        ) }}
                    </td>

                </tr>

                </tfoot>

            </table>

        </td>
    </tr>

    {{-- Footer --}}
    <tr>
        <td style="
            background:#f5f5f5;
            padding:20px 30px;
            text-align:center;
            font-size:13px;
            color:#777;
        ">

            Thank you for shopping with Budlume.

        </td>
    </tr>

</table>

</td>
</tr>

</table>

</body>
</html>