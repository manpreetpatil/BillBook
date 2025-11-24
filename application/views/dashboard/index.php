<div class="grid">
    <div class="card stat-card">
        <div class="stat-label">Total Sales</div>
        <div class="stat-value"><?php echo $currency_symbol; ?> <?php echo number_format($stats['total_sales'], 2); ?>
        </div>
        <div class="badge badge-success" style="width: fit-content;">+0% this month</div>
    </div>
    <div class="card stat-card">
        <div class="stat-label">Due Amount</div>
        <div class="stat-value" style="color: var(--danger-color);"><?php echo $currency_symbol; ?>
            <?php echo number_format($stats['due_amount'], 2); ?>
        </div>
        <div class="badge badge-danger" style="width: fit-content;">Action Needed</div>
    </div>
    <div class="card stat-card">
        <div class="stat-label">Total Customers</div>
        <div class="stat-value"><?php echo $stats['total_customers']; ?></div>
        <div class="badge badge-info" style="width: fit-content;">Active</div>
    </div>
    <div class="card stat-card">
        <div class="stat-label">Total Invoices</div>
        <div class="stat-value"><?php echo $stats['total_invoices']; ?></div>
        <div class="badge badge-warning" style="width: fit-content;">All Time</div>
    </div>
</div>

<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <div class="card-title">Recent Invoices</div>
        <a href="<?php echo site_url('invoices/create'); ?>" class="btn btn-primary">
            <i class="fas fa-plus" style="margin-right: 8px;"></i> New Invoice
        </a>
    </div>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($recent_invoices) && count($recent_invoices) > 0): ?>
                    <?php foreach ($recent_invoices as $invoice): ?>
                        <tr>
                            <td>
                                <a href="<?php echo site_url('invoices/view/' . $invoice->id); ?>"
                                    style="color: var(--primary-color); text-decoration: none;">
                                    <?php echo $invoice->invoice_number; ?>
                                </a>
                            </td>
                            <td><?php echo $invoice->customer_name; ?></td>
                            <td><?php echo date('d M Y', strtotime($invoice->invoice_date)); ?></td>
                            <td><?php echo $currency_symbol; ?>         <?php echo number_format($invoice->grand_total, 2); ?></td>
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
                            <td>
                                <a href="<?php echo site_url('invoices/view/' . $invoice->id); ?>" class="btn btn-outline"
                                    style="padding: 6px 12px; font-size: 0.85rem;">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-light);">No recent invoices found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>