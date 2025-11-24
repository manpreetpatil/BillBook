<div class="card">
    <div class="card-header">
        <div class="card-title">GST Report</div>
        <a href="<?php echo site_url('reports'); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back
        </a>
    </div>

    <form method="get" action="<?php echo site_url('reports/gst_report'); ?>" style="margin-bottom: 24px;">
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

    <div style="display: flex; gap: 12px; margin-bottom: 16px;">
        <h3 style="margin-bottom: 0;">Tax Summary</h3>
        <a href="<?php echo site_url('reports/gstr3b_report?start_date=' . $start_date . '&end_date=' . $end_date); ?>"
            class="btn btn-outline btn-sm">
            View GSTR-3B Summary
        </a>
        <a href="<?php echo site_url('reports/gst_report_csv?start_date=' . $start_date . '&end_date=' . $end_date); ?>"
            class="btn btn-primary btn-sm">
            <i class="fas fa-download" style="margin-right: 8px;"></i> Download CSV
        </a>
    </div>
    <div class="table-container" style="margin-bottom: 32px;">
        <table class="table">
            <thead>
                <tr>
                    <th>Tax Rate</th>
                    <th>Taxable Amount</th>
                    <th>CGST</th>
                    <th>SGST</th>
                    <th>IGST</th>
                    <th>Total Tax</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($tax_summary) > 0): ?>
                    <?php foreach ($tax_summary as $rate => $data): ?>
                        <tr>
                            <td><?php echo $rate; ?>%</td>
                            <td><?php echo $currency_symbol; ?>         <?php echo number_format($data['taxable_amount'], 2); ?></td>
                            <td><?php echo $currency_symbol; ?>         <?php echo number_format($data['cgst_amount'], 2); ?></td>
                            <td><?php echo $currency_symbol; ?>         <?php echo number_format($data['sgst_amount'], 2); ?></td>
                            <td><?php echo $currency_symbol; ?>         <?php echo number_format($data['igst_amount'], 2); ?></td>
                            <td><?php echo $currency_symbol; ?>         <?php echo number_format($data['tax_amount'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr style="font-weight: 700; background-color: #f8fafc;">
                        <td>TOTAL</td>
                        <td><?php echo $currency_symbol; ?>     <?php echo number_format($total_taxable, 2); ?></td>
                        <td><?php echo $currency_symbol; ?>     <?php echo number_format($total_cgst, 2); ?></td>
                        <td><?php echo $currency_symbol; ?>     <?php echo number_format($total_sgst, 2); ?></td>
                        <td><?php echo $currency_symbol; ?>     <?php echo number_format($total_igst, 2); ?></td>
                        <td><?php echo $currency_symbol; ?>     <?php echo number_format($total_tax, 2); ?></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-light);">No tax data for selected
                            period.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <h3 style="margin-bottom: 16px;">Detailed Transactions (GSTR-1)</h3>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>GSTIN</th>
                    <th>Place of Supply</th>
                    <th>Taxable</th>
                    <th>Rate</th>
                    <th>CGST</th>
                    <th>SGST</th>
                    <th>IGST</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($items) > 0): ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?php echo date('d M Y', strtotime($item->invoice_date)); ?></td>
                            <td><?php echo $item->invoice_number; ?></td>
                            <td><?php echo $item->customer_name; ?></td>
                            <td><?php echo $item->gstin ?: 'N/A'; ?></td>
                            <td><?php echo $item->customer_state ?: 'N/A'; ?></td>
                            <td><?php echo $currency_symbol; ?>         <?php echo number_format($item->quantity * $item->price, 2); ?>
                            </td>
                            <td><?php echo $item->tax_rate; ?>%</td>
                            <td><?php echo $currency_symbol; ?>         <?php echo number_format($item->cgst_amount, 2); ?></td>
                            <td><?php echo $currency_symbol; ?>         <?php echo number_format($item->sgst_amount, 2); ?></td>
                            <td><?php echo $currency_symbol; ?>         <?php echo number_format($item->igst_amount, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" style="text-align: center; color: var(--text-light);">No transactions found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>