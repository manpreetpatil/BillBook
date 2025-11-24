// Firebase Authentication Functions

/**
 * Sign up with email and password
 */
async function signUpWithEmail(name, email, password) {
    try {
        // Create user in Firebase
        const userCredential = await auth.createUserWithEmailAndPassword(email, password);
        const user = userCredential.user;

        // Update profile with name
        await user.updateProfile({
            displayName: name
        });

        // Send email verification
        await user.sendEmailVerification();

        // Get ID token
        const idToken = await user.getIdToken();

        // Sync with backend
        await syncUserWithBackend(idToken, name);

        return { success: true, message: 'Registration successful! Please verify your email.' };
    } catch (error) {
        return { success: false, message: getErrorMessage(error.code) };
    }
}

/**
 * Sign in with email and password
 */
async function signInWithEmail(email, password) {
    try {
        const userCredential = await auth.signInWithEmailAndPassword(email, password);
        const user = userCredential.user;

        // Get ID token
        const idToken = await user.getIdToken();

        // Sync with backend
        await syncUserWithBackend(idToken);

        return { success: true };
    } catch (error) {
        return { success: false, message: getErrorMessage(error.code) };
    }
}

/**
 * Sign in with Google
 */
async function signInWithGoogle() {
    try {
        const result = await auth.signInWithPopup(googleProvider);
        const user = result.user;

        // Get ID token
        const idToken = await user.getIdToken();

        // Sync with backend
        await syncUserWithBackend(idToken);

        return { success: true };
    } catch (error) {
        return { success: false, message: getErrorMessage(error.code) };
    }
}

/**
 * Send password reset email
 */
async function sendPasswordReset(email) {
    try {
        await auth.sendPasswordResetEmail(email);
        return { success: true, message: 'Password reset email sent! Check your inbox.' };
    } catch (error) {
        return { success: false, message: getErrorMessage(error.code) };
    }
}

/**
 * Sign out
 */
async function signOut() {
    try {
        await auth.signOut();
        window.location.href = '<?php echo site_url("auth/login"); ?>';
        return { success: true };
    } catch (error) {
        return { success: false, message: 'Failed to sign out' };
    }
}

/**
 * Sync user with backend
 */
async function syncUserWithBackend(idToken, displayName = null) {
    const response = await fetch('/BillBook/index.php/auth/verify_token', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            idToken: idToken,
            displayName: displayName
        })
    });

    const data = await response.json();

    if (data.success) {
        // Redirect to dashboard
        window.location.href = '/BillBook/index.php/dashboard';
    } else {
        throw new Error(data.message || 'Failed to sync with server');
    }
}

/**
 * Get user-friendly error messages
 */
function getErrorMessage(errorCode) {
    const errorMessages = {
        'auth/email-already-in-use': 'This email is already registered.',
        'auth/invalid-email': 'Invalid email address.',
        'auth/operation-not-allowed': 'Operation not allowed.',
        'auth/weak-password': 'Password should be at least 6 characters.',
        'auth/user-disabled': 'This account has been disabled.',
        'auth/user-not-found': 'No account found with this email.',
        'auth/wrong-password': 'Incorrect password.',
        'auth/too-many-requests': 'Too many attempts. Please try again later.',
        'auth/popup-closed-by-user': 'Sign-in popup was closed.',
        'auth/cancelled-popup-request': 'Sign-in cancelled.',
        'auth/network-request-failed': 'Network error. Please check your connection.'
    };

    return errorMessages[errorCode] || 'An error occurred. Please try again.';
}

/**
 * Show loading state
 */
function showLoading(button) {
    button.disabled = true;
    button.dataset.originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Please wait...';
}

/**
 * Hide loading state
 */
function hideLoading(button) {
    button.disabled = false;
    button.innerHTML = button.dataset.originalText;
}

/**
 * Show alert message
 */
function showAlert(message, type = 'error') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;

    const container = document.querySelector('.auth-card');
    const existingAlert = container.querySelector('.alert');

    if (existingAlert) {
        existingAlert.remove();
    }

    container.insertBefore(alertDiv, container.firstChild);

    // Auto-remove after 5 seconds
    setTimeout(() => alertDiv.remove(), 5000);
}
