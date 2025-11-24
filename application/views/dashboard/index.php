<div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px;">
    <!-- Total Sales -->
    <div class="card stat-card">
        <div class="stat-label">Total Sales</div>
        <div class="stat-value"><?php echo $currency_symbol; ?> <?php echo number_format($stats['total_sales'], 2); ?></div>
        <div class="badge badge-success" style="width: fit-content;">Income</div>
    </div>

    <!-- Total Expenses -->
    <div class="card stat-card">
        <div class="stat-label">Total Expenses</div>
        <div class="stat-value" style="color: var(--danger-color);"><?php echo $currency_symbol; ?> <?php echo number_format($stats['total_expenses'], 2); ?></div>
        <div class="badge badge-danger" style="width: fit-content;">Outflow</div>
    </div>

    <!-- Net Profit -->
    <div class="card stat-card">
        <div class="stat-label">Net Profit</div>
        <div class="stat-value" style="color: <?php echo $stats['net_profit'] >= 0 ? 'var(--success-color)' : 'var(--danger-color)'; ?>;">
            <?php echo $currency_symbol; ?> <?php echo number_format($stats['net_profit'], 2); ?>
        </div>
        <div class="badge <?php echo $stats['net_profit'] >= 0 ? 'badge-success' : 'badge-danger'; ?>" style="width: fit-content;">
            <?php echo $stats['net_profit'] >= 0 ? 'Profit' : 'Loss'; ?>
        </div>
    </div>

    <!-- Due Amount -->
    <div class="card stat-card">
        <div class="stat-label">Due Amount</div>
        <div class="stat-value" style="color: var(--warning-color);"><?php echo $currency_symbol; ?> <?php echo number_format($stats['due_amount'], 2); ?></div>
        <div class="badge badge-warning" style="width: fit-content;">Pending</div>
    </div>

    <!-- Low Stock -->
    <div class="card stat-card">
        <div class="stat-label">Low Stock Items</div>
        <div class="stat-value"><?php echo $stats['low_stock_count']; ?></div>
        <div class="badge <?php echo $stats['low_stock_count'] > 0 ? 'badge-danger' : 'badge-success'; ?>" style="width: fit-content;">
            <?php echo $stats['low_stock_count'] > 0 ? 'Restock Needed' : 'Good'; ?>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-top: 24px;">
    <!-- Recent Invoices -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Recent Invoices</div>
            <a href="<?php echo site_url('invoices/create'); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> New
            </a>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($recent_invoices) && count($recent_invoices) > 0): ?>
                        <?php foreach ($recent_invoices as $invoice): ?>
                            <tr>
                                <td><a href="<?php echo site_url('invoices/view/' . $invoice->id); ?>"><?php echo $invoice->invoice_number; ?></a></td>
                                <td><?php echo $invoice->customer_name; ?></td>
                                <td><?php echo $currency_symbol; ?> <?php echo number_format($invoice->grand_total, 2); ?></td>
                                <td>
                                    <?php
                                    $badge_class = 'badge-info';
                                    if ($invoice->status == 'Paid') $badge_class = 'badge-success';
                                    elseif ($invoice->status == 'Cancelled') $badge_class = 'badge-danger';
                                    elseif ($invoice->status == 'Partial') $badge_class = 'badge-warning';
                                    ?>
                                    <span class="badge <?php echo $badge_class; ?>"><?php echo $invoice->status; ?></span>
                                </td>
                                <td>
                                    <a href="<?php echo site_url('invoices/view/' . $invoice->id); ?>" class="btn btn-outline btn-sm"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">No recent invoices.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Expenses -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Recent Expenses</div>
            <a href="<?php echo site_url('expenses/create'); ?>" class="btn btn-outline btn-sm">
                <i class="fas fa-plus"></i> New
            </a>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($recent_expenses) && count($recent_expenses) > 0): ?>
                        <?php foreach ($recent_expenses as $expense): ?>
                            <tr>
                                <td><?php echo $expense->category_name; ?></td>
                                <td style="font-weight: 600; color: var(--danger-color);"><?php echo $currency_symbol; ?> <?php echo number_format($expense->amount, 2); ?></td>
                                <td><?php echo date('d M', strtotime($expense->expense_date)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center">No recent expenses.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>