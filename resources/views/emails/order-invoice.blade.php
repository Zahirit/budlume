<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Invoice {{ $order->order_number }}
    </title>
</head>

<body style="
    margin:0;
    padding:0;
    background:#f4f4f4;
    font-family:Arial, Helvetica, sans-serif;
    color:#333;
">

<table width="100%" cellpadding="0" cellspacing="0"
       style="background:#f4f4f4; padding:30px 15px;">

    <tr>
        <td align="center">

            <table width="650"
                   cellpadding="0"
                   cellspacing="0"
                   style="
                       width:100%;
                       max-width:650px;
                       background:#ffffff;
                       border-collapse:collapse;
                   ">

                {{-- Header --}}
                <tr>
                    <td style="
                        background:#222;
                        color:#ffffff;
                        padding:25px 30px;
                    ">

                        <h1 style="
                            margin:0;
                            font-size:28px;
                        ">
                            Budlume
                        </h1>

                        <p style="
                            margin:8px 0 0;
                            color:#dddddd;
                        ">
                            Order Confirmation & Invoice
                        </p>

                    </td>
                </tr>

                {{-- Thank You --}}
                <tr>
                    <td style="padding:30px;">

                        <h2 style="margin-top:0;">
                            Thank you for your order!
                        </h2>

                        <p>
                            Hello {{ $order->customer_name }},
                        </p>

                        <p>
                            We have successfully received your order.
                            Below are your order and invoice details.
                        </p>

                    </td>
                </tr>

                {{-- Order Details --}}
                <tr>
                    <td style="padding:0 30px 25px;">

                        <table width="100%"
                               cellpadding="8"
                               cellspacing="0"
                               style="
                                   border-collapse:collapse;
                                   background:#f8f8f8;
                               ">

                            <tr>
                                <td>
                                    <strong>Order Number</strong>
                                </td>

                                <td align="right">
                                    {{ $order->order_number }}
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <strong>Order Date</strong>
                                </td>

                                <td align="right">
                                    {{ $order->created_at->format('M d, Y') }}
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <strong>Status</strong>
                                </td>

                                <td align="right">
                                    {{ ucfirst($order->status) }}
                                </td>
                            </tr>

                        </table>

                    </td>
                </tr>

                {{-- Products --}}
                <tr>
                    <td style="padding:0 30px 25px;">

                        <h3>Order Summary</h3>

                        <table width="100%"
                               cellpadding="10"
                               cellspacing="0"
                               style="
                                   border-collapse:collapse;
                                   border:1px solid #dddddd;
                               ">

                            <thead>

                                <tr style="background:#f1f1f1;">

                                    <th align="left">
                                        Product
                                    </th>

                                    <th align="center">
                                        Qty
                                    </th>

                                    <th align="right">
                                        Price
                                    </th>

                                    <th align="right">
                                        Total
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($order->items as $item)

                                    <tr>

                                        <td style="
                                            border-top:1px solid #dddddd;
                                        ">
                                            {{ $item->product->name ?? 'Product' }}
                                        </td>

                                        <td align="center"
                                            style="
                                                border-top:1px solid #dddddd;
                                            ">
                                            {{ $item->quantity }}
                                        </td>

                                        <td align="right"
                                            style="
                                                border-top:1px solid #dddddd;
                                            ">
                                            ${{ number_format($item->price, 2) }}
                                        </td>

                                        <td align="right"
                                            style="
                                                border-top:1px solid #dddddd;
                                            ">
                                            ${{ number_format($item->subtotal, 2) }}
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </td>
                </tr>

                {{-- Totals --}}
                <tr>
                    <td style="padding:0 30px 25px;">

                        <table width="100%"
                               cellpadding="7"
                               cellspacing="0">

                            <tr>
                                <td align="right">
                                    Subtotal:
                                </td>

                                <td width="120" align="right">
                                    ${{ number_format($order->subtotal, 2) }}
                                </td>
                            </tr>

                            @if($order->discount_amount > 0)

                                <tr>
                                    <td align="right">
                                        Discount
                                        ({{ number_format($order->discount_percentage, 2) }}%):
                                    </td>

                                    <td align="right">
                                        -${{ number_format($order->discount_amount, 2) }}
                                    </td>
                                </tr>

                            @endif

                            <tr>
                                <td align="right"
                                    style="font-size:18px;">
                                    <strong>Total:</strong>
                                </td>

                                <td align="right"
                                    style="font-size:18px;">
                                    <strong>
                                        ${{ number_format($order->total_amount, 2) }}
                                    </strong>
                                </td>
                            </tr>

                        </table>

                    </td>
                </tr>

                {{-- Delivery Address --}}
                <tr>
                    <td style="
                        padding:0 30px 30px;
                    ">

                        <h3>Delivery Address</h3>

                        <p style="
                            line-height:1.7;
                            margin-bottom:0;
                        ">

                            {{ $order->customer_name }}
                            <br>

                            {{ $order->delivery_address_line_1 }}
                            <br>

                            @if($order->delivery_address_line_2)

                                {{ $order->delivery_address_line_2 }}
                                <br>

                            @endif

                            {{ $order->delivery_city }}

                            @if($order->delivery_state)
                                , {{ $order->delivery_state }}
                            @endif

                            {{ $order->delivery_postal_code }}

                            <br>

                            {{ $order->delivery_country }}

                            <br><br>

                            Phone:
                            {{ $order->customer_phone }}

                        </p>

                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="
                        background:#222;
                        color:#cccccc;
                        padding:22px 30px;
                        text-align:center;
                        font-size:13px;
                    ">

                        Thank you for shopping with Budlume.

                        <br>

                        Please keep this email for your order records.

                    </td>
                </tr>

            </table>

        </td>
    </tr>

</table>

</body>
</html>