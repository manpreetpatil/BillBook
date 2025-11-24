<div class="card">
    <div class="card-header">
        <div class="card-title">Purchase Orders</div>
        <a href="<?php echo site_url('purchases/create'); ?>" class="btn btn-primary">
            <i class="fas fa-plus" style="margin-right: 8px;"></i> New Purchase Order
        </a>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Supplier</th>
                    <th>Status</th>
                    <th>Total Amount</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($purchases)): ?>
                    <?php foreach ($purchases as $purchase): ?>
                        <tr>
                            <td><?php echo date('d M Y', strtotime($purchase->purchase_date)); ?></td>
                            <td><?php echo $purchase->supplier_name; ?></td>
                            <td>
                                <?php
                                $badge_class = 'badge-info';
                                if ($purchase->status == 'Received')
                                    $badge_class = 'badge-success';
                                if ($purchase->status == 'Cancelled')
                                    $badge_class = 'badge-danger';
                                ?>
                                <span class="badge <?php echo $badge_class; ?>"><?php echo $purchase->status; ?></span>
                            </td>
                            <td><?php echo $currency_symbol; ?>         <?php echo number_format($purchase->total_amount, 2); ?></td>
                            <td class="text-right">
                                <a href="<?php echo site_url('purchases/view/' . $purchase->id); ?>"
                                    class="btn btn-outline btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No purchase orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>