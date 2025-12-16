<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - UserFeedback</title>
    <link rel="stylesheet" href="../assets/index.css">
</head>
<body>
    <nav class="navbar">
        <div class="container navbar-content">
            <a href="../" class="brand">UserFeedback</a>
            <div class="nav-links">
                <a href="../" class="btn" style="color: var(--text-secondary);">Back to Home</a>
            </div>
        </div>
    </nav>

    <main class="container" style="max-width: 600px; margin-top: 3rem;">
        <h1 style="margin-bottom: 2rem;">Your Profile</h1>

        <?php if (isset($_GET['success'])): ?>
            <div style="background: #dcfce7; color: #166534; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
                Profile updated successfully.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
             <div style="background: #fef2f2; color: #ef4444; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
                <?php 
                    if ($_GET['error'] == 'wrong_password') echo "Current password was incorrect.";
                    else if ($_GET['error'] == 'password_required') echo "Current password required to set a new password.";
                    else echo "An error occurred.";
                ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <form action="profile/update" method="POST">
                
                <h3 style="margin-bottom: 1rem;">General Information</h3>
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required
                           style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                </div>

                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Email Address</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required
                           style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                </div>

                <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 2rem 0;">

                <h3 style="margin-bottom: 1rem;">Change Password</h3>
                <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1rem;">Leave blank if you don't want to change it.</p>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">New Password</label>
                    <input type="password" name="new_password" placeholder="New Password"
                           style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                </div>

                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Current Password (Required for Password Change)</label>
                    <input type="password" name="current_password" placeholder="Confirm your identity"
                           style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                </div>

                <div style="text-align: right;">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
