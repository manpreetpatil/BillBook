<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 20px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        .invoice-title {
            font-size: 2rem;
            font-weight: 700;
            color: #111827;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .meta-table th {
            text-align: left;
            color: #6b7280;
            font-weight: 500;
            padding: 8px 0;
        }

        .meta-table td {
            text-align: right;
            font-weight: 600;
            color: #111827;
            padding: 8px 0;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            text-align: left;
            background: #f9fafb;
            padding: 12px;
            font-weight: 600;
            color: #374151;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            color: #4b5563;
        }

        .total-section {
            display: flex;
            justify-content: flex-end;
        }

        .total-table {
            width: 300px;
        }

        .total-table td {
            padding: 8px 0;
        }

        .grand-total {
            font-size: 1.25rem;
            font-weight: 700;
            color: #4f46e5;
        }

        .actions {
            text-align: center;
            margin-top: 40px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #4f46e5;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
        }

        .btn:hover {
            background: #4338ca;
        }
    </style>
</head>

<body>

    <div class="invoice-container">
        <div class="header">
            <div>
                <div class="invoice-title">INVOICE</div>
                <div style="color: #6b7280;">#<?php echo $invoice->invoice_number; ?></div>
            </div>
            <div style="text-align: right;">
                <h3 style="margin: 0;"><?php echo $settings->company_name ?? $invoice->user_name; ?></h3>
                <div style="color: #6b7280; font-size: 0.9rem; margin-top: 4px;">
                    <?php echo $settings->address ?? ''; ?><br>
                    <?php echo $settings->phone ?? $invoice->user_email; ?>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; margin-bottom: 40px;">
            <div>
                <div style="color: #6b7280; font-size: 0.9rem; margin-bottom: 4px;">Bill To:</div>
                <div style="font-weight: 600; font-size: 1.1rem;"><?php echo $invoice->customer_name; ?></div>
                <div style="color: #4b5563; font-size: 0.9rem;">
                    <?php echo $invoice->customer_phone; ?><br>
                    <?php echo $invoice->customer_address; ?>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="color: #6b7280; font-size: 0.9rem;">Date:</div>
                <div style="font-weight: 600; margin-bottom: 8px;">
                    <?php echo date('d M Y', strtotime($invoice->invoice_date)); ?></div>

                <div style="color: #6b7280; font-size: 0.9rem;">Due Date:</div>
                <div style="font-weight: 600;"><?php echo date('d M Y', strtotime($invoice->due_date)); ?></div>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Price</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoice_items as $item): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 500;"><?php echo $item->item_name; ?></div>
                            <?php if (!empty($item->description)): ?>
                                <div style="font-size: 0.85rem; color: #6b7280; margin-top: 2px;">
                                    <?php echo $item->description; ?></div>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;"><?php echo $item->quantity; ?></td>
                        <td style="text-align: right;">
                            <?php echo $settings->currency_symbol ?? '$'; ?>    <?php echo number_format($item->price, 2); ?>
                        </td>
                        <td style="text-align: right;">
                            <?php echo $settings->currency_symbol ?? '$'; ?>    <?php echo number_format($item->total, 2); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total-section">
            <table class="total-table">
                <tr>
                    <td style="color: #6b7280;">Subtotal:</td>
                    <td style="text-align: right; font-weight: 600;">
                        <?php echo $settings->currency_symbol ?? '$'; ?><?php echo number_format($invoice->subtotal, 2); ?>
                    </td>
                </tr>
                <tr>
                    <td style="color: #6b7280;">Tax:</td>
                    <td style="text-align: right; font-weight: 600;">
                        <?php echo $settings->currency_symbol ?? '$'; ?><?php echo number_format($invoice->tax_total, 2); ?>
                    </td>
                </tr>
                <tr>
                    <td style="color: #111827; font-weight: 600; border-top: 1px solid #e5e7eb; padding-top: 12px;">
                        Total:</td>
                    <td style="text-align: right; border-top: 1px solid #e5e7eb; padding-top: 12px;"
                        class="grand-total">
                        <?php echo $settings->currency_symbol ?? '$'; ?><?php echo number_format($invoice->grand_total, 2); ?>
                    </td>
                </tr>
            </table>
        </div>

        <div class="actions">
            <button onclick="window.print()" class="btn">
                <i class="fas fa-print" style="margin-right: 8px;"></i> Print Invoice
            </button>
        </div>
    </div>

</body>

</html>