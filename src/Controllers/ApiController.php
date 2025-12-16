<?php

namespace App\Controllers;

use App\Models\Feedback;
use App\Models\ApiToken;
use App\Helpers\Sanitizer;

class ApiController {
    
    private function authenticate() {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';
        
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
            $tokenModel = new ApiToken();
            $user_id = $tokenModel->validate($token);
            
            if ($user_id) {
                return $user_id;
            }
        }
        
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized. Invalid API Token.']);
        exit;
    }

    public function storeFeedback() {
        header('Content-Type: application/json');
        
        // Ensure POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
            return;
        }

        // Auth
        $user_id = $this->authenticate();

        // Parse Input (Expect JSON)
        $input = json_decode(file_get_contents('php://input'), true);
        
        $title = $input['title'] ?? '';
        $description = $input['description'] ?? ''; 
        $category_id = $input['category_id'] ?? 1;
        $target_email = $input['user_email'] ?? null;

        if (empty($title) || empty($description)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing title or description']);
            return;
        }

        // Handle "On Behalf Of" if provided
        if ($target_email) {
            $userModel = new \App\Models\User();
            $targetUser = $userModel->findByEmail($target_email);
            
            if ($targetUser) {
                // Use the existing user
                $user_id = $targetUser['id'];
            } else {
                // Optional: Auto-create user or Return Error.
                // For simplicity, let's create a "Shadow User" with random password? 
                // Or verify if this is safe. Admin API tokens implies trust.
                // Let's just create them.
                $username = explode('@', $target_email)[0];
                $temp_pass = bin2hex(random_bytes(8));
                if ($userModel->register($username, $target_email, $temp_pass)) {
                     // Since register doesn't return ID easily in current implementation, we fetch again
                     $targetUser = $userModel->findByEmail($target_email);
                     $user_id = $targetUser['id'];
                } else {
                     http_response_code(500);
                     echo json_encode(['error' => 'Could not create target user']);
                     return;
                }
            }
        }

        // Sanitize
        $description = Sanitizer::clean($description);
        $title = htmlspecialchars(strip_tags($title));

        // Create
        $feedbackModel = new Feedback();
        if ($feedbackModel->create($user_id, $category_id, $title, $description)) {
            http_response_code(201);
            echo json_encode(['success' => true, 'message' => 'Feedback created successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Database error']);
        }
    }
}
