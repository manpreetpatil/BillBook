<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login - BillBook</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
</head>

<body
    style="background-color: #f3f4f6; display: flex; align-items: center; justify-content: center; min-height: 100vh;">

    <div class="card" style="width: 100%; max-width: 400px; padding: 32px;">
        <div style="text-align: center; margin-bottom: 24px;">
            <div style="font-size: 2rem; color: var(--primary-color); margin-bottom: 8px;">
                <i class="fas fa-bolt"></i>
            </div>
            <h2 style="margin: 0;">Staff Login</h2>
            <p class="text-muted">Sign in to your staff account</p>
        </div>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <form action="<?php echo site_url('auth/login_staff'); ?>" method="post">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="margin-top: 24px;">
                Sign In
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px;">
            <a href="<?php echo site_url('auth/login'); ?>"
                style="color: var(--primary-color); text-decoration: none; font-size: 0.9rem;">
                <i class="fas fa-arrow-left"></i> Back to Admin Login
            </a>
        </div>
    </div>

</body>

</html>