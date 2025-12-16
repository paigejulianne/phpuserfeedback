<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UserFeedback - Share Your Ideas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/index.css">
</head>
<body>
    <nav class="navbar">
        <div class="container navbar-content">
            <a href="index.php" class="brand">UserFeedback</a>
            <div class="nav-links" style="display: flex; align-items: center; gap: 1rem;">
                <?php if ($isLoggedIn): ?>
                    <a href="profile" style="color: var(--text-secondary); font-size: 0.9rem; text-decoration: none; font-weight: 500;">Hey, <?php echo htmlspecialchars($user['username']); ?></a>
                    <?php if ($user['role'] === 'admin'): ?>
                        <a href="admin" class="btn" style="color: var(--primary-color);">Admin</a>
                    <?php endif; ?>
                    <a href="logout" class="btn" style="color: var(--text-secondary);">Logout</a>
                    <a href="feedback/new" class="btn btn-primary">Submit Feedback</a>
                <?php else: ?>
                    <a href="login" class="btn" style="color: var(--text-secondary);">Login</a>
                    <a href="register" class="btn btn-primary">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="container" style="margin-top: 2rem;">
        <header style="text-align: center; margin-bottom: 2rem;">
            <h1>Help us build a better product.</h1>
            <p style="color: var(--text-secondary); font-size: 1.1rem; margin-top: 0.5rem;">
                Browse existing contributions or submit your own ideas.
            </p>
        </header>

        <!-- Search & Sort Toolbar -->
        <div class="card" style="margin-bottom: 2rem; padding: 1rem; display: flex; gap: 1rem; align-items: center; background: var(--surface-bg);">
            <form action="" method="GET" style="display: flex; gap: 1rem; width: 100%; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">
                    <div style="position: relative;">
                        <!-- Search Icon -->
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="search-icon" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary);">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" name="q" placeholder="Search ideas..." value="<?php echo htmlspecialchars($searchQuery ?? ''); ?>"
                               style="width: 100%; padding: 0.6rem 1rem 0.6rem 2.5rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-size: 1rem; outline: none;">
                    </div>
                </div>
                
                <div style="min-width: 150px;">
                    <select name="sort" onchange="this.form.submit()" 
                            style="width: 100%; padding: 0.6rem 1rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-size: 1rem; background-color: var(--surface-bg); cursor: pointer;">
                        <option value="popular" <?php echo ($currentSort ?? '') === 'popular' ? 'selected' : ''; ?>>Most Popular</option>
                        <option value="newest" <?php echo ($currentSort ?? '') === 'newest' ? 'selected' : ''; ?>>Newest</option>
                        <option value="oldest" <?php echo ($currentSort ?? '') === 'oldest' ? 'selected' : ''; ?>>Oldest</option>
                    </select>
                </div>

                <noscript>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </noscript>
            </form>
        </div>

        <section class="feedback-grid">
            <?php if (empty($feedbacks)): ?>
                <div class="card" style="text-align: center; padding: 3rem;">
                    <div style="margin-bottom: 1rem; color: var(--text-secondary);">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    <h2>No feedback found</h2>
                    <p style="color: var(--text-secondary);">Try adjusting your search or filters.</p>
                </div>
            <?php else: ?>
                <?php foreach ($feedbacks as $item): ?>
                    <div class="card" style="margin-bottom: 1rem; display: flex; gap: 1.5rem; align-items: start;">
                        <!-- Vote Section -->
                        <div style="display: flex; flex-direction: column; align-items: center; min-width: 60px;">
                            <button class="vote-btn" data-id="<?php echo $item['id']; ?>" 
                                    style="background: white; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 0.5rem; width: 100%; cursor: pointer; display: flex; flex-direction: column; align-items: center; transition: all 0.2s;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="vote-icon" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-secondary); margin-bottom: 0.2rem;">
                                    <polyline points="18 15 12 9 6 15"></polyline>
                                </svg>
                                <span class="vote-count" style="font-weight: 700; font-size: 1.1rem; color: var(--text-primary);">
                                    <?php echo $item['vote_count']; ?>
                                </span>
                            </button>
                        </div>

                        <!-- Content Section -->
                        <div style="flex: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: start;">
                                <h3 style="margin-top: 0; font-size: 1.25rem;">
                                    <a href="feedback/view?id=<?php echo $item['id']; ?>" style="text-decoration: none; color: inherit;"><?php echo htmlspecialchars($item['title']); ?></a>
                                </h3>
                                <span style="font-size: 0.75rem; padding: 0.2rem 0.6rem; background: var(--border-color); border-radius: 2rem; font-weight: 600; text-transform: uppercase;">
                                    <?php echo htmlspecialchars($item['status']); ?>
                                </span>
                            </div>
                            <p style="margin: 0.5rem 0; color: var(--text-secondary); line-height: 1.6;">
                                <?php echo htmlspecialchars($item['description']); ?>
                            </p>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; font-size: 0.9rem;">
                                <span style="color: var(--primary-color); font-weight: 600;">
                                    #<?php echo htmlspecialchars($item['category_name'] ?? 'General'); ?>
                                </span>
                                <span style="color: var(--text-secondary);">
                                    <?php echo date('M j, Y', strtotime($item['created_at'])); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>

    <script src="assets/main.js"></script>
</body>
</html>
