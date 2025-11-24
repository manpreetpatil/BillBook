<div class="card">
    <div class="card-header">
        <div class="card-title">Suppliers</div>
        <a href="<?php echo site_url('suppliers/create'); ?>" class="btn btn-primary">
            <i class="fas fa-plus" style="margin-right: 8px;"></i> Add Supplier
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
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>GSTIN</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($suppliers)): ?>
                    <?php foreach ($suppliers as $supplier): ?>
                        <tr>
                            <td>
                                <div style="font-weight: 500;"><?php echo $supplier->name; ?></div>
                                <div class="text-muted small"><?php echo $supplier->address; ?></div>
                            </td>
                            <td><?php echo $supplier->email; ?></td>
                            <td><?php echo $supplier->phone; ?></td>
                            <td><?php echo $supplier->gstin; ?></td>
                            <td class="text-right">
                                <a href="<?php echo site_url('suppliers/edit/' . $supplier->id); ?>"
                                    class="btn btn-outline btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?php echo site_url('suppliers/delete/' . $supplier->id); ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this supplier?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No suppliers found. Add your first supplier!
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>