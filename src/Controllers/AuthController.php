<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\Session;

class AuthController {
    
    public function showLogin() {
        view('auth/login');
    }

    public function showRegister() {
        view('auth/register');
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($email) || empty($password)) {
                header('Location: ../public/register?error=missing_fields');
                exit;
            }

            $userModel = new User();
            // Check if email exists (simple check logic could be added to model, but for now generic catch)
            try {
                if ($userModel->create($username, $email, $password)) {
                    // Auto login after register
                    $user = $userModel->findByEmail($email);
                    Session::set('user_id', $user['id']);
                    Session::set('username', $user['username']);
                    Session::set('role', $user['role']);
                    
                    \App\Helpers\Url::redirect('');
                } else {
                    \App\Helpers\Url::redirect('register?error=failed');
                }
            } catch (\Exception $e) {
                \App\Helpers\Url::redirect('register?error=exists');
            }
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password_hash'])) {
                Session::set('user_id', $user['id']);
                Session::set('username', $user['username']);
                Session::set('role', $user['role']);
                
                \App\Helpers\Url::redirect('');
            } else {
                \App\Helpers\Url::redirect('login?error=invalid_credentials');
            }
        }
    }

    public function logout() {
        Session::destroy();
        \App\Helpers\Url::redirect('login');
    }
}
