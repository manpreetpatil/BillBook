<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div class="card-title">Sales Report</div>
        <a href="<?php echo site_url('reports/export_csv?start_date=' . $start_date . '&end_date=' . $end_date); ?>"
            class="btn btn-outline btn-sm">
            <i class="fas fa-file-csv" style="margin-right: 8px;"></i> Export CSV
        </a>
    </div>

    <form method="get" action="<?php echo site_url('reports'); ?>" style="margin-bottom: 24px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 12px; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter" style="margin-right: 8px;"></i> Filter
            </button>
        </div>
    </form>

    <div class="grid" style="margin-bottom: 24px;">
        <div class="card stat-card" style="background-color: #eff6ff;">
            <div class="stat-label">Total Sales</div>
            <div class="stat-value" style="color: var(--primary-color);"><?php echo $currency_symbol; ?>
                <?php echo number_format($total_sales, 2); ?>
            </div>
        </div>
        <div class="card stat-card" style="background-color: #f0fdf4;">
            <div class="stat-label">Total Paid</div>
            <div class="stat-value" style="color: var(--success-color);"><?php echo $currency_symbol; ?>
                <?php echo number_format($total_paid, 2); ?>
            </div>
        </div>
        <div class="card stat-card" style="background-color: #fef3c7;">
            <div class="stat-label">Total Tax</div>
            <div class="stat-value" style="color: var(--warning-color);"><?php echo $currency_symbol; ?>
                <?php echo number_format($total_tax, 2); ?>
            </div>
        </div>
        <div class="card stat-card" style="background-color: #fee2e2;">
            <div class="stat-label">Total Due</div>
            <div class="stat-value" style="color: var(--danger-color);"><?php echo $currency_symbol; ?>
                <?php echo number_format($total_due, 2); ?>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Tax</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($invoices) > 0): ?>
                    <?php foreach ($invoices as $invoice): ?>
                        <tr>
                            <td>
                                <a href="<?php echo site_url('invoices/view/' . $invoice->id); ?>"
                                    style="color: var(--primary-color); text-decoration: none;">
                                    <?php echo $invoice->invoice_number; ?>
                                </a>
                            </td>
                            <td><?php echo date('d M Y', strtotime($invoice->invoice_date)); ?></td>
                            <td><?php echo $invoice->customer_name; ?></td>
                            <td><?php echo $currency_symbol; ?>         <?php echo number_format($invoice->grand_total, 2); ?></td>
                            <td><?php echo $currency_symbol; ?>         <?php echo number_format($invoice->tax_total, 2); ?></td>
                            <td><?php echo $currency_symbol; ?>         <?php echo number_format($invoice->paid_amount, 2); ?></td>
                            <td><?php echo $currency_symbol; ?>
                                <?php echo number_format($invoice->grand_total - $invoice->paid_amount, 2); ?></td>
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
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-light);">No invoices found for selected
                            period.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 24px; display: flex; gap: 12px;">

        <a href="<?php echo site_url('reports/gst_report?start_date=' . $start_date . '&end_date=' . $end_date); ?>"
            class="btn btn-outline">
            <i class="fas fa-file-invoice" style="margin-right: 8px;"></i> GST Report
        </a>
        <a href="<?php echo site_url('reports/outstanding'); ?>" class="btn btn-outline">
            <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i> Outstanding Payments
        </a>
    </div>
</div>