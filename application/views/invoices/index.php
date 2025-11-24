<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"
        style="background-color: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
        <?php echo $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">Invoices</div>
        <a href="<?php echo site_url('invoices/create'); ?>" class="btn btn-primary">
            <i class="fas fa-plus" style="margin-right: 8px;"></i> Create Invoice
        </a>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Due Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($invoices) > 0): ?>
                    <?php foreach ($invoices as $invoice): ?>
                        <tr>
                            <td><?php echo $invoice->invoice_number; ?></td>
                            <td><?php echo $invoice->customer_name; ?></td>
                            <td><?php echo date('d M Y', strtotime($invoice->invoice_date)); ?></td>
                            <td><?php echo $invoice->due_date ? date('d M Y', strtotime($invoice->due_date)) : 'N/A'; ?></td>
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
                                    style="padding: 6px 12px; font-size: 0.85rem;" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo site_url('invoices/print_invoice/' . $invoice->id); ?>"
                                    class="btn btn-outline" style="padding: 6px 12px; font-size: 0.85rem;" target="_blank"
                                    title="Print">
                                    <i class="fas fa-print"></i>
                                </a>
                                <a href="<?php echo site_url('invoices/delete/' . $invoice->id); ?>" class="btn btn-danger"
                                    style="padding: 6px 12px; font-size: 0.85rem;" onclick="return confirm('Are you sure?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-light);">No invoices found. Create your
                            first invoice!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>