<div class="card">
    <div class="card-header">
        <div class="card-title">Invoice <?php echo $invoice->invoice_number; ?></div>
        <div style="display: flex; gap: 8px;">
            <a href="<?php echo site_url('invoices/print_invoice/' . $invoice->id); ?>" class="btn btn-primary"
                target="_blank">
                <i class="fas fa-print" style="margin-right: 8px;"></i> Print
            </a>
            <a href="<?php echo site_url('payments/add/' . $invoice->id); ?>" class="btn btn-primary">
                <i class="fas fa-money-bill-wave" style="margin-right: 8px;"></i> Add Payment
            </a>
            <a href="<?php echo site_url('invoices'); ?>" class="btn btn-outline">
                <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back
            </a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
        <div>
            <h3 style="margin-bottom: 12px;">Customer Details</h3>
            <div style="background-color: #f8fafc; padding: 16px; border-radius: 8px;">
                <div style="margin-bottom: 8px;"><strong><?php echo $invoice->customer_name; ?></strong></div>
                <div style="color: var(--text-light); font-size: 0.9rem;">
                    <?php if ($invoice->email): ?>
                        <div><i class="fas fa-envelope" style="width: 20px;"></i> <?php echo $invoice->email; ?></div>
                    <?php endif; ?>
                    <?php if ($invoice->phone): ?>
                        <div><i class="fas fa-phone" style="width: 20px;"></i> <?php echo $invoice->phone; ?></div>
                    <?php endif; ?>
                    <?php if ($invoice->address): ?>
                        <div><i class="fas fa-map-marker-alt" style="width: 20px;"></i>
                            <?php echo nl2br($invoice->address); ?></div>
                    <?php endif; ?>
                    <?php if ($invoice->gstin): ?>
                        <div><i class="fas fa-file-alt" style="width: 20px;"></i> GSTIN: <?php echo $invoice->gstin; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div>
            <h3 style="margin-bottom: 12px;">Invoice Details</h3>
            <div style="background-color: #f8fafc; padding: 16px; border-radius: 8px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <div style="color: var(--text-light); font-size: 0.85rem;">Invoice Date</div>
                        <div style="font-weight: 600;"><?php echo date('d M Y', strtotime($invoice->invoice_date)); ?>
                        </div>
                    </div>
                    <div>
                        <div style="color: var(--text-light); font-size: 0.85rem;">Due Date</div>
                        <div style="font-weight: 600;">
                            <?php echo $invoice->due_date ? date('d M Y', strtotime($invoice->due_date)) : 'N/A'; ?>
                        </div>
                    </div>
                    <div>
                        <div style="color: var(--text-light); font-size: 0.85rem;">Status</div>
                        <div>
                            <?php
                            $badge_class = 'badge-info';
                            if ($invoice->status == 'Paid')
                                $badge_class = 'badge-success';
                            elseif ($invoice->status == 'Cancelled')
                                $badge_class = 'badge-danger';
                            elseif ($invoice->status == 'Partial')
                                $badge_class = 'badge-warning';
                            ?>
                            <span class="badge <?php echo $badge_class; ?>"><?php echo $invoice->status; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h3 style="margin-bottom: 12px;">Items</h3>
    <div class="table-container" style="margin-bottom: 24px;">
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Tax Rate</th>
                    <th>Tax Amount</th>
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
    </div>

    <div style="max-width: 400px; margin-left: auto; background-color: #f8fafc; padding: 20px; border-radius: 8px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
            <span>Subtotal:</span>
            <span><?php echo $currency_symbol; ?> <?php echo number_format($invoice->subtotal, 2); ?></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
            <span>Tax:</span>
            <span><?php echo $currency_symbol; ?> <?php echo number_format($invoice->tax_total, 2); ?></span>
        </div>
        <div
            style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: 700; padding-top: 12px; border-top: 2px solid var(--border-color);">
            <span>Grand Total:</span>
            <span><?php echo $currency_symbol; ?> <?php echo number_format($invoice->grand_total, 2); ?></span>
        </div>
    </div>

    <?php if ($invoice->notes): ?>
        <div style="margin-top: 24px;">
            <h3 style="margin-bottom: 12px;">Notes</h3>
            <div style="background-color: #f8fafc; padding: 16px; border-radius: 8px;">
                <?php echo nl2br($invoice->notes); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($payments) && count($payments) > 0): ?>
        <div style="margin-top: 24px;">
            <h3 style="margin-bottom: 12px;">Payment History</h3>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Transaction ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_paid = 0;
                        foreach ($payments as $payment):
                            $total_paid += $payment->amount;
                            ?>
                            <tr>
                                <td><?php echo date('d M Y', strtotime($payment->payment_date)); ?></td>
                                <td><?php echo $currency_symbol; ?>         <?php echo number_format($payment->amount, 2); ?></td>
                                <td><?php echo $payment->payment_method; ?></td>
                                <td><?php echo $payment->transaction_id ?: 'N/A'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr style="font-weight: 700; background-color: #f8fafc;">
                            <td>Total Paid</td>
                            <td><?php echo $currency_symbol; ?>     <?php echo number_format($total_paid, 2); ?></td>
                            <td colspan="2">Balance: <?php echo $currency_symbol; ?>
                                <?php echo number_format($invoice->grand_total - $total_paid, 2); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>