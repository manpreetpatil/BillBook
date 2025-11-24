<div class="card">
    <div class="card-header">
        <div class="card-title">Customer Ledger - <?php echo $customer->name; ?></div>
        <a href="<?php echo site_url('customers'); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back
        </a>
    </div>

    <div style="padding: 20px; background-color: #f8fafc; border-radius: 8px; margin-bottom: 20px;">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
            <div>
                <div style="color: var(--text-light); font-size: 0.9rem;">Email</div>
                <div style="font-weight: 600;"><?php echo $customer->email ?: 'N/A'; ?></div>
            </div>
            <div>
                <div style="color: var(--text-light); font-size: 0.9rem;">Phone</div>
                <div style="font-weight: 600;"><?php echo $customer->phone ?: 'N/A'; ?></div>
            </div>
            <div>
                <div style="color: var(--text-light); font-size: 0.9rem;">GSTIN</div>
                <div style="font-weight: 600;"><?php echo $customer->gstin ?: 'N/A'; ?></div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Date</th>
                    <th>Total Amount</th>
                    <th>Paid Amount</th>
                    <th>Balance</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($ledger) > 0): ?>
                    <?php
                    $total_amount = 0;
                    $total_paid = 0;
                    foreach ($ledger as $invoice):
                        $balance = $invoice->grand_total - $invoice->paid_amount;
                        $total_amount += $invoice->grand_total;
                        $total_paid += $invoice->paid_amount;
                        ?>
                        <tr>
                            <td><?php echo $invoice->invoice_number; ?></td>
                            <td><?php echo date('d M Y', strtotime($invoice->invoice_date)); ?></td>
                            <td>₹ <?php echo number_format($invoice->grand_total, 2); ?></td>
                            <td>₹ <?php echo number_format($invoice->paid_amount, 2); ?></td>
                            <td>₹ <?php echo number_format($balance, 2); ?></td>
                            <td>
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
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr style="font-weight: 700; background-color: #f8fafc;">
                        <td colspan="2">TOTAL</td>
                        <td>₹ <?php echo number_format($total_amount, 2); ?></td>
                        <td>₹ <?php echo number_format($total_paid, 2); ?></td>
                        <td>₹ <?php echo number_format($total_amount - $total_paid, 2); ?></td>
                        <td></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-light);">No transactions found for this
                            customer.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>