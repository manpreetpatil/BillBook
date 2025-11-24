<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thermal Print - Invoice #<?php echo $invoice->invoice_number; ?></title>
    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            padding: 10px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 80mm;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .invoice-details {
            margin-bottom: 10px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .items-table th {
            text-align: left;
            border-bottom: 1px solid #000;
        }

        .items-table td {
            padding: 4px 0;
        }

        .totals {
            text-align: right;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
        }

        .no-print {
            display: none;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="header">
        <div class="company-name"><?php echo $settings->company_name ?? 'BillBook'; ?></div>
        <div><?php echo $settings->address ?? ''; ?></div>
        <div><?php echo $settings->phone ?? ''; ?></div>
    </div>

    <div class="invoice-details">
        <div><strong>Inv No:</strong> <?php echo $invoice->invoice_number; ?></div>
        <div><strong>Date:</strong> <?php echo date('d-m-Y', strtotime($invoice->invoice_date)); ?></div>
        <div><strong>Customer:</strong> <?php echo $invoice->customer_name; ?></div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 40%;">Item</th>
                <th style="width: 20%; text-align: center;">Qty</th>
                <th style="width: 40%; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invoice_items as $item): ?>
                <tr>
                    <td><?php echo substr($item->item_name, 0, 15); ?></td>
                    <td style="text-align: center;"><?php echo $item->quantity; ?></td>
                    <td style="text-align: right;"><?php echo number_format($item->total, 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="totals">
        <div>Subtotal: <?php echo number_format($invoice->subtotal, 2); ?></div>
        <div>Tax: <?php echo number_format($invoice->tax_total, 2); ?></div>
        <div style="font-weight: bold; font-size: 14px; margin-top: 5px;">
            Total: <?php echo $settings->currency_symbol ?? ''; ?><?php echo number_format($invoice->grand_total, 2); ?>
        </div>
    </div>

    <div class="footer">
        Thank you for your business!
    </div>

</body>

</html>