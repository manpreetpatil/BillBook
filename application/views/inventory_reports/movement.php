<div class="card">
    <div class="card-header">
        <div class="card-title">Stock Movement History</div>
        <div>
            <button class="btn btn-outline" onclick="window.print()">
                <i class="fas fa-print" style="margin-right: 8px;"></i> Print
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Item Name</th>
                    <th>Type</th>
                    <th class="text-right">Quantity</th>
                    <th>Reference</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?php echo date('d M Y', strtotime($log->date)); ?></td>
                            <td><?php echo $log->item_name; ?></td>
                            <td>
                                <?php if ($log->type == 'IN'): ?>
                                    <span class="badge badge-success">IN</span>
                                <?php elseif ($log->type == 'OUT'): ?>
                                    <span class="badge badge-danger">OUT</span>
                                <?php else: ?>
                                    <span class="badge badge-info">ADJUST</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-right"><?php echo number_format($log->quantity, 2); ?></td>
                            <td>
                                <?php if ($log->reference_type == 'Purchase'): ?>
                                    <a href="<?php echo site_url('purchases/view/' . $log->reference_id); ?>">PO
                                        #<?php echo $log->reference_id; ?></a>
                                <?php elseif ($log->reference_type == 'Invoice'): ?>
                                    <a href="<?php echo site_url('invoices/view/' . $log->reference_id); ?>">INV
                                        #<?php echo $log->reference_id; ?></a>
                                <?php else: ?>
                                    <?php echo $log->reference_type; ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted small"><?php echo $log->notes; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No movement history found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>