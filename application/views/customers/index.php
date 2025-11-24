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
        <div class="card-title">Customers</div>
        <a href="<?php echo site_url('customers/create'); ?>" class="btn btn-primary">
            <i class="fas fa-plus" style="margin-right: 8px;"></i> Add Customer
        </a>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>GSTIN</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($customers) > 0): ?>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?php echo $customer->name; ?></td>
                            <td><?php echo $customer->email; ?></td>
                            <td><?php echo $customer->phone; ?></td>
                            <td><?php echo $customer->gstin; ?></td>
                            <td>
                                <a href="<?php echo site_url('customers/ledger/' . $customer->id); ?>" class="btn btn-outline"
                                    style="padding: 6px 12px; font-size: 0.85rem;" title="View Ledger">
                                    <i class="fas fa-book"></i>
                                </a>
                                <a href="<?php echo site_url('customers/edit/' . $customer->id); ?>" class="btn btn-outline"
                                    style="padding: 6px 12px; font-size: 0.85rem;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?php echo site_url('customers/delete/' . $customer->id); ?>" class="btn btn-danger"
                                    style="padding: 6px 12px; font-size: 0.85rem;"
                                    onclick="return confirm('Are you sure you want to delete this customer?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-light);">No customers found. Add your
                            first customer!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>