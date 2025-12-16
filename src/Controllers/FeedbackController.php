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
            $description = $_POST['description'] ?? '';
            
            // Basic validation
            if (empty($title) || empty($description) || empty($category_id)) {
                // TODO: Flash error message
                header('Location: ../public/feedback/new?error=missing_fields');
                exit;
            }

            $feedbackModel = new Feedback();
            $user_id = \App\Helpers\Session::user()['id'];

            if ($feedbackModel->create($user_id, $category_id, $title, $description)) {
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
