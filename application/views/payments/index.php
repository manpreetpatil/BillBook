<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"
        style="background-color: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
        <?php echo $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">Payments</div>
        <a href="<?php echo site_url('payments/add'); ?>" class="btn btn-primary">
            <i class="fas fa-plus" style="margin-right: 8px;"></i> Add Payment
        </a>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Transaction ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($payments) > 0): ?>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?php echo date('d M Y', strtotime($payment->payment_date)); ?></td>
                            <td>
                                <a href="<?php echo site_url('invoices/view/' . $payment->invoice_id); ?>"
                                    style="color: var(--primary-color); text-decoration: none;">
                                    <?php echo $payment->invoice_number; ?>
                                </a>
                            </td>
                            <td><?php echo $payment->customer_name; ?></td>
                            <td>₹ <?php echo number_format($payment->amount, 2); ?></td>
                            <td><?php echo $payment->payment_method; ?></td>
                            <td><?php echo $payment->transaction_id ?: 'N/A'; ?></td>
                            <td>
                                <a href="<?php echo site_url('payments/delete/' . $payment->id); ?>" class="btn btn-danger"
                                    style="padding: 6px 12px; font-size: 0.85rem;" onclick="return confirm('Are you sure?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-light);">No payments recorded yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>