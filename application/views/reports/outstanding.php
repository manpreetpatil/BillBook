<div class="card">
    <div class="card-header">
        <div class="card-title">Outstanding Payments</div>
        <a href="<?php echo site_url('reports'); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back
        </a>
    </div>

    <div style="background-color: #fee2e2; padding: 20px; border-radius: 8px; margin-bottom: 24px;">
        <div style="text-align: center;">
            <div style="color: #991b1b; font-size: 0.9rem; margin-bottom: 8px;">Total Outstanding Amount</div>
            <div style="font-size: 2.5rem; font-weight: 700; color: var(--danger-color);">
                ₹ <?php echo number_format($total_outstanding, 2); ?>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Contact</th>
                    <th>Invoice Date</th>
                    <th>Due Date</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($invoices) > 0): ?>
                    <?php foreach ($invoices as $invoice): ?>
                        <?php
                        $is_overdue = $invoice->due_date && strtotime($invoice->due_date) < time();
                        ?>
                        <tr style="<?php echo $is_overdue ? 'background-color: #fef3c7;' : ''; ?>">
                            <td>
                                <a href="<?php echo site_url('invoices/view/' . $invoice->id); ?>"
                                    style="color: var(--primary-color); text-decoration: none;">
                                    <?php echo $invoice->invoice_number; ?>
                                </a>
                            </td>
                            <td><?php echo $invoice->customer_name; ?></td>
                            <td>
                                <?php if ($invoice->phone): ?>
                                    <div><i class="fas fa-phone" style="font-size: 0.8rem;"></i> <?php echo $invoice->phone; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($invoice->email): ?>
                                    <div><i class="fas fa-envelope" style="font-size: 0.8rem;"></i> <?php echo $invoice->email; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d M Y', strtotime($invoice->invoice_date)); ?></td>
                            <td>
                                <?php if ($invoice->due_date): ?>
                                    <?php echo date('d M Y', strtotime($invoice->due_date)); ?>
                                    <?php if ($is_overdue): ?>
                                        <span class="badge badge-danger" style="margin-left: 4px;">Overdue</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td>₹ <?php echo number_format($invoice->grand_total, 2); ?></td>
                            <td>₹ <?php echo number_format($invoice->paid_amount, 2); ?></td>
                            <td style="font-weight: 700; color: var(--danger-color);">
                                ₹ <?php echo number_format($invoice->balance, 2); ?>
                            </td>
                            <td>
                                <?php
                                $badge_class = $invoice->status == 'Partial' ? 'badge-warning' : 'badge-danger';
                                ?>
                                <span class="badge <?php echo $badge_class; ?>"><?php echo $invoice->status; ?></span>
                            </td>
                            <td>
                                <a href="<?php echo site_url('payments/add/' . $invoice->id); ?>" class="btn btn-primary"
                                    style="padding: 6px 12px; font-size: 0.85rem;">
                                    <i class="fas fa-money-bill-wave"></i> Pay
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" style="text-align: center; color: var(--text-light);">
                            <i class="fas fa-check-circle"
                                style="font-size: 2rem; color: var(--success-color); margin-bottom: 8px;"></i>
                            <div>All invoices are paid! No outstanding payments.</div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>