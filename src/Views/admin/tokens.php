<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Tokens - Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/index.css">
</head>
<body>
    <nav class="navbar" style="background: #1e293b; border-bottom: none;">
        <div class="container navbar-content">
            <a href="../admin" class="brand" style="color: white; -webkit-text-fill-color: white;"><?php echo \App\Helpers\Config::get('site_name'); ?> Admin</a>
            <div class="nav-links">
                <a href="../admin" class="btn" style="color: white; opacity: 0.8;">Feedback Management</a>
                <a href="../public/" class="btn" style="color: white; opacity: 0.8;">Back to Site</a>
            </div>
        </div>
    </nav>

    <main class="container" style="margin-top: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1>API Tokens</h1>
            <form action="tokens/create" method="POST">
                <button type="submit" class="btn btn-primary">Generate New Token</button>
            </form>
        </div>

        <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f8fafc; border-bottom: 2px solid var(--border-color);">
                    <tr>
                        <th style="text-align: left; padding: 1rem; font-weight: 600;">Token</th>
                        <th style="text-align: left; padding: 1rem; font-weight: 600;">Created By</th>
                        <th style="text-align: left; padding: 1rem; font-weight: 600;">Date</th>
                        <th style="text-align: left; padding: 1rem; font-weight: 600;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tokens)): ?>
                        <tr><td colspan="4" style="padding: 2rem; text-align: center;">No tokens found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($tokens as $token): ?>
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <td style="padding: 1rem;">
                                    <code style="background: #e2e8f0; padding: 0.2rem 0.4rem; border-radius: 4px; font-family: monospace;">
                                        <?php echo substr($token['token'], 0, 10) . '...'; ?>
                                    </code>
                                    <button onclick="navigator.clipboard.writeText('<?php echo $token['token']; ?>'); alert('Copied!');" style="margin-left: 0.5rem; cursor: pointer; border: none; background: none; color: var(--primary-color);">
                                        Copy Full
                                    </button>
                                </td>
                                <td style="padding: 1rem;"><?php echo htmlspecialchars($token['username']); ?></td>
                                <td style="padding: 1rem;"><?php echo date('M j, Y H:i', strtotime($token['created_at'])); ?></td>
                                <td style="padding: 1rem;">
                                    <form action="tokens/revoke" method="POST" onsubmit="return confirm('Revoke this token? Applications using it will stop working.');">
                                        <input type="hidden" name="token_id" value="<?php echo $token['id']; ?>">
                                        <button type="submit" style="color: var(--danger-color); background: none; border: none; text-decoration: underline; cursor: pointer;">Revoke</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
