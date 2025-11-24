<div class="grid" style="grid-template-columns: 1fr 2fr; align-items: start;">
    <!-- Add Category Form -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Add Category</div>
        </div>
        <form action="<?php echo site_url('expenses/categories'); ?>" method="post">
            <div class="form-group">
                <label class="form-label">Category Name *</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="2"></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Add Category</button>
        </form>
    </div>

    <!-- Categories List -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Expense Categories</div>
            <a href="<?php echo site_url('expenses'); ?>" class="btn btn-outline">
                <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back to Expenses
            </a>
        </div>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td style="font-weight: 500;"><?php echo $cat->name; ?></td>
                                <td class="text-muted"><?php echo $cat->description; ?></td>
                                <td class="text-right">
                                    <a href="<?php echo site_url('expenses/delete_category/' . $cat->id); ?>"
                                        class="btn btn-danger" style="padding: 4px 8px; font-size: 0.8rem;"
                                        onclick="return confirm('Are you sure? This will not delete expenses linked to this category.')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">No categories found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>