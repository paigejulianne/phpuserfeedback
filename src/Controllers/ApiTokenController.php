<?php

namespace App\Controllers;

use App\Models\ApiToken;
use App\Helpers\Session;

class ApiTokenController {
    
    public function __construct() {
        Session::requireAdmin();
    }

    public function index() {
        $tokenModel = new ApiToken();
        $tokens = $tokenModel->getAll();
        
        view('admin/tokens', ['tokens' => $tokens]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Currently generate token for CURRENT admin user. 
            // Could extend to select ANY user, but for now admin keys are sufficient.
            $user_id = Session::user()['id'];
            
            $tokenModel = new ApiToken();
            if ($tokenModel->generate($user_id)) {
                \App\Helpers\Url::redirect('admin/tokens?success=created');
            } else {
                \App\Helpers\Url::redirect('admin/tokens?error=failed');
            }
        }
    }

    public function revoke() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['token_id'] ?? null;
            if ($id) {
                $tokenModel = new ApiToken();
                $tokenModel->delete($id);
            }
            \App\Helpers\Url::redirect('admin/tokens?success=revoked');
        }
    }
}
