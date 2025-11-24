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
            <label class="form-label">State (for GST)</label>
            <select name="state" class="form-control">
                <option value="">Select State</option>
                <?php
                $states = [
                    'Andhra Pradesh',
                    'Arunachal Pradesh',
                    'Assam',
                    'Bihar',
                    'Chhattisgarh',
                    'Goa',
                    'Gujarat',
                    'Haryana',
                    'Himachal Pradesh',
                    'Jharkhand',
                    'Karnataka',
                    'Kerala',
                    'Madhya Pradesh',
                    'Maharashtra',
                    'Manipur',
                    'Meghalaya',
                    'Mizoram',
                    'Nagaland',
                    'Odisha',
                    'Punjab',
                    'Rajasthan',
                    'Sikkim',
                    'Tamil Nadu',
                    'Telangana',
                    'Tripura',
                    'Uttar Pradesh',
                    'Uttarakhand',
                    'West Bengal',
                    'Andaman and Nicobar Islands',
                    'Chandigarh',
                    'Dadra and Nagar Haveli and Daman and Diu',
                    'Delhi',
                    'Jammu and Kashmir',
                    'Ladakh',
                    'Lakshadweep',
                    'Puducherry'
                ];
                $current_state = isset($settings->state) ? $settings->state : '';
                foreach ($states as $state) {
                    $selected = ($current_state == $state) ? 'selected' : '';
                    echo "<option value='$state' $selected>$state</option>";
                }
                ?>
            </select>
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
            <select name="currency" class="form-control">
                <?php
                $current_currency = isset($settings->currency) ? $settings->currency : 'INR (₹)';
                if (isset($currencies) && !empty($currencies)):
                    foreach ($currencies as $currency):
                        $currency_value = $currency->code . ' (' . $currency->symbol . ')';
                        ?>
                        <option value="<?php echo $currency_value; ?>" <?php echo ($current_currency == $currency_value) ? 'selected' : ''; ?>>
                            <?php echo $currency->name . ' - ' . $currency->code . ' (' . $currency->symbol . ')'; ?>
                        </option>
                    <?php endforeach;
                else: ?>
                    <option value="INR (₹)" <?php echo ($current_currency == 'INR (₹)') ? 'selected' : ''; ?>>Indian Rupee (₹)
                    </option>
                    <option value="USD ($)" <?php echo ($current_currency == 'USD ($)') ? 'selected' : ''; ?>>US Dollar ($)
                    </option>
                <?php endif; ?>
            </select>
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