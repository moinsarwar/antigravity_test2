<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $sale->invoice_number }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .border-bottom {
            border-bottom: 1px dashed #000;
        }

        .my-2 {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
        }

        @media print {
            body {
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="text-center">
        <h2 style="margin: 0;">PHARMACY POS</h2>
        <p style="margin: 5px 0;">123 Healthcare St, Pharmacy City<br>Phone: +1 234 567 890</p>
    </div>

    <div class="border-bottom my-2"></div>

    <div>
        <table style="margin-bottom: 10px;">
            <tr>
                <td>Invoice: {{ $sale->invoice_number }}</td>
                <td class="text-end">{{ $sale->sale_date->format('d-M-Y H:i') }}</td>
            </tr>
            <tr>
                <td>Customer: {{ $sale->customer_name ?? 'Walk-in' }}</td>
                <td class="text-end">Type: {{ ucfirst($sale->payment_type) }}</td>
            </tr>
        </table>
    </div>

    <div class="border-bottom mb-2"></div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Disc</th>
                <th class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
                <tr>
                    <td>{{ $item->product->subCategory ? $item->product->subCategory->name : '' }} {{ $item->product->name }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-end">{{ $item->discount_amount > 0 ? number_format($item->discount_amount, 2) : '-' }}
                    </td>
                    <td class="text-end">{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="border-bottom my-2"></div>

    <div style="width: 180px; margin-left: auto;">
        <table class="fw-bold">
            @php
                $totalDiscount = $sale->items->sum('discount_amount');
            @endphp
            @if($totalDiscount > 0)
                <tr>
                    <td>Gross:</td>
                    <td class="text-end">Rs. {{ number_format($sale->total_amount + $totalDiscount, 2) }}</td>
                </tr>
                <tr>
                    <td>Discount:</td>
                    <td class="text-end">Rs. {{ number_format($totalDiscount, 2) }}</td>
                </tr>
            @endif
            <tr>
                <td>Total:</td>
                <td class="text-end">Rs. {{ number_format($sale->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Paid:</td>
                <td class="text-end">Rs. {{ number_format($sale->cash_amount, 2) }}</td>
            </tr>
            @if($sale->credit_amount > 0)
                <tr>
                    <td>Due:</td>
                    <td class="text-end">Rs. {{ number_format($sale->credit_amount, 2) }}</td>
                </tr>
            @endif
        </table>
    </div>

    <div class="border-bottom my-2"></div>

    <div class="text-center">
        <p>Thank you for your visit!<br>Prescription medicines cannot be returned.</p>
    </div>

    <script>
        // Auto print window when loaded
        window.print();
    </script>
</body>

</html>
