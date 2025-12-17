<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($feedback['title']); ?> - <?php echo \App\Helpers\Config::get('site_name'); ?></title>
    <link rel="stylesheet" href="../assets/index.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ql-editor { padding: 0; min-height: auto; }
        .content-body img { max-width: 100%; border-radius: 8px; margin-top: 1rem; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container navbar-content">
            <a href="../" class="brand"><?php echo \App\Helpers\Config::get('site_name'); ?></a>
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
                <div style="flex: 1; overflow: hidden;">
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

                    <div class="content-body" style="font-size: 1.1rem; line-height: 1.7; color: var(--text-primary); margin-bottom: 1rem;">
                        <?php echo $feedback['description']; // Safe HTML ?>
                    </div>

                    <?php if ($feedback['has_screenshot']): ?>
                        <div style="margin-top: 1rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden; display: inline-block;">
                            <a href="../image?type=feedback&id=<?php echo $feedback['id']; ?>" target="_blank">
                                <img src="../image?type=feedback&id=<?php echo $feedback['id']; ?>" alt="Screenshot" style="max-height: 300px; display: block;">
                            </a>
                        </div>
                    <?php endif; ?>
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
                            <div class="content-body" style="color: var(--text-primary); line-height: 1.5;">
                                <?php echo $comment['body']; // Safe HTML ?>
                            </div>
                            <?php if (!empty($comment['has_screenshot'])): ?>
                                <div style="margin-top: 1rem;">
                                    <a href="../image?type=comment&id=<?php echo $comment['id']; ?>" target="_blank">
                                        <img src="../image?type=comment&id=<?php echo $comment['id']; ?>" alt="Attachment" style="max-height: 200px; border-radius: 4px; border: 1px solid var(--border-color);">
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Add Comment Form -->
            <?php if ($isLoggedIn): ?>
                <div class="card">
                    <h4 style="margin-bottom: 1rem;">Add a comment</h4>
                    <form action="../comments/add" method="POST" enctype="multipart/form-data" id="commentForm">
                        <input type="hidden" name="feedback_id" value="<?php echo $feedback['id']; ?>">
                        
                        <div style="margin-bottom: 1rem;">
                            <input type="hidden" name="body" id="commentBody">
                            <div id="commentEditor" style="height: 120px; background: white;"></div>
                        </div>

                        <div style="margin-bottom: 1rem; display: flex; align-items: center; gap: 1rem;">
                            <label style="font-size: 0.9rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; color: var(--text-secondary);">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                Attach Image
                                <input type="file" name="screenshot" accept="image/*" style="display: none;" onchange="document.getElementById('fileName').textContent = this.files[0] ? this.files[0].name : '';">
                            </label>
                            <span id="fileName" style="font-size: 0.85rem; color: var(--text-secondary);"></span>
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

    <script>
        window.SITE_URL = "<?php echo \App\Helpers\Url::base(); ?>";
    </script>
    <script src="../assets/main.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        <?php if ($isLoggedIn): ?>
        var quill = new Quill('#commentEditor', {
            theme: 'snow',
            placeholder: 'What are your thoughts?',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'code-block'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }]
                ]
            }
        });

        document.getElementById('commentForm').onsubmit = function() {
            var input = document.getElementById('commentBody');
            input.value = quill.root.innerHTML;
            
            if (quill.getText().trim().length === 0) {
                alert('Please enter a comment.');
                return false;
            }
        };
        <?php endif; ?>
    </script>
</body>
</html>
