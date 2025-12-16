<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Comment {
    private $conn;
    private $table = 'comments';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($user_id, $feedback_id, $body, $screenshot_data = null, $screenshot_mime = null) {
        $query = "INSERT INTO " . $this->table . " 
                  (user_id, feedback_id, body, screenshot_data, screenshot_mime) 
                  VALUES (:user_id, :feedback_id, :body, :screenshot_data, :screenshot_mime)";

        $stmt = $this->conn->prepare($query);

        // Body sanitized by Controller/Sanitizer

        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':feedback_id', $feedback_id);
        $stmt->bindParam(':body', $body);
        $stmt->bindParam(':screenshot_data', $screenshot_data);
        $stmt->bindParam(':screenshot_mime', $screenshot_mime);

        return $stmt->execute();
    }

    public function getByFeedbackId($feedback_id) {
        $query = "SELECT 
                    c.id, 
                    c.body, 
                    c.created_at, 
                    c.screenshot_mime,
                    (c.screenshot_data IS NOT NULL) as has_screenshot,
                    u.username 
                  FROM " . $this->table . " c
                  JOIN users u ON c.user_id = u.id
                  WHERE c.feedback_id = :feedback_id
                  ORDER BY c.created_at ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':feedback_id', $feedback_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getCount($feedback_id) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE feedback_id = :feedback_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':feedback_id', $feedback_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }

    public function getImage($id) {
        $query = "SELECT screenshot_data, screenshot_mime FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
