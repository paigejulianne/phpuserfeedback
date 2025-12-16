<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - UserFeedback</title>
    <link rel="stylesheet" href="../assets/index.css">
</head>
<body style="display: flex; align-items: center; justify-content: center;">
    
    <div class="card" style="width: 100%; max-width: 400px; padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <a href="../public/" class="brand" style="font-size: 2rem;">UserFeedback</a>
            <h2 style="font-size: 1.5rem; margin-top: 1rem;">Create an account</h2>
        </div>

        <form action="register" method="POST">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Username</label>
                <input type="text" name="username" required placeholder="johndoe"
                       style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Email Address</label>
                <input type="email" name="email" required placeholder="john@example.com"
                       style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Password</label>
                <input type="password" name="password" required placeholder="••••••••"
                       style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">Create Account</button>
        </form>

        <p style="text-align: center; margin-top: 1.5rem; color: var(--text-secondary); font-size: 0.9rem;">
            Already have an account? <a href="login" style="color: var(--primary-color); font-weight: 600;">Sign in</a>
        </p>
    </div>

</body>
</html>
