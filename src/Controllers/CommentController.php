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

            $commentModel = new Comment();
            $user_id = Session::user()['id'];

            if ($commentModel->create($user_id, $feedback_id, $body)) {
                Url::redirect('feedback/view?id=' . $feedback_id . '#comments');
            } else {
                Url::redirect('feedback/view?id=' . $feedback_id . '&error=db_error');
            }
        }
    }
}
