<div class="card">
    <div class="card-header">
        <div class="card-title">Expiry Report</div>
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
                    <th>Item Name</th>
                    <th>Batch Number</th>
                    <th>Expiry Date</th>
                    <th class="text-right">Quantity</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($batches)): ?>
                    <?php foreach ($batches as $batch): ?>
                        <?php
                        $expiry = strtotime($batch->expiry_date);
                        $today = time();
                        $diff = $expiry - $today;
                        $days_until_expiry = round($diff / (60 * 60 * 24));

                        $status_class = 'badge-success';
                        $status_text = 'Good';

                        if ($days_until_expiry < 0) {
                            $status_class = 'badge-danger';
                            $status_text = 'Expired';
                        } elseif ($days_until_expiry <= 30) {
                            $status_class = 'badge-warning';
                            $status_text = 'Expiring Soon (' . $days_until_expiry . ' days)';
                        }
                        ?>
                        <tr>
                            <td><?php echo $batch->item_name; ?></td>
                            <td><?php echo $batch->batch_number; ?></td>
                            <td><?php echo date('d M Y', $expiry); ?></td>
                            <td class="text-right"><?php echo number_format($batch->quantity, 2); ?></td>
                            <td>
                                <span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No batches found with expiry dates.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>