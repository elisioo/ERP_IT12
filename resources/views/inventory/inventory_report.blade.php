<!DOCTYPE html>
<html>

<head>
    <title>Inventory Report</title>
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

    h4 {
        margin-bottom: 5px;
    }

    .summary {
        margin-bottom: 20px;
    }

    .summary div {
        margin-bottom: 5px;
    }
    </style>
</head>

<body>
    <h2>Inventory Report</h2>
    <p>Date: {{ date('F d, Y') }}</p>

    <div class="summary">
        <div><strong>Total Items:</strong> {{ $totalItems }}</div>
        <div><strong>Low Stock Items:</strong> {{ $lowStockCount }}</div>
        <div><strong>Out of Stock Items:</strong> {{ $outOfStockCount }}</div>
        <div><strong>Total Inventory Value:</strong> ₱{{ number_format($totalValue, 2) }}</div>
    </div>

    <h4>Category Summary</h4>
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Total Items</th>
                <th>Total Value (₱)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categorySummary as $cat)
            <tr>
                <td>{{ $cat->category_name }}</td>
                <td>{{ $cat->total_items }}</td>
                <td>₱{{ number_format($cat->total_value, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">No data for now</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h4>Inventory Details</h4>
    <table>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Unit Price (₱)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
            <tr>
                <td>{{ $item->menu->menu_name ?? 'Unnamed' }}</td>
                <td>{{ $item->category->category_name ?? 'N/A' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->unit }}</td>
                <td>₱{{ number_format($item->cost_price, 2) }}</td>
                <td>
                    {{ $item->quantity == 0 ? 'Out of Stock' : ($item->quantity < 10 ? 'Low Stock' : 'In Stock') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No data for now</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>