<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - UserFeedback</title>
    <link rel="stylesheet" href="../../assets/index.css">
</head>
<body style="display: flex; align-items: center; justify-content: center;">
    
    <div class="card" style="width: 100%; max-width: 400px; padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <a href="../../" class="brand" style="font-size: 2rem;">UserFeedback</a>
            <h2 style="font-size: 1.5rem; margin-top: 1rem;">Reset Password</h2>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'sent'): ?>
            <div style="background: #dcfce7; color: #166534; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; text-align: center;">
                If an account exists with that email, we have sent a password reset link.
            </div>
        <?php else: ?>

            <p style="text-align: center; color: var(--text-secondary); margin-bottom: 2rem;">
                Enter your email address and we'll send you a link to reset your password.
            </p>

            <form action="email" method="POST">
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Email Address</label>
                    <input type="email" name="email" required placeholder="john@example.com"
                           style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">Send Reset Link</button>
            </form>
        <?php endif; ?>

        <p style="text-align: center; margin-top: 1.5rem; color: var(--text-secondary); font-size: 0.9rem;">
            <a href="../login" style="color: var(--primary-color); font-weight: 600;">Back to Login</a>
        </p>
    </div>

</body>
</html>
