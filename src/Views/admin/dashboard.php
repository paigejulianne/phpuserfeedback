<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - UserFeedback</title>
    <link rel="stylesheet" href="../assets/index.css">
</head>
<body>
    <nav class="navbar" style="background: #1e293b; border-bottom: none;">
        <div class="container navbar-content">
            <a href="../public/" class="brand" style="color: white; -webkit-text-fill-color: white;">UserFeedback Admin</a>
            <div class="nav-links">
                <a href="admin/tokens" class="btn" style="color: white; opacity: 0.8;">API Tokens</a>
                <a href="../public/" class="btn" style="color: white; opacity: 0.8;">Back to Site</a>
            </div>
        </div>
    </nav>

    <main class="container" style="margin-top: 2rem;">
        <h1 style="margin-bottom: 2rem;">Feedback Management</h1>

        <?php
        function getSortLink($column, $currentSort) {
            // Determine direction
            $parts = explode('_', $currentSort);
            $currentCol = implode('_', array_slice($parts, 0, -1)); // Handle multi-word cols if any, though we use simple keys
            // Actually our keys are title_asc. $column would be 'title'.
            
            // Simpler: Check if current sort starts with column
            if (strpos($currentSort, $column) === 0) {
                // If currently asc, switch to desc
                if (strpos($currentSort, '_asc') !== false) {
                    return "?sort=" . $column . "_desc";
                }
                return "?sort=" . $column . "_asc";
            }
            // Default to desc for stats, asc for text? 
            if ($column === 'votes') return "?sort=" . $column . "_desc";
            return "?sort=" . $column . "_asc";
        }
        
        function getSortIcon($column, $currentSort) {
             if (strpos($currentSort, $column) === 0) {
                 return strpos($currentSort, '_asc') !== false ? ' ↑' : ' ↓';
             }
             return '';
        }
        ?>

        <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f8fafc; border-bottom: 2px solid var(--border-color);">
                    <tr>
                        <th style="text-align: left; padding: 1rem; font-weight: 600;">
                            <a href="<?php echo getSortLink('title', $currentSort); ?>" style="color: inherit; text-decoration: none;">
                                Title<?php echo getSortIcon('title', $currentSort); ?>
                            </a>
                        </th>
                        <th style="text-align: left; padding: 1rem; font-weight: 600;">
                            <a href="<?php echo getSortLink('category', $currentSort); ?>" style="color: inherit; text-decoration: none;">
                                Category<?php echo getSortIcon('category', $currentSort); ?>
                            </a>
                        </th>
                        <th style="text-align: left; padding: 1rem; font-weight: 600;">
                            <a href="<?php echo getSortLink('votes', $currentSort); ?>" style="color: inherit; text-decoration: none;">
                                Votes<?php echo getSortIcon('votes', $currentSort); ?>
                            </a>
                        </th>
                         <th style="text-align: left; padding: 1rem; font-weight: 600;">
                            <a href="<?php echo getSortLink('status', $currentSort); ?>" style="color: inherit; text-decoration: none;">
                                Status<?php echo getSortIcon('status', $currentSort); ?>
                            </a>
                        </th>
                        <th style="text-align: left; padding: 1rem; font-weight: 600;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($feedbacks as $item): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 1rem;">
                                <a href="../feedback/view?id=<?php echo $item['id']; ?>" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">
                                    <?php echo htmlspecialchars($item['title']); ?>
                                </a>
                            </td>
                            <td style="padding: 1rem;">
                                <span style="font-size: 0.85rem; padding: 0.2rem 0.6rem; background: var(--background-bg); border-radius: 2rem;">
                                    <?php echo htmlspecialchars($item['category_name'] ?? 'General'); ?>
                                </span>
                            </td>
                            <td style="padding: 1rem; font-weight: 600;"><?php echo $item['vote_count']; ?></td>
                            <td style="padding: 1rem;">
                                <form action="admin/update_status" method="POST" style="display: flex; gap: 0.5rem;">
                                    <input type="hidden" name="feedback_id" value="<?php echo $item['id']; ?>">
                                    <select name="status" onchange="this.form.submit()"
                                            style="padding: 0.4rem; border-radius: 0.25rem; border: 1px solid var(--border-color); font-size: 0.9rem; 
                                                   background: <?php echo $item['status'] === 'completed' ? '#dcfce7' : ($item['status'] === 'in_progress' ? '#dbeafe' : 'white'); ?>;">
                                        <?php 
                                            $statuses = ['open', 'under_review', 'planned', 'in_progress', 'completed', 'closed'];
                                            foreach($statuses as $s): 
                                        ?>
                                            <option value="<?php echo $s; ?>" <?php echo $item['status'] === $s ? 'selected' : ''; ?>>
                                                <?php echo ucwords(str_replace('_', ' ', $s)); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            </td>
                            <td style="padding: 1rem;">
                                <form action="admin/delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this feedback?');">
                                    <input type="hidden" name="feedback_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" style="background: none; border: none; padding: 0; color: var(--danger-color); font-size: 0.9rem; cursor: pointer; text-decoration: underline;">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
