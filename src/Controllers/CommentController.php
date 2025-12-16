<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Helpers\Url;
use App\Models\Comment;

class CommentController {
    
    public function store() {
        Session::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $feedback_id = $_POST['feedback_id'] ?? null;
            $body = $_POST['body'] ?? '';

            if (!$feedback_id) {
                 Url::redirect('');
            }
            
            if (empty($body)) {
                 Url::redirect('feedback/view?id=' . $feedback_id . '&error=empty_comment');
            }

            // Sanitize
            $body = \App\Helpers\Sanitizer::clean($body);

            // Handle File Upload
            $screenshot_data = null;
            $screenshot_mime = null;

            if (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] === UPLOAD_ERR_OK) {
                if ($_FILES['screenshot']['size'] > 2 * 1024 * 1024) {
                     Url::redirect('feedback/view?id=' . $feedback_id . '&error=file_too_large');
                }
                
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($_FILES['screenshot']['tmp_name']);
                
                $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (in_array($mime, $allowed)) {
                    $screenshot_data = file_get_contents($_FILES['screenshot']['tmp_name']);
                    $screenshot_mime = $mime;
                } else {
                    Url::redirect('feedback/view?id=' . $feedback_id . '&error=invalid_file_type');
                }
            }

            $commentModel = new Comment();
            $user_id = Session::user()['id'];

            if ($commentModel->create($user_id, $feedback_id, $body, $screenshot_data, $screenshot_mime)) {
                Url::redirect('feedback/view?id=' . $feedback_id . '#comments');
            } else {
                Url::redirect('feedback/view?id=' . $feedback_id . '&error=db_error');
            }
        }
    }
}
