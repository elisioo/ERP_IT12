<!DOCTYPE html>
<html>

<head>
    <title>Invoice</title>
    <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 5px;
    }
    </style>
</head>

<body>
    <h2>Invoice</h2>
    <p><strong>Order No:</strong> {{ $order->order_number }}</p>
    <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
    <p><strong>Date:</strong> {{ $order->order_date }}</p>

    <table>
        <thead>
            <tr>
                <th>Menu</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->lines as $line)
            <tr>
                <td>{{ $line->menu->menu_name }}</td>
                <td>{{ $line->quantity }}</td>
                <td>₱{{ number_format($line->menu->price, 2) }}</td>
                <td>₱{{ number_format($line->price, 2) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="3" style="text-align:right"><strong>Total:</strong></td>
                <td><strong>₱{{ number_format($order->total_amount, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>
</body>

</html>