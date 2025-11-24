<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <div class="card-title">Add New User</div>
        <a href="<?php echo site_url('users'); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back
        </a>
    </div>

    <form action="<?php echo site_url('users/create'); ?>" method="post">
        <div class="form-group">
            <label class="form-label">Full Name *</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="form-label">Email Address *</label>
            <input type="email" name="email" class="form-control" required>
            <small class="text-muted">This will be used for login.</small>
        </div>

        <div class="form-group">
            <label class="form-label">Password *</label>
            <input type="password" name="password" class="form-control" required minlength="6">
        </div>

        <div class="form-group">
            <label class="form-label">Role *</label>
            <select name="role" class="form-control" required>
                <option value="cashier">Cashier (Can create bills, cannot delete)</option>
                <option value="staff">Staff (View only)</option>
                <option value="admin">Admin (Full access)</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="margin-top: 24px;">
            Create User
        </button>
    </form>
</div>