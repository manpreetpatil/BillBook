<div class="card">
    <div class="card-header">
        <div class="card-title">Purchase Order #<?php echo $purchase->id; ?></div>
        <div>
            <a href="<?php echo site_url('purchases'); ?>" class="btn btn-outline">Back</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 40px;">
        <div>
            <h4 style="margin-bottom: 12px; color: var(--text-secondary);">Supplier Details</h4>
            <div style="font-weight: 600; font-size: 1.1rem; margin-bottom: 4px;">
                <?php echo $purchase->supplier_name; ?></div>
            <?php if ($purchase->address): ?>
                <div style="color: var(--text-secondary); margin-bottom: 4px;"><?php echo nl2br($purchase->address); ?>
                </div>
            <?php endif; ?>
            <?php if ($purchase->gstin): ?>
                <div style="color: var(--text-secondary);">GSTIN: <?php echo $purchase->gstin; ?></div>
            <?php endif; ?>
            <?php if ($purchase->email): ?>
                <div style="color: var(--text-secondary);"><?php echo $purchase->email; ?></div>
            <?php endif; ?>
        </div>
        <div class="text-right">
            <h4 style="margin-bottom: 12px; color: var(--text-secondary);">Order Details</h4>
            <div style="margin-bottom: 4px;">
                <span style="color: var(--text-secondary);">Date:</span>
                <span style="font-weight: 600;"><?php echo date('d M Y', strtotime($purchase->purchase_date)); ?></span>
            </div>
            <div style="margin-bottom: 4px;">
                <span style="color: var(--text-secondary);">Status:</span>
                <span style="font-weight: 600;"><?php echo $purchase->status; ?></span>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Batch / Expiry</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($purchase_items as $item): ?>
                    <tr>
                        <td><?php echo $item->item_name; ?></td>
                        <td>
                            <?php if ($item->batch_number): ?>
                                <div>Batch: <?php echo $item->batch_number; ?></div>
                            <?php endif; ?>
                            <?php if ($item->expiry_date): ?>
                                <div class="text-muted small">Exp: <?php echo date('d M Y', strtotime($item->expiry_date)); ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="text-right"><?php echo $item->quantity; ?></td>
                        <td class="text-right"><?php echo $currency_symbol; ?>
                            <?php echo number_format($item->unit_price, 2); ?></td>
                        <td class="text-right"><?php echo $currency_symbol; ?>     <?php echo number_format($item->total, 2); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right" style="font-weight: 700;">Total Amount</td>
                    <td class="text-right" style="font-weight: 700;"><?php echo $currency_symbol; ?>
                        <?php echo number_format($purchase->total_amount, 2); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <?php if ($purchase->notes): ?>
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-color);">
            <h4 style="margin-bottom: 8px; color: var(--text-secondary);">Notes</h4>
            <p><?php echo nl2br($purchase->notes); ?></p>
        </div>
    <?php endif; ?>
</div>