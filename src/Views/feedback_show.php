<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($feedback['title']); ?> - UserFeedback</title>
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

    <main class="container" style="max-width: 800px; margin-top: 3rem; margin-bottom: 5rem;">
        
        <!-- Main Feedback Card -->
        <div class="card" style="margin-bottom: 2rem;">
            <div style="display: flex; gap: 1.5rem; align-items: start;">
                <!-- Vote Section -->
                <div style="display: flex; flex-direction: column; align-items: center; min-width: 60px;">
                     <!-- Vote button logic reused (could be componentized) -->
                     <button class="vote-btn" data-id="<?php echo $feedback['id']; ?>" 
                            style="background: white; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 0.5rem; width: 100%; cursor: pointer; display: flex; flex-direction: column; align-items: center; transition: all 0.2s;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="vote-icon" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-secondary); margin-bottom: 0.2rem;">
                            <polyline points="18 15 12 9 6 15"></polyline>
                        </svg>
                        <span class="vote-count" style="font-weight: 700; font-size: 1.1rem; color: var(--text-primary);">
                            <?php echo $feedback['vote_count']; ?>
                        </span>
                    </button>
                </div>

                <!-- Content -->
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                        <h1 style="font-size: 1.8rem; line-height: 1.3; margin: 0;"><?php echo htmlspecialchars($feedback['title']); ?></h1>
                    </div>
                    
                    <div style="margin-bottom: 1rem; display: flex; gap: 1rem; align-items: center; font-size: 0.9rem;">
                         <span style="padding: 0.2rem 0.6rem; background: var(--border-color); border-radius: 2rem; font-weight: 600; text-transform: uppercase; font-size: 0.8rem;">
                            <?php echo htmlspecialchars($feedback['status']); ?>
                        </span>
                        <span style="color: var(--primary-color); font-weight: 600;">
                            #<?php echo htmlspecialchars($feedback['category_name'] ?? 'General'); ?>
                        </span>
                         <span style="color: var(--text-secondary);">
                            <?php echo date('M j, Y', strtotime($feedback['created_at'])); ?>
                        </span>
                    </div>

                    <p style="font-size: 1.1rem; line-height: 1.7; color: var(--text-primary); white-space: pre-wrap;"><?php echo htmlspecialchars($feedback['description']); ?></p>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div id="comments">
            <h3 style="margin-bottom: 1.5rem; font-size: 1.3rem;">Discussion (<?php echo count($comments); ?>)</h3>

            <?php if (empty($comments)): ?>
                <div style="padding: 2rem; text-align: center; color: var(--text-secondary); background: var(--surface-bg); border-radius: var(--radius-lg); border: 1px dashed var(--border-color); margin-bottom: 2rem;">
                    No comments yet. Start the discussion!
                </div>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 1.5rem; margin-bottom: 2rem;">
                    <?php foreach ($comments as $comment): ?>
                        <div class="card" style="padding: 1.25rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span style="font-weight: 600;"><?php echo htmlspecialchars($comment['username']); ?></span>
                                <span style="font-size: 0.85rem; color: var(--text-secondary);"><?php echo date('M j, g:i a', strtotime($comment['created_at'])); ?></span>
                            </div>
                            <div style="color: var(--text-primary); line-height: 1.5;">
                                <?php echo nl2br(htmlspecialchars($comment['body'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Add Comment Form -->
            <?php if ($isLoggedIn): ?>
                <div class="card">
                    <h4 style="margin-bottom: 1rem;">Add a comment</h4>
                    <form action="../comments/add" method="POST">
                        <input type="hidden" name="feedback_id" value="<?php echo $feedback['id']; ?>">
                        <div style="margin-bottom: 1rem;">
                            <textarea name="body" required rows="3" placeholder="What are your thoughts?"
                                      style="width: 100%; padding: 1rem; border: 2px solid var(--border-color); border-radius: var(--radius-md); font-family: inherit; font-size: 1rem; resize: vertical;"></textarea>
                        </div>
                        <div style="text-align: right;">
                            <button type="submit" class="btn btn-primary">Post Comment</button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <div class="card" style="text-align: center; background: #f1f5f9;">
                    <p style="margin-bottom: 1rem;">Please log in to join the discussion.</p>
                    <a href="../login" class="btn btn-primary">Login</a>
                </div>
            <?php endif; ?>

        </div>

    </main>

    <script src="../assets/main.js"></script>
</body>
</html>
