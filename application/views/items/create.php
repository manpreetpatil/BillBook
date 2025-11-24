<div class="card">
    <div class="card-header">
        <div class="card-title">Add New Item</div>
        <a href="<?php echo site_url('items'); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back
        </a>
    </div>

    <form action="<?php echo site_url('items/create'); ?>" method="post">
        <div class="form-group">
            <label class="form-label">Item Name *</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Unit</label>
                <select name="unit" class="form-control">
                    <option value="pcs">Pieces (pcs)</option>
                    <option value="kg">Kilogram (kg)</option>
                    <option value="hr">Hour (hr)</option>
                    <option value="box">Box</option>
                    <option value="ltr">Liter (ltr)</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Price *</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">HSN/SAC Code</label>
                <input type="text" name="hsn_sac" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Tax Rate (%)</label>
                <input type="number" step="0.01" name="tax_rate" class="form-control" value="0">
            </div>

            <div class="form-group">
                <label class="form-label">Tax Type</label>
                <select name="tax_type" class="form-control">
                    <option value="exclusive">Exclusive (Price + Tax)</option>
                    <option value="inclusive">Inclusive (Tax included in Price)</option>
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save" style="margin-right: 8px;"></i> Save Item
            </button>
            <a href="<?php echo site_url('items'); ?>" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>