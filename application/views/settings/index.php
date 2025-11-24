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
        <div class="card-title">Company Settings</div>
    </div>

    <form action="<?php echo site_url('settings/update'); ?>" method="post">
        <div class="form-group">
            <label class="form-label">Company Name *</label>
            <input type="text" name="company_name" class="form-control"
                value="<?php echo isset($settings->company_name) ? $settings->company_name : ''; ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control"
                rows="3"><?php echo isset($settings->address) ? $settings->address : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">GSTIN</label>
            <input type="text" name="gstin" class="form-control"
                value="<?php echo isset($settings->gstin) ? $settings->gstin : ''; ?>">
        </div>

        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                value="<?php echo isset($settings->email) ? $settings->email : ''; ?>">
        </div>

        <div class="form-group">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control"
                value="<?php echo isset($settings->phone) ? $settings->phone : ''; ?>">
        </div>

        <div class="form-group">
            <label class="form-label">Currency</label>
            <input type="text" name="currency" class="form-control"
                value="<?php echo isset($settings->currency) ? $settings->currency : 'INR'; ?>">
        </div>

        <div class="form-group">
            <label class="form-label">Invoice Prefix</label>
            <input type="text" name="invoice_prefix" class="form-control"
                value="<?php echo isset($settings->invoice_prefix) ? $settings->invoice_prefix : 'INV-'; ?>">
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save" style="margin-right: 8px;"></i> Save Settings
            </button>
        </div>
    </form>
</div>