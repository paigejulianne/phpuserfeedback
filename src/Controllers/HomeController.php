<?php

namespace App\Controllers;

use App\Models\Feedback;
use App\Helpers\Session;

class HomeController {
    public function index() {
        $sort = $_GET['sort'] ?? 'popular';
        $search = $_GET['q'] ?? '';

        $feedbackModel = new Feedback();
        $feedbacks = $feedbackModel->getAll($sort, $search);
        
        $user = Session::user();

        // Pass data to the view
        view('home', [
            'feedbacks' => $feedbacks, 
            'isLoggedIn' => Session::isLoggedIn(),
            'user' => $user,
            'currentSort' => $sort,
            'searchQuery' => $search
        ]);
    }
}
