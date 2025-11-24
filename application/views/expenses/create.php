<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div class="card-title">Record New Expense</div>
        <a href="<?php echo site_url('expenses'); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back
        </a>
    </div>

    <form action="<?php echo site_url('expenses/create'); ?>" method="post">
        <div class="grid" style="grid-template-columns: 1fr 1fr;">
            <div class="form-group">
                <label class="form-label">Category *</label>
                <div style="display: flex; gap: 8px;">
                    <select name="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat->id; ?>"><?php echo $cat->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <a href="<?php echo site_url('expenses/categories'); ?>" class="btn btn-outline"
                        title="Add Category">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Date *</label>
                <input type="date" name="expense_date" class="form-control" value="<?php echo date('Y-m-d'); ?>"
                    required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Amount *</label>
            <div style="position: relative;">
                <span
                    style="position: absolute; left: 12px; top: 10px; color: var(--text-light);"><?php echo $currency_symbol; ?></span>
                <input type="number" name="amount" class="form-control" style="padding-left: 32px;" step="0.01"
                    required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Reference No. (Optional)</label>
            <input type="text" name="reference_no" class="form-control" placeholder="Bill No, Receipt No, etc.">
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"
                placeholder="Enter details about the expense..."></textarea>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 32px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save" style="margin-right: 8px;"></i> Save Expense
            </button>
            <a href="<?php echo site_url('expenses'); ?>" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>