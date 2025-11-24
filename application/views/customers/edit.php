<div class="card">
    <div class="card-header">
        <div class="card-title">Edit Customer</div>
        <a href="<?php echo site_url('customers'); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back
        </a>
    </div>

    <form action="<?php echo site_url('customers/edit/' . $customer->id); ?>" method="post">
        <div class="form-group">
            <label class="form-label">Customer Name *</label>
            <input type="text" name="name" class="form-control" value="<?php echo $customer->name; ?>" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $customer->email; ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="<?php echo $customer->phone; ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="3"><?php echo $customer->address; ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">State</label>
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
                $current_state = isset($customer->state) ? $customer->state : '';
                foreach ($states as $state) {
                    $selected = ($current_state == $state) ? 'selected' : '';
                    echo "<option value='$state' $selected>$state</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">GSTIN / Tax ID</label>
            <input type="text" name="gstin" class="form-control" value="<?php echo $customer->gstin; ?>">
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save" style="margin-right: 8px;"></i> Update Customer
            </button>
            <a href="<?php echo site_url('customers'); ?>" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>