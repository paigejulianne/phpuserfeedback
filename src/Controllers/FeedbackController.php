<?php

namespace App\Controllers;

use App\Models\Feedback;
use App\Models\Category;

class FeedbackController {
    
    public function create() {
        \App\Helpers\Session::requireLogin();
        
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();
        
        view('create_feedback', ['categories' => $categories]);
    }

    public function store() {
        \App\Helpers\Session::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $category_id = $_POST['category_id'] ?? '';
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';

            if (empty($title) || empty($description)) {
                \App\Helpers\Url::redirect('feedback/new?error=missing_fields');
            }

            // Sanitize Rich Text
            $description = \App\Helpers\Sanitizer::clean($description);

            // Handle File Upload
            $screenshot_data = null;
            $screenshot_mime = null;

            if (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] === UPLOAD_ERR_OK) {
                // Limit size (e.g. 2MB)
                if ($_FILES['screenshot']['size'] > 2 * 1024 * 1024) {
                     \App\Helpers\Url::redirect('feedback/new?error=file_too_large');
                }
                
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($_FILES['screenshot']['tmp_name']);
                
                $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (in_array($mime, $allowed)) {
                    $screenshot_data = file_get_contents($_FILES['screenshot']['tmp_name']);
                    $screenshot_mime = $mime;
                } else {
                    \App\Helpers\Url::redirect('feedback/new?error=invalid_file_type');
                }
            }

            $feedbackModel = new Feedback();
            $user_id = \App\Helpers\Session::user()['id'];

            if ($feedbackModel->create($user_id, $category_id, $title, $description, $screenshot_data, $screenshot_mime)) {
                \App\Helpers\Url::redirect(''); 
            } else {
                \App\Helpers\Url::redirect('feedback/new?error=db_error'); 
            }
        }
    }

    public function vote() {
        header('Content-Type: application/json');
        
        if (!\App\Helpers\Session::isLoggedIn()) {
             http_response_code(401);
             echo json_encode(['success' => false, 'message' => 'Unauthorized']);
             return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $feedback_id = $input['feedback_id'] ?? null;
            
            if (!$feedback_id) {
                echo json_encode(['success' => false, 'message' => 'Missing feedback ID']);
                return;
            }

            $user_id = \App\Helpers\Session::user()['id'];

            $feedbackModel = new Feedback();
            
            // Check ownership
            $feedback = $feedbackModel->getById($feedback_id);
            if ($feedback && $feedback['user_id'] == $user_id) {
                 echo json_encode(['success' => false, 'message' => 'You cannot vote on your own feedback.']);
                 return;
            }

            $result = $feedbackModel->toggleVote($user_id, $feedback_id);
            
            if ($result) {
                $newCount = $feedbackModel->getVoteCount($feedback_id);
                echo json_encode([
                    'success' => true, 
                    'action' => $result, 
                    'newCount' => $newCount
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database error']);
            }
        }
    }

    public function show() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            \App\Helpers\Url::redirect('');
        }

        $feedbackModel = new Feedback();
        $feedback = $feedbackModel->getById($id);

        if (!$feedback) {
            \App\Helpers\Url::redirect('');
        }

        $commentModel = new \App\Models\Comment();
        $comments = $commentModel->getByFeedbackId($id);

        view('feedback_show', [
            'feedback' => $feedback,
            'comments' => $comments,
            'isLoggedIn' => \App\Helpers\Session::isLoggedIn(),
            'user' => \App\Helpers\Session::user()
        ]);
    }
}
