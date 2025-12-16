<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Feedback {
    private $conn;
    private $table = 'feedback';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll($sort = 'popular', $search = '') {
        // Query to get feedback with category names and vote counts
        // We do NOT select the BLOB data here for performance, just check if it exists (NOT NULL)
        $query = "SELECT 
                    f.id, 
                    f.title, 
                    f.description, 
                    f.status, 
                    f.created_at, 
                    c.name as category_name,
                    COUNT(v.user_id) as vote_count,
                    (f.screenshot_data IS NOT NULL) as has_screenshot
                  FROM " . $this->table . " f
                  LEFT JOIN categories c ON f.category_id = c.id
                  LEFT JOIN votes v ON f.id = v.feedback_id";

        // Add Search
        $params = [];
        if (!empty($search)) {
            $query .= " WHERE (f.title LIKE :search OR f.description LIKE :search)";
            $params[':search'] = "%$search%";
        }

        $query .= " GROUP BY f.id";

        // Add Sort
        switch ($sort) {
            case 'newest':
                $query .= " ORDER BY f.created_at DESC";
                break;
            case 'oldest':
                $query .= " ORDER BY f.created_at ASC";
                break;
            // Admin Sorting
            case 'title_asc': $query .= " ORDER BY f.title ASC"; break;
            case 'title_desc': $query .= " ORDER BY f.title DESC"; break;
            case 'category_asc': $query .= " ORDER BY category_name ASC"; break;
            case 'category_desc': $query .= " ORDER BY category_name DESC"; break;
            case 'status_asc': $query .= " ORDER BY f.status ASC"; break;
            case 'status_desc': $query .= " ORDER BY f.status DESC"; break;
            case 'votes_asc': $query .= " ORDER BY vote_count ASC"; break;
            case 'votes_desc': $query .= " ORDER BY vote_count DESC"; break;
            
            case 'popular':
            default:
                $query .= " ORDER BY vote_count DESC, f.created_at DESC";
                break;
        }

        $stmt = $this->conn->prepare($query);
        
        // Bind params
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }

        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($user_id, $category_id, $title, $description, $screenshot_data = null, $screenshot_mime = null) {
        $query = "INSERT INTO " . $this->table . " 
                  (user_id, category_id, title, description, screenshot_data, screenshot_mime) 
                  VALUES (:user_id, :category_id, :title, :description, :screenshot_data, :screenshot_mime)";

        $stmt = $this->conn->prepare($query);

        $title = htmlspecialchars(strip_tags($title));
        // Description is already sanitized by Controller using HTMLPurifier

        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':screenshot_data', $screenshot_data);
        $stmt->bindParam(':screenshot_mime', $screenshot_mime);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function toggleVote($user_id, $feedback_id) {
        // Check if vote exists
        $checkQuery = "SELECT * FROM votes WHERE user_id = :user_id AND feedback_id = :feedback_id";
        $stmt = $this->conn->prepare($checkQuery);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':feedback_id', $feedback_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Remove vote
            $query = "DELETE FROM votes WHERE user_id = :user_id AND feedback_id = :feedback_id";
            $action = 'removed';
        } else {
            // Add vote
            $query = "INSERT INTO votes (user_id, feedback_id) VALUES (:user_id, :feedback_id)";
            $action = 'added';
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':feedback_id', $feedback_id);
        
        if ($stmt->execute()) {
            return $action;
        }
        return false;
    }

    public function getVoteCount($feedback_id) {
        $query = "SELECT COUNT(*) as count FROM votes WHERE feedback_id = :feedback_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':feedback_id', $feedback_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getById($id) {
        $query = "SELECT 
                    f.id, 
                    f.title, 
                    f.description, 
                    f.status, 
                    f.created_at, 
                    f.screenshot_mime,
                    (f.screenshot_data IS NOT NULL) as has_screenshot,
                    c.name as category_name,
                    COUNT(v.user_id) as vote_count
                  FROM " . $this->table . " f
                  LEFT JOIN categories c ON f.category_id = c.id
                  LEFT JOIN votes v ON f.id = v.feedback_id
                  WHERE f.id = :id
                  GROUP BY f.id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getImage($id) {
        $query = "SELECT screenshot_data, screenshot_mime FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
