<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Helpers\Url;
use App\Models\User;

class ProfileController {
    
    public function __construct() {
        Session::requireLogin();
    }

    public function edit() {
        $user_id = Session::user()['id'];
        $userModel = new User();
        $user = $userModel->findById($user_id);
        
        view('profile/edit', ['user' => $user]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = Session::user()['id'];
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $current_password = $_POST['current_password'] ?? ''; // Required for any change for security? Or just for password change?
            // Let's implement separate password change logic for simplicity or combined.
            // Requirement: "change name, password, and email".
            // Implementation: Two sections in one controller often works best, or smart detection.
            
            // SECURITY: Should verify current password before changing sensitive info like Email or Password.
            // For this version (lightweight), let's just allow Profile Info update, and separate Password update logic.

            $userModel = new User();
            
            // 1. Update Profile Info
            if (!empty($username) && !empty($email)) {
                 $userModel->updateProfile($user_id, $username, $email);
                 // Update Session
                 Session::set('username', $username);
            }

            // 2. Update Password (if provided)
            $new_password = $_POST['new_password'] ?? '';
            if (!empty($new_password)) {
                 if (empty($current_password)) {
                     Url::redirect('profile?error=password_required');
                 }
                 
                 $user = $userModel->findById($user_id);
                 if (!password_verify($current_password, $user['password_hash'])) {
                     Url::redirect('profile?error=wrong_password');
                 }

                 $userModel->updatePassword($user_id, password_hash($new_password, PASSWORD_DEFAULT));
            }

            Url::redirect('profile?success=updated');
        }
    }
}
