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

    public function create($user_id, $feedback_id, $body) {
        $query = "INSERT INTO " . $this->table . " 
                  (user_id, feedback_id, body) 
                  VALUES (:user_id, :feedback_id, :body)";

        $stmt = $this->conn->prepare($query);

        $body = htmlspecialchars(strip_tags($body));

        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':feedback_id', $feedback_id);
        $stmt->bindParam(':body', $body);

        return $stmt->execute();
    }

    public function getByFeedbackId($feedback_id) {
        $query = "SELECT 
                    c.id, 
                    c.body, 
                    c.created_at, 
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
}
