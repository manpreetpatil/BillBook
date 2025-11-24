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
        <div class="card-title">My Profile</div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
        <!-- Profile Information -->
        <div>
            <h3 style="margin-bottom: 16px;">Profile Information</h3>

            <?php if ($user->photo_url): ?>
                <div style="margin-bottom: 20px; text-align: center;">
                    <img src="<?php echo $user->photo_url; ?>" alt="Profile Photo"
                        style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary-color);">
                </div>
            <?php endif; ?>

            <form action="<?php echo site_url('profile'); ?>" method="post">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo $user->name; ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" value="<?php echo $user->email; ?>" disabled>
                    <?php if ($user->email_verified): ?>
                        <small style="color: var(--success-color); margin-top: 4px; display: block;">
                            <i class="fas fa-check-circle"></i> Email verified
                        </small>
                    <?php else: ?>
                        <small style="color: var(--warning-color); margin-top: 4px; display: block;">
                            <i class="fas fa-exclamation-circle"></i> Email not verified
                        </small>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo $user->phone; ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Sign-in Provider</label>
                    <input type="text" class="form-control" value="<?php echo ucfirst($user->provider); ?>" disabled>
                </div>

                <div class="form-group">
                    <label class="form-label">Member Since</label>
                    <input type="text" class="form-control"
                        value="<?php echo date('d M Y', strtotime($user->created_at)); ?>" disabled>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Profile
                </button>
            </form>
        </div>

        <!-- Account Management -->
        <div>
            <h3 style="margin-bottom: 16px;">Account Management</h3>

            <div
                style="padding: 20px; background-color: #eff6ff; border-radius: 8px; border-left: 4px solid var(--primary-color); margin-bottom: 20px;">
                <h4 style="margin-bottom: 12px; color: var(--primary-color);">
                    <i class="fas fa-shield-alt"></i> Password & Security
                </h4>
                <p style="color: var(--text-light); margin-bottom: 16px;">
                    Your account is secured by Firebase Authentication. To manage your password and security settings,
                    visit your Firebase account.
                </p>
                <a href="https://myaccount.google.com/security" target="_blank" class="btn btn-outline">
                    <i class="fas fa-external-link-alt"></i> Manage Password
                </a>
            </div>

            <?php if ($user->provider === 'google'): ?>
                <div
                    style="padding: 20px; background-color: #f0fdf4; border-radius: 8px; border-left: 4px solid var(--success-color); margin-bottom: 20px;">
                    <h4 style="margin-bottom: 8px; color: var(--success-color);">
                        <i class="fab fa-google"></i> Google Account
                    </h4>
                    <p style="color: var(--text-light); margin: 0;">
                        You're signed in with your Google account. Your profile information is synced from Google.
                    </p>
                </div>
            <?php endif; ?>

            <div
                style="padding: 20px; background-color: #fef3c7; border-radius: 8px; border-left: 4px solid var(--warning-color);">
                <h4 style="margin-bottom: 8px; color: #92400e;">
                    <i class="fas fa-info-circle"></i> Account Information
                </h4>
                <ul style="margin: 0; padding-left: 20px; color: var(--text-light);">
                    <li>Your email cannot be changed</li>
                    <li>Password is managed by Firebase</li>
                    <li>Profile photo synced from <?php echo ucfirst($user->provider); ?></li>
                    <li>All data is securely encrypted</li>
                </ul>
            </div>
        </div>
    </div>
</div>