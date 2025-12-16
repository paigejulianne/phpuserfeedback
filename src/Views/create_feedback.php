<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback - UserFeedback</title>
    <link rel="stylesheet" href="../assets/index.css">
</head>
<body>
    <nav class="navbar">
        <div class="container navbar-content">
            <a href="../../" class="brand">UserFeedback</a>
            <div class="nav-links">
                <a href="../../" class="btn" style="color: var(--text-secondary);">Cancel</a>
            </div>
        </div>
    </nav>

    <main class="container" style="max-width: 800px; margin-top: 3rem;">
        <div class="card">
            <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">Submit an Idea</h1>
            <p style="color: var(--text-secondary); margin-bottom: 2rem;">
                We read every piece of feedback. Let us know how we can improve!
            </p>

            <form action="store" method="POST">
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-primary);">Title</label>
                    <input type="text" name="title" required placeholder="Short, descriptive title"
                           style="width: 100%; padding: 0.75rem; border: 2px solid var(--border-color); border-radius: var(--radius-md); font-family: inherit; font-size: 1rem; transition: border-color 0.2s;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-primary);">Category</label>
                    <select name="category_id" required 
                            style="width: 100%; padding: 0.75rem; border: 2px solid var(--border-color); border-radius: var(--radius-md); font-family: inherit; font-size: 1rem; background-color: var(--surface-bg);">
                        <option value="" disabled selected>Select a category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-primary);">Description</label>
                    <textarea name="description" required rows="5" placeholder="Explain your idea in detail..."
                              style="width: 100%; padding: 0.75rem; border: 2px solid var(--border-color); border-radius: var(--radius-md); font-family: inherit; font-size: 1rem; resize: vertical;"></textarea>
                </div>

                <div style="text-align: right;">
                    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-size: 1rem;">Submit Feedback</button>
                </div>
            </form>
        </div>
    </main>

    <style>
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
    </style>
</body>
</html>
