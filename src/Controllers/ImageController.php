<?php

namespace App\Controllers;

use App\Models\Feedback;
use App\Models\Comment;

class ImageController {
    
    public function show() {
        $type = $_GET['type'] ?? 'feedback';
        $id = $_GET['id'] ?? 0;
        
        $data = null;

        if ($type === 'feedback') {
            $model = new Feedback();
            $data = $model->getImage($id);
        } elseif ($type === 'comment') {
            $model = new Comment();
            $data = $model->getImage($id);
        }
        
        if ($data && !empty($data['screenshot_data'])) {
            header("Content-Type: " . $data['screenshot_mime']);
            echo $data['screenshot_data'];
            exit;
        } else {
            http_response_code(404);
            // Optional: output a 1x1 pixel or placeholder
            exit;
        }
    }
}
