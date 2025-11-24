<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?php echo $invoice->invoice_number; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            color: #333;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #4f46e5;
        }

        .company-info h1 {
            color: #4f46e5;
            font-size: 28px;
            margin-bottom: 8px;
        }

        .company-info p {
            margin: 4px 0;
            font-size: 14px;
            color: #666;
        }

        .invoice-info {
            text-align: right;
        }

        .invoice-info h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 8px;
        }

        .invoice-info p {
            margin: 4px 0;
            font-size: 14px;
        }

        .parties {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        .party {
            width: 48%;
        }

        .party h3 {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .party p {
            margin: 4px 0;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th {
            background-color: #f8fafc;
            padding: 12px;
            text-align: left;
            font-size: 13px;
            border-bottom: 2px solid #e2e8f0;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }

        .text-right {
            text-align: right;
        }

        .totals {
            margin-left: auto;
            width: 300px;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
        }

        .totals-row.grand {
            font-size: 18px;
            font-weight: bold;
            padding-top: 12px;
            border-top: 2px solid #333;
        }

        .notes {
            margin-top: 30px;
            padding: 16px;
            background-color: #f8fafc;
            border-radius: 4px;
        }

        .notes h3 {
            font-size: 14px;
            margin-bottom: 8px;
        }

        .notes p {
            font-size: 13px;
            color: #666;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }

        @media print {
            body {
                padding: 20px;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="header">
            <div class="company-info">
                <h1><?php echo $settings ? $settings->company_name : 'BillBook'; ?></h1>
                <?php if ($settings): ?>
                    <?php if ($settings->address): ?>
                        <p><?php echo nl2br($settings->address); ?></p><?php endif; ?>
                    <?php if ($settings->email): ?>
                        <p>Email: <?php echo $settings->email; ?></p><?php endif; ?>
                    <?php if ($settings->phone): ?>
                        <p>Phone: <?php echo $settings->phone; ?></p><?php endif; ?>
                    <?php if ($settings->gstin): ?>
                        <p>GSTIN: <?php echo $settings->gstin; ?></p><?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="invoice-info">
                <h2>INVOICE</h2>
                <p><strong><?php echo $invoice->invoice_number; ?></strong></p>
                <p>Date: <?php echo date('d M Y', strtotime($invoice->invoice_date)); ?></p>
                <?php if ($invoice->due_date): ?>
                    <p>Due: <?php echo date('d M Y', strtotime($invoice->due_date)); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="parties">
            <div class="party">
                <h3>Bill To</h3>
                <p><strong><?php echo $invoice->customer_name; ?></strong></p>
                <?php if ($invoice->address): ?>
                    <p><?php echo nl2br($invoice->address); ?></p><?php endif; ?>
                <?php if ($invoice->email): ?>
                    <p><?php echo $invoice->email; ?></p><?php endif; ?>
                <?php if ($invoice->phone): ?>
                    <p><?php echo $invoice->phone; ?></p><?php endif; ?>
                <?php if ($invoice->gstin): ?>
                    <p>GSTIN: <?php echo $invoice->gstin; ?></p><?php endif; ?>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Tax Rate</th>
                    <th>Tax</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoice_items as $item): ?>
                    <tr>
                        <td><?php echo $item->item_name; ?></td>
                        <td><?php echo $item->quantity; ?></td>
                        <td><?php echo $currency_symbol; ?>     <?php echo number_format($item->price, 2); ?></td>
                        <td><?php echo $item->tax_rate; ?>%</td>
                        <td><?php echo $currency_symbol; ?>     <?php echo number_format($item->tax_amount, 2); ?></td>
                        <td class="text-right"><?php echo $currency_symbol; ?>     <?php echo number_format($item->total, 2); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="totals">
            <div class="totals-row">
                <span>Subtotal:</span>
                <span><?php echo $currency_symbol; ?> <?php echo number_format($invoice->subtotal, 2); ?></span>
            </div>
            <div class="totals-row">
                <span>Tax:</span>
                <span><?php echo $currency_symbol; ?> <?php echo number_format($invoice->tax_total, 2); ?></span>
            </div>
            <div class="totals-row grand">
                <span>Grand Total:</span>
                <span><?php echo $currency_symbol; ?> <?php echo number_format($invoice->grand_total, 2); ?></span>
            </div>
        </div>

        <?php if ($invoice->notes): ?>
            <div class="notes">
                <h3>Notes</h3>
                <p><?php echo nl2br($invoice->notes); ?></p>
            </div>
        <?php endif; ?>

        <div class="footer">
            <p>Thank you for your business!</p>
        </div>
    </div>

    <script>
        window.onload = function () {
            window.print();
        }
    </script>
</body>

</html>