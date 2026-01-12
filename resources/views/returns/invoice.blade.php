<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Return Receipt - {{ $return->return_invoice_number }}</title>
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

        @media print {
            body {
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="text-center">
        <h2 style="margin: 0;">RETURN RECEIPT</h2>
        <p style="margin: 5px 0;">PHARMACY POS<br>Return Transaction</p>
    </div>

    <div class="border-bottom my-2"></div>

    <div>
        <table style="margin-bottom: 10px;">
            <tr>
                <td>Return: {{ $return->return_invoice_number }}</td>
                <td class="text-end">{{ $return->return_date->format('d-M-Y H:i') }}</td>
            </tr>
            <tr>
                <td>Orig. Invoice: {{ $return->originalSale->invoice_number }}</td>
                <td class="text-end">Type: {{ ucfirst($return->return_type) }}</td>
            </tr>
        </table>
    </div>

    <div class="border-bottom mb-2"></div>

    <table style="width: 100%;">
        <thead>
            <tr>
                <th style="text-align: left;">Item</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($return->items as $item)
                <tr>
                    <td>{{ $item->product->subCategory ? $item->product->subCategory->name : '' }}
                        {{ $item->product->name }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="border-bottom my-2"></div>

    <div style="width: 150px; margin-left: auto;">
        <table class="fw-bold">
            <tr>
                <td>Total Return:</td>
                <td class="text-end">Rs. {{ number_format($return->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="border-bottom my-2"></div>

    <div class="text-center" style="margin-top: 20px;">
        <p>This is a formal return receipt.<br>Please keep this for your records.</p>
    </div>
</body>

</html>
