<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - BillBook</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="auth-container">
        <div class="auth-split">
            <!-- Left Side - Branding -->
            <div class="auth-brand-side">
                <div class="auth-brand-content">
                    <div class="auth-brand-logo">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <h2 class="auth-brand-title">BillBook</h2>
                    <p class="auth-brand-subtitle">Your complete invoicing and billing solution for modern businesses
                    </p>

                    <div class="auth-brand-features">
                        <div class="auth-feature">
                            <div class="auth-feature-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div class="auth-feature-text">Fast & Easy Invoicing</div>
                        </div>
                        <div class="auth-feature">
                            <div class="auth-feature-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="auth-feature-text">Real-time Analytics</div>
                        </div>
                        <div class="auth-feature">
                            <div class="auth-feature-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="auth-feature-text">Secure & Reliable</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="auth-form-side">
                <div class="auth-card">
                    <div class="auth-header">
                        <h1><i class="fas fa-user-plus"></i> Create Account</h1>
                        <p>Get started with your free account</p>
                    </div>

                    <div id="alert-container"></div>

                    <form id="registerForm" onsubmit="handleRegister(event)">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user"></i> Full Name
                            </label>
                            <input type="text" id="name" name="name" class="form-control"
                                placeholder="Enter your full name" required autofocus>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-envelope"></i> Email Address
                            </label>
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="Enter your email" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Create a password (min 6 characters)" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock"></i> Confirm Password
                            </label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                                placeholder="Confirm your password" required>
                        </div>

                        <button type="submit" id="registerBtn" class="btn btn-primary btn-block">
                            <i class="fas fa-user-plus"></i> Create Account
                        </button>
                    </form>

                    <div class="auth-divider">
                        <span>OR</span>
                    </div>

                    <button onclick="handleGoogleSignIn()" id="googleBtn" class="btn btn-outline btn-block"
                        style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                        <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
                            <path fill="#EA4335"
                                d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z" />
                            <path fill="#4285F4"
                                d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z" />
                            <path fill="#FBBC05"
                                d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z" />
                            <path fill="#34A853"
                                d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z" />
                        </svg>
                        Sign up with Google
                    </button>

                    <div class="auth-footer">
                        <span>Already have an account?</span>
                        <a href="<?php echo site_url('auth/login'); ?>">Sign In</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-auth-compat.js"></script>

    <!-- Firebase Config -->
    <?php $this->load->view('partials/firebase-config'); ?>
    <script src="<?php echo base_url('assets/js/firebase-auth.js'); ?>"></script>

    <script>
        async function handleRegister(event) {
            event.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const registerBtn = document.getElementById('registerBtn');

            // Validate password match
            if (password !== confirmPassword) {
                showAlert('Passwords do not match!', 'error');
                return;
            }

            showLoading(registerBtn);

            const result = await signUpWithEmail(name, email, password);

            if (result.success) {
                showAlert(result.message, 'success');
                // Redirect to login after 2 seconds
                setTimeout(() => {
                    window.location.href = '<?php echo site_url("auth/login"); ?>';
                }, 2000);
            } else {
                hideLoading(registerBtn);
                showAlert(result.message, 'error');
            }
        }

        async function handleGoogleSignIn() {
            const googleBtn = document.getElementById('googleBtn');
            showLoading(googleBtn);

            const result = await signInWithGoogle();

            if (!result.success) {
                hideLoading(googleBtn);
                showAlert(result.message, 'error');
            }
            // On success, syncUserWithBackend will redirect
        }
    </script>
</body>

</html>