<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback - <?php echo \App\Helpers\Config::get('site_name'); ?></title>
    <link rel="stylesheet" href="../../assets/index.css">
    <!-- Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container navbar-content">
            <a href="../../" class="brand"><?php echo \App\Helpers\Config::get('site_name'); ?></a>
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

            <form action="store" method="POST" enctype="multipart/form-data" id="feedbackForm">
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-primary);">Title</label>
                    <input type="text" name="title" required placeholder="Enter a short, descriptive title"
                           style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-family: inherit; font-size: 1rem;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-primary);">Category</label>
                    <select name="category_id" required
                            style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-family: inherit; font-size: 1rem; background-color: white;">
                        <?php 
                        $catModel = new \App\Models\Category();
                        $categories = $catModel->getAll();
                        foreach($categories as $cat) {
                            echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-primary);">Description</label>
                    <!-- Valid Hidden Input to Store Data -->
                    <input type="hidden" name="description" id="descriptionInput">
                    <!-- Quill Container -->
                    <div id="editor" style="height: 200px; background: white;"></div>
                </div>

                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-primary);">Screenshot (Optional)</label>
                    <input type="file" name="screenshot" accept="image/*"
                         style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.25rem;">Max 2MB. Allowed: JPG, PNG, GIF.</p>
                </div>

                <div style="text-align: right;">
                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
                </div>
            </form>
        </div>
    </main>

    <!-- Quill JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Describe your idea or issue in detail...'
        });

        // Sync Quill to Hidden Input on Submit
        document.getElementById('feedbackForm').onsubmit = function() {
            var description = document.querySelector('input[name=description]');
            // Populate hidden form on submit
            description.value = quill.root.innerHTML;
            
            // Basic Empty Check (Quill leaves <p><br></p> typically when empty)
            if (quill.getText().trim().length === 0) {
                alert('Please provide a description.');
                return false;
            }
        };
    </script>
</body>
    <style>
        input:focus, select:focus {
            outline: none;
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
    </style>
</body>
</html>
