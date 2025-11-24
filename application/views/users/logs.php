<div class="card">
    <div class="card-header">
        <div class="card-title">Activity Logs</div>
        <a href="<?php echo site_url('users'); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back to Users
        </a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Action</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td style="white-space: nowrap;"><?php echo date('d M Y H:i', strtotime($log->created_at)); ?></td>
                            <td style="font-weight: 500;"><?php echo $log->user_name; ?></td>
                            <td>
                                <span class="badge badge-light"><?php echo ucfirst($log->role); ?></span>
                            </td>
                            <td>
                                <span class="badge badge-info"><?php echo $log->action; ?></span>
                            </td>
                            <td class="text-muted"><?php echo $log->details; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No activity logs found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>