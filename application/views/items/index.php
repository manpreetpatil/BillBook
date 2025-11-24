<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"
        style="background-color: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
        <?php echo $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-error"
        style="background-color: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
        <?php echo $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">Items / Products</div>
        <a href="<?php echo site_url('items/create'); ?>" class="btn btn-primary">
            <i class="fas fa-plus" style="margin-right: 8px;"></i> Add Item
        </a>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Unit</th>
                    <th>Price</th>
                    <th>HSN/SAC</th>
                    <th>Tax Rate</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($items) > 0): ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?php echo $item->name; ?></td>
                            <td><?php echo $item->description; ?></td>
                            <td><?php echo $item->unit; ?></td>
                            <td>₹ <?php echo number_format($item->price, 2); ?></td>
                            <td><?php echo $item->hsn_sac; ?></td>
                            <td><?php echo $item->tax_rate; ?>%</td>
                            <td>
                                <a href="<?php echo site_url('items/edit/' . $item->id); ?>" class="btn btn-outline"
                                    style="padding: 6px 12px; font-size: 0.85rem;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?php echo site_url('items/delete/' . $item->id); ?>" class="btn btn-danger"
                                    style="padding: 6px 12px; font-size: 0.85rem;"
                                    onclick="return confirm('Are you sure you want to delete this item?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-light);">No items found. Add your first
                            item!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>