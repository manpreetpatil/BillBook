<div class="card">
    <div class="card-header">
        <div class="card-title">Edit Item</div>
        <a href="<?php echo site_url('items'); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back
        </a>
    </div>

    <form action="<?php echo site_url('items/edit/' . $item->id); ?>" method="post">
        <div class="form-group">
            <label class="form-label">Item Name *</label>
            <input type="text" name="name" class="form-control" value="<?php echo $item->name; ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"><?php echo $item->description; ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Unit</label>
                <select name="unit" class="form-control">
                    <option value="pcs" <?php echo ($item->unit == 'pcs') ? 'selected' : ''; ?>>Pieces (pcs)</option>
                    <option value="kg" <?php echo ($item->unit == 'kg') ? 'selected' : ''; ?>>Kilogram (kg)</option>
                    <option value="hr" <?php echo ($item->unit == 'hr') ? 'selected' : ''; ?>>Hour (hr)</option>
                    <option value="box" <?php echo ($item->unit == 'box') ? 'selected' : ''; ?>>Box</option>
                    <option value="ltr" <?php echo ($item->unit == 'ltr') ? 'selected' : ''; ?>>Liter (ltr)</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Price *</label>
                <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $item->price; ?>"
                    required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">HSN/SAC Code</label>
                <input type="text" name="hsn_sac" class="form-control" value="<?php echo $item->hsn_sac; ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Tax Rate (%)</label>
                <input type="number" step="0.01" name="tax_rate" class="form-control"
                    value="<?php echo $item->tax_rate; ?>">
            </div>
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save" style="margin-right: 8px;"></i> Update Item
            </button>
            <a href="<?php echo site_url('items'); ?>" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>