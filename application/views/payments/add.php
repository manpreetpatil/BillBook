<div class="card">
    <div class="card-header">
        <div class="card-title">Add Payment</div>
        <a href="<?php echo site_url('payments'); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back
        </a>
    </div>

    <form action="<?php echo site_url('payments/add'); ?>" method="post">
        <?php if (isset($invoice)): ?>
            <input type="hidden" name="invoice_id" value="<?php echo $invoice->id; ?>">

            <div style="background-color: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 24px;">
                <h3 style="margin-bottom: 12px;">Invoice Details</h3>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
                    <div>
                        <div style="color: var(--text-light); font-size: 0.85rem;">Invoice Number</div>
                        <div style="font-weight: 600;"><?php echo $invoice->invoice_number; ?></div>
                    </div>
                    <div>
                        <div style="color: var(--text-light); font-size: 0.85rem;">Customer</div>
                        <div style="font-weight: 600;"><?php echo $invoice->customer_name; ?></div>
                    </div>
                    <div>
                        <div style="color: var(--text-light); font-size: 0.85rem;">Total Amount</div>
                        <div style="font-weight: 600;">₹ <?php echo number_format($invoice->grand_total, 2); ?></div>
                    </div>
                    <div>
                        <div style="color: var(--text-light); font-size: 0.85rem;">Status</div>
                        <div>
                            <?php
                            $badge_class = 'badge-info';
                            if ($invoice->status == 'Paid')
                                $badge_class = 'badge-success';
                            elseif ($invoice->status == 'Partial')
                                $badge_class = 'badge-warning';
                            ?>
                            <span class="badge <?php echo $badge_class; ?>"><?php echo $invoice->status; ?></span>
                        </div>
                    </div>
                    <div>
                        <div style="color: var(--text-light); font-size: 0.85rem;">Balance Due</div>
                        <div style="font-weight: 600; color: var(--danger-color);">₹
                            <?php echo number_format($balance, 2); ?></div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="form-group">
                <label class="form-label">Select Invoice *</label>
                <select name="invoice_id" class="form-control" required>
                    <option value="">Select Invoice</option>
                    <?php if (isset($invoices)): ?>
                        <?php foreach ($invoices as $inv): ?>
                            <option value="<?php echo $inv->id; ?>">
                                <?php echo $inv->invoice_number; ?> - ₹ <?php echo number_format($inv->grand_total, 2); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Payment Date *</label>
                <input type="date" name="payment_date" class="form-control" value="<?php echo date('Y-m-d'); ?>"
                    required>
            </div>

            <div class="form-group">
                <label class="form-label">Amount *</label>
                <input type="number" step="0.01" name="amount" class="form-control"
                    value="<?php echo isset($balance) ? $balance : ''; ?>" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Payment Method</label>
                <select name="payment_method" class="form-control">
                    <option value="Cash">Cash</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="UPI">UPI</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="Cheque">Cheque</option>
                    <option value="Online">Online</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Transaction ID</label>
                <input type="text" name="transaction_id" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3"></textarea>
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save" style="margin-right: 8px;"></i> Record Payment
            </button>
            <a href="<?php echo isset($invoice) ? site_url('invoices/view/' . $invoice->id) : site_url('payments'); ?>"
                class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>