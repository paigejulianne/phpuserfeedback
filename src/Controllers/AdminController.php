<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Models\Feedback;
use App\Config\Database;
use PDO;

class AdminController {
    
    public function __construct() {
        Session::requireAdmin();
    }

    public function index() {
        $feedbackModel = new Feedback();
        $feedbacks = $feedbackModel->getAll();
        
        view('admin/dashboard', ['feedbacks' => $feedbacks]);
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $feedback_id = $_POST['feedback_id'] ?? null;
            $status = $_POST['status'] ?? null;

            if ($feedback_id && $status) {
                // Quick method added here or could be in Model
                // For speed, let's just run a quick direct query or add to model
                // It's cleaner to add to Model
                $feedbackModel = new Feedback();
                $feedbackModel->updateStatus($feedback_id, $status);
            }
            \App\Helpers\Url::redirect('admin');
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $feedback_id = $_POST['feedback_id'] ?? null;
            if ($feedback_id) {
                $feedbackModel = new Feedback();
                $feedbackModel->delete($feedback_id);
            }
            \App\Helpers\Url::redirect('admin');
        }
    }
}
