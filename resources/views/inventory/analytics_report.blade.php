<!DOCTYPE html>
<html>

<head>
    <title>Sales Report</title>
    <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 5px;
        text-align: left;
    }

    th {
        background-color: #f0f0f0;
    }
    </style>
</head>

<body>
    <h3>Sales Report</h3>
    <p>From: {{ $request->from_date }} To: {{ $request->to_date }}</p>
    <p>Total Orders: {{ $totalOrders }} | Total Sales: ₱{{ number_format($totalSales, 2) }}</p>

    <table>
        <thead>
            <tr>
                <th>Order No.</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->order_date }}</td>
                <td>₱{{ number_format($order->total_amount, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No data for now</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>