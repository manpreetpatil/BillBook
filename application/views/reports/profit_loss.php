<div class="card">
    <div class="card-header">
        <div class="card-title">Profit & Loss Report</div>
        <div>
            <button class="btn btn-outline" onclick="window.print()">
                <i class="fas fa-print" style="margin-right: 8px;"></i> Print
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div style="background-color: #f8fafc; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
        <form action="<?php echo site_url('reports/profit_loss'); ?>" method="get"
            style="display: flex; gap: 16px; align-items: end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 150px;">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
            </div>
            <button type="submit" class="btn btn-primary" style="height: 42px;">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>
    </div>

    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
        <!-- Income Card -->
        <div class="card" style="border-left: 4px solid var(--success-color);">
            <div class="stat-label">Total Income (Sales)</div>
            <div class="stat-value" style="color: var(--success-color);">
                <?php echo $currency_symbol; ?> <?php echo number_format($total_income, 2); ?>
            </div>
            <div class="text-muted small">Total invoiced amount</div>
        </div>

        <!-- Purchases Card -->
        <div class="card" style="border-left: 4px solid var(--warning-color);">
            <div class="stat-label">Total Purchases (Inventory)</div>
            <div class="stat-value" style="color: var(--warning-color);">
                <?php echo $currency_symbol; ?> <?php echo number_format($total_purchases, 2); ?>
            </div>
            <div class="text-muted small">Stock purchases</div>
        </div>

        <!-- Expenses Card -->
        <div class="card" style="border-left: 4px solid var(--danger-color);">
            <div class="stat-label">Total Expenses (Overhead)</div>
            <div class="stat-value" style="color: var(--danger-color);">
                <?php echo $currency_symbol; ?> <?php echo number_format($total_expenses, 2); ?>
            </div>
            <div class="text-muted small">Operational expenses</div>
        </div>
    </div>

    <!-- Net Profit Summary -->
    <div class="card" style="text-align: center; padding: 40px;">
        <h3 style="color: var(--text-light); margin-bottom: 16px;">Net Profit / Loss</h3>
        <?php
        $profit_color = $net_profit >= 0 ? 'var(--success-color)' : 'var(--danger-color)';
        $profit_icon = $net_profit >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
        ?>
        <div style="font-size: 3rem; font-weight: 800; color: <?php echo $profit_color; ?>; margin-bottom: 8px;">
            <?php echo $currency_symbol; ?> <?php echo number_format(abs($net_profit), 2); ?>
        </div>
        <div style="font-size: 1.2rem; font-weight: 600; color: <?php echo $profit_color; ?>;">
            <i class="fas <?php echo $profit_icon; ?>"></i> <?php echo $net_profit >= 0 ? 'Net Profit' : 'Net Loss'; ?>
        </div>
        <p class="text-muted" style="margin-top: 16px;">
            (Income - Purchases - Expenses)
        </p>
    </div>
</div>