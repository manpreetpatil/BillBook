<div class="card">
    <div class="card-header">
        <div class="card-title">User Management</div>
        <div style="display: flex; gap: 12px;">
            <a href="<?php echo site_url('users/logs'); ?>" class="btn btn-outline">
                <i class="fas fa-history" style="margin-right: 8px;"></i> Activity Logs
            </a>
            <a href="<?php echo site_url('users/create'); ?>" class="btn btn-primary">
                <i class="fas fa-plus" style="margin-right: 8px;"></i> Add New User
            </a>
        </div>
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
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td style="font-weight: 500;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div
                                        style="width: 32px; height: 32px; background: #e0e7ff; color: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.9rem;">
                                        <?php echo strtoupper(substr($user->name, 0, 1)); ?>
                                    </div>
                                    <?php echo $user->name; ?>
                                </div>
                            </td>
                            <td><?php echo $user->email; ?></td>
                            <td>
                                <?php
                                $badge_class = 'badge-info';
                                if ($user->role == 'admin')
                                    $badge_class = 'badge-primary';
                                if ($user->role == 'cashier')
                                    $badge_class = 'badge-success';
                                ?>
                                <span class="badge <?php echo $badge_class; ?>"><?php echo ucfirst($user->role); ?></span>
                            </td>
                            <td>
                                <span class="badge badge-success">Active</span>
                            </td>
                            <td class="text-right">
                                <?php if ($user->role !== 'admin'): ?>
                                    <a href="<?php echo site_url('users/delete/' . $user->id); ?>" class="btn btn-danger"
                                        style="padding: 4px 8px; font-size: 0.8rem;"
                                        onclick="return confirm('Are you sure you want to delete this user?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>