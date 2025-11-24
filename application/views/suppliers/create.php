<div class="card">
    <div class="card-header">
        <div class="card-title">Add Supplier</div>
        <a href="<?php echo site_url('suppliers'); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back
        </a>
    </div>

    <form action="<?php echo site_url('suppliers/create'); ?>" method="post">
        <div class="form-group">
            <label class="form-label">Supplier Name *</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">GSTIN</label>
            <input type="text" name="gstin" class="form-control">
        </div>

        <div class="form-group">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="3"></textarea>
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save" style="margin-right: 8px;"></i> Save Supplier
            </button>
            <a href="<?php echo site_url('suppliers'); ?>" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>