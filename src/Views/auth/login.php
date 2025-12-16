<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UserFeedback</title>
    <link rel="stylesheet" href="../assets/index.css">
</head>
<body style="display: flex; align-items: center; justify-content: center;">
    
    <div class="card" style="width: 100%; max-width: 400px; padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <a href="../public/" class="brand" style="font-size: 2rem;">UserFeedback</a>
            <h2 style="font-size: 1.5rem; margin-top: 1rem;">Welcome back</h2>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div style="background: #fef2f2; color: #ef4444; padding: 0.75rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-size: 0.9rem; text-align: center;">
                Invalid email or password.
            </div>
        <?php endif; ?>

        <form action="login" method="POST">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Email Address</label>
                <input type="email" name="email" required 
                       style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
            </div>

            <div style="margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <label style="font-weight: 600;">Password</label>
                    <a href="password/reset" style="font-size: 0.85rem; color: var(--primary-color);">Forgot password?</a>
                </div>
                <input type="password" name="password" required 
                       style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">Sign In</button>
        </form>

        <p style="text-align: center; margin-top: 1.5rem; color: var(--text-secondary); font-size: 0.9rem;">
            Don't have an account? <a href="register" style="color: var(--primary-color); font-weight: 600;">Sign up</a>
        </p>
    </div>

</body>
</html>
