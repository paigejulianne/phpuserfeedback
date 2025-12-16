<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Password - UserFeedback</title>
    <link rel="stylesheet" href="../../assets/index.css">
</head>
<body style="display: flex; align-items: center; justify-content: center;">
    
    <div class="card" style="width: 100%; max-width: 400px; padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <a href="../../" class="brand" style="font-size: 2rem;">UserFeedback</a>
            <h2 style="font-size: 1.5rem; margin-top: 1rem;">Set New Password</h2>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div style="background: #fef2f2; color: #ef4444; padding: 0.75rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; text-align: center;">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <form action="../update" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Email Address</label>
                <input type="email" name="email" required placeholder="Confirm your email"
                       style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">New Password</label>
                <input type="password" name="password" required 
                       style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
            </div>

             <div style="margin-bottom: 2rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Confirm Password</label>
                <input type="password" name="password_confirmation" required 
                       style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">Reset Password</button>
        </form>
    </div>

</body>
</html>
